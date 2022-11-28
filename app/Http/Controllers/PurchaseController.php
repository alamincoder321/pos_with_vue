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
        if(isset($request->dateFrom) && !empty($request->dateFrom)){
            $clauses .= " AND p.date BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if(isset($request->invoice) && !empty($request->invoice)){
            $clauses .= " AND p.invoice = '$request->invoice'";
        }
        $purchases = DB::select("SELECT
                            p.*,
                            s.name,
                            s.address,
                            s.phone,
                            u.name AS user_name
                        FROM
                            purchases AS p
                        LEFT JOIN suppliers AS s
                        ON s.id = p.supplier_id
                        LEFT JOIN users AS u
                        ON u.id = p.added_by
                        WHERE p.status = 'a'
                        $clauses ORDER BY p.invoice DESC                            
                        ");

        $invoice = $this->invoiceNumberPurchase();
        return response()->json(['invoice' => $invoice, 'purchases' => $purchases]);
    }





    public function savePurchase(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Purchase();
            } else {
                $data = Purchase::find($request->id);
            }

            if ($request->supplier['supplier_type'] == 'G') {
                $s                = new Supplier();
                $s->name          = $request->supplier['name'];
                $s->phone         = $request->supplier['phone'];
                $s->address       = $request->supplier['address'];
                $s->supplier_type = $request->supplier['supplier_type'];
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

            $last_id = $data->id;
            foreach ($request->carts as $item) {
                $details = new PurchaseDetails();
                $details->purchase_id = $last_id;
                $details->product_id = $item['id'];
                $details->quantity = $item['quantity'];
                $details->purchase_price = $item['purchase_price'];
                $details->selling_price = $item['selling_price'];
                $details->total_price = $item['total_amount'];
                $details->save();

                //inventory-update
                $inventory_check = ProductInventory::where("product_id", $item['id'])->first();
                if (!empty($inventory_check)) {
                    $inventory_check->purchase_qty = $item['quantity'];
                    $inventory_check->save();
                } else {
                    $new_inventory = new ProductInventory();
                    $new_inventory->product_id = $item['id'];
                    $new_inventory->purchase_qty = $item['quantity'];
                    $new_inventory->save();
                }

                //update product price
                $productchange = Product::where("id", $item['id'])->first();
                $productchange->purchase_price = $item['purchase_price'];
                $productchange->selling_price = $item['selling_price'];
                $productchange->save();
            }

            if (empty($request->id)) {
                return "Purchase save successfully";
            } else {
                return "Purchase updated successfully";
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            return "Opps! something went wrong";
        }
    }
}
