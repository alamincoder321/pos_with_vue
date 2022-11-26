<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getPurchase()
    {
        $invoice = $this->invoiceNumberPurchase();
        return response()->json(['invoice'=>$invoice]);
    }

    public function savePurchase(Request $request)
    {
        return $request->all();
        try {
            if (empty($request->id)) {
                $data = new Purchase();
            } else {
                $data = Purchase::find($request->id);
            }

            $data->invoice        = $request->invoice;
            $data->date           = $request->date;
            $data->supplier_id    = $request->supplier_id;
            $data->subtotal       = $request->subtotal;
            $data->total          = $request->total;
            $data->paid           = $request->paid;
            $data->due            = $request->due;
            $data->previous_due   = $request->previous_due;
            $data->vat            = $request->vat;
            $data->vat_amount     = $request->vat_amount;
            $data->discount       = $request->discount;
            $data->transport_cost = $request->transport_cost;
            $data->payment_type   = $request->payment_type;
            $data->account_id     = $request->account_id;
            $data->note           = $request->note;
            $data->added_by       = $request->added_by;
            $data->save();

            $last_id = $data->id;
            foreach($request->carts as $item){
                $details = new PurchaseDetails();
                $details->purchase_id = $last_id;
                $details->product_id = $item->id;
                $details->quantity = $item->quantity;
                $details->purchase_price = $item->purchase_price;
                $details->selling_price = $item->selling_price;
                $details->total_price = $item->total_price;
                $details->save();

                //inventory-update
                $inventory_check = ProductInventory::where("product_id", $item->id)->first();
                if(count($inventory_check) > 0){
                    $inventory_check->purchase_qty = $item->quantity;
                    $inventory_check->save();
                }else{
                    $new_inventory = new ProductInventory();
                    $new_inventory->product_id = $item->id;
                    $new_inventory->quantity = $item->quantity;
                    $new_inventory->save();
                }

                //update product price
                $productchange = Product::where("id", $item->id)->first();
                $productchange->purchase_price = $item->purchase_price;
                $productchange->selling_price = $item->selling_price;
                $productchange->save();
            }

            if (empty($request->id)) {
                return "Purchase save successfully";
            } else {
                return "Purchase updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }
}
