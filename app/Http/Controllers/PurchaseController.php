<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function getPurchase()
    {
        $total_invoice = Purchase::first();
        if ($total_invoice->count() > 0) {
            $invoice = $total_invoice->invoice + 1;
        }else{
            $invoice = "#0". date("Y")."0000";
        }
    }

    public function savePurchase(Request $request)
    {
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
            $data->added_by       = $request->added_by;
            $data->save();

            $last_id = $data->id;
            foreach($request->products as $item){
                $details = new PurchaseDetails();
                $details->purchase_id = $last_id;
                $details->product_id = $item->id;
                $details->qty = $item->qty;
                $details->purchase_price = $item->purchase_price;
                $details->selling_price = $item->selling_price;
                $details->total_price = $item->total_price;
                $details->save();
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
