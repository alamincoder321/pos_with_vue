<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function getPurchase(Request $request)
    {
        $clauses = '';
        if (isset($request->dateFrom) && !empty($request->dateFrom)) {
            $clauses .= " AND p.date BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if (isset($request->invoice) && !empty($request->invoice)) {
            $clauses .= " AND p.invoice = '$request->invoice'";
        }
        if (isset($request->supplier_id) && !empty($request->supplier_id)) {
            $clauses .= " AND p.supplier_id = '$request->supplier_id'";
        }
        $purchases = DB::select("SELECT
                            p.*,
                            s.id as supplier_id,
                            s.name,
                            s.supplier_code as code,
                            CONCAT(s.supplier_code, ' - ', s.name) as display_name,
                            s.address,
                            s.phone,
                            s.supplier_type,
                            CONCAT(ac.account_no, '-(', ac.bank_name, ')') AS bank_display_name,
                            u.name AS user_name
                        FROM
                            purchases AS p
                        LEFT JOIN suppliers AS s
                        ON s.id = p.supplier_id
                        LEFT JOIN bank_accounts AS ac
                        ON ac.id = p.account_id
                        LEFT JOIN users AS u
                        ON u.id = p.added_by
                        WHERE 1=1
                        $clauses ORDER BY p.invoice DESC                            
                        ");

        foreach ($purchases as $purchase) {
            $purchase->purchaseDetails = DB::select("SELECT
                                    pd.*,
                                    p.name,
                                    un.name as unit_name
                                FROM
                                    purchase_details AS pd
                                LEFT JOIN products AS p ON p.id = pd.product_id
                                LEFT JOIN units AS un ON un.id = p.unit_id
                                WHERE pd.purchase_id = ?", [$purchase->id]);
        }

        $invoice = $this->invoiceGenerate("Purchase", "PI");
        return response()->json(['invoice' => $invoice, 'purchases' => $purchases]);
    }



    public function savePurchase(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->purchase['id'] == null) {
                $data = new Purchase();
            } else {
                $data = Purchase::find($request->purchase['id']);
            }

            if ($request->supplier['supplier_type'] == 'G') {
                if ($request->supplier['id'] != null) {
                    $s = Supplier::find($request->supplier['id']);
                } else {
                    $s                = new Supplier();
                }
                $s->name          = isset($request->supplier['name']) ? $request->supplier['name']:"";
                $s->phone         = isset($request->supplier['phone']) ? $request->supplier['phone']:"";
                $s->address       = isset($request->supplier['address']) ? $request->supplier['address']:"";
                $s->supplier_type = isset($request->supplier['supplier_type']) ? $request->supplier['supplier_type']:"G";
                $s->save();

                $supplier_id = $s->id;
            } else {
                $supplier_id = $request->supplier['id'];
            }

            $data->invoice         = $request->purchase['invoice'];
            $data->date            = $request->purchase['date'];
            $data->supplier_id     = $supplier_id;
            $data->subtotal        = $request->purchase['subtotal'];
            $data->total           = $request->purchase['total'];
            $data->paid            = $request->purchase['paid'];
            $data->due             = $request->purchase['due'];
            $data->previous_due    = $request->purchase['previous_due'];
            $data->vat             = $request->purchase['vat'];
            $data->vat_amount      = $request->purchase['vat_amount'];
            $data->discount        = $request->purchase['discount'];
            $data->discount_amount = $request->purchase['discount_amount'];
            $data->transport_cost  = $request->purchase['transport_cost'];
            $data->payment_type    = $request->purchase['payment_type'];
            $data->account_id      = $request->purchase['account_id'];
            $data->note            = $request->purchase['note'];
            $data->added_by        = $request->purchase['added_by'];
            $data->save();

            if ($request->purchase['id'] != null) {
                $last_id = $request->purchase['id'];
                $purchaseqtys = PurchaseDetails::where("purchase_id", $last_id)->get();
                foreach($purchaseqtys as $inventoryqty){
                    $inventoryQtyUpdate = ProductInventory::where("product_id", $inventoryqty->product_id)->first();
                    $inventoryQtyUpdate->purchase_qty = $inventoryQtyUpdate->purchase_qty-$inventoryqty->quantity;
                    $inventoryQtyUpdate->save();
                }
                PurchaseDetails::where("purchase_id", $last_id)->delete();
            } else {
                $last_id = $data->id;
            }
            foreach ($request->carts as $item) {
                $details                 = new PurchaseDetails();
                $details->purchase_id    = $last_id;
                $details->product_id     = $item['product_id'];
                $details->quantity       = $item['quantity'];
                $details->purchase_price = $item['purchase_price'];
                $details->selling_price  = $item['selling_price'];
                $details->total_amount   = $item['total_amount'];
                $details->save();

                //inventory-update
                $inventory_check = ProductInventory::where("product_id", $item['product_id'])->first();

                if (!empty($inventory_check)) {
                    $inventory_check->purchase_qty = $inventory_check->purchase_qty+$item['quantity'];
                    $inventory_check->save();
                } else {
                    $new_inventory = new ProductInventory();
                    $new_inventory->product_id = $item['product_id'];
                    $new_inventory->purchase_qty = $item['quantity'];
                    $new_inventory->save();
                }

                //update product price
                $productchange = Product::where("id", $item['product_id'])->first();
                $productchange->purchase_price = $item['purchase_price'];
                $productchange->selling_price = $item['selling_price'];
                $productchange->save();
            }

            DB::commit();

            if ($request->purchase['id'] == null) {
                return response()->json(['invoice'=>$request->purchase['invoice'], 'msg'=>"Purchase save successfully"]);
            } else {
                return response()->json(['invoice'=>$request->purchase['invoice'], 'msg'=>"Purchase updated successfully"]);
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return "Opps! something went wrong";
        }
    }

    public function deletePurchase($id)
    {
        $details = PurchaseDetails::where("purchase_id", $id)->get();
        foreach ($details as $item) {
            $inventory = ProductInventory::where("product_id", $item->product_id)->first();
            $inventory->purchase_qty = $inventory->purchase_qty - $item->quantity;
            $inventory->save();
        }
        Purchase::find($id)->delete();
        return "Purchae Delete";
    }
}
