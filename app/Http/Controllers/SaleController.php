<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function getSale(Request $request)
    {
        $clauses = '';
        if (isset($request->dateFrom) && !empty($request->dateFrom)) {
            $clauses .= " AND p.date BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if (isset($request->invoice) && !empty($request->invoice)) {
            $clauses .= " AND p.invoice = '$request->invoice'";
        }
        $sales = DB::select("SELECT
                            p.*,
                            s.id as customer_id,
                            s.name,
                            CONCAT(s.customer_code, ' - ', s.name) as display_name,
                            s.address,
                            s.phone,
                            s.customer_type,
                            u.name AS user_name
                        FROM
                            sales AS p
                        LEFT JOIN customers AS s
                        ON s.id = p.customer_id
                        LEFT JOIN users AS u
                        ON u.id = p.added_by
                        WHERE 1=1
                        $clauses ORDER BY p.invoice DESC                            
                        ");

        foreach ($sales as $sale) {
            $sale->saleDetails = DB::select("SELECT
                                    pd.*,
                                    p.name
                                FROM
                                    sale_details AS pd
                                LEFT JOIN products AS p ON p.id = pd.product_id
                                WHERE pd.sale_id = ?", [$sale->id]);
        }

        $invoice = $this->invoiceNumberSale();
        return response()->json(['invoice' => $invoice, 'sales' => $sales]);
    }



    public function saveSale(Request $request)
    {
        try {
            if ($request->sale['id'] == null) {
                $data = new Sale();
            } else {
                $data = Sale::find($request->sale['id']);
            }

            if ($request->customer['customer_type'] == 'G') {
                if ($request->customer['id'] != null) {
                    $s = Customer::find($request->customer['id']);
                } else {
                    $s                = new Customer();
                }
                $s->name          = $request->customer['name'];
                $s->phone         = $request->customer['phone'];
                $s->address       = $request->customer['address'];
                $s->customer_type = $request->customer['customer_type'];
                $s->save();

                $customer_id = $s->id;
            } else {
                $customer_id = $request->customer['id'];
            }

            $data->invoice         = $request->sale['invoice'];
            $data->date            = $request->sale['date'];
            $data->customer_id     = $customer_id;
            $data->subtotal        = $request->sale['subtotal'];
            $data->total           = $request->sale['total'];
            $data->paid            = $request->sale['paid'];
            $data->due             = $request->sale['due'];
            $data->previous_due    = $request->sale['previous_due'];
            $data->vat             = $request->sale['vat'];
            $data->vat_amount      = $request->sale['vat_amount'];
            $data->discount        = $request->sale['discount'];
            $data->discount_amount = $request->sale['discount_amount'];
            $data->transport_cost  = $request->sale['transport_cost'];
            $data->payment_type    = $request->sale['payment_type'];
            $data->account_id      = $request->sale['account_id'];
            $data->note            = $request->sale['note'];
            $data->added_by        = $request->sale['added_by'];
            $data->save();

            if ($request->sale['id'] != null) {
                $last_id = $request->sale['id'];
                SaleDetail::where("sale_id", $last_id)->delete();
            } else {
                $last_id = $data->id;
            }
            foreach ($request->carts as $item) {
                $details = new SaleDetail();
                $details->sale_id = $last_id;
                if ($request->sale['id'] != null) {
                    $details->product_id = $item['product_id'];
                } else {
                    $details->product_id = $item['id'];
                }
                $details->quantity = $item['quantity'];
                $details->selling_price = $item['selling_price'];
                $details->total_amount = $item['total_amount'];
                $details->save();

                //inventory-update
                if ($request->sale['id'] != null) {
                    $inventory_check = ProductInventory::where("product_id", $item['product_id'])->first();
                } else {
                    $inventory_check = ProductInventory::where("product_id", $item['id'])->first();
                }
                if (!empty($inventory_check)) {
                    $inventory_check->sale_qty = $item['quantity'];
                    $inventory_check->save();
                } else {
                    $new_inventory = new ProductInventory();
                    $new_inventory->product_id = $item['id'];
                    $new_inventory->sale_qty = $item['quantity'];
                    $new_inventory->save();
                }
            }

            if ($request->sale['id'] == null) {
                return "Sale save successfully";
            } else {
                return "Sale updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }
}
