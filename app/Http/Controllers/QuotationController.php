<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuotationDetail;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function getQuotation(Request $request)
    {
        $clauses = '';
        if (isset($request->dateFrom) && !empty($request->dateFrom)) {
            $clauses .= " AND qu.date BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if (isset($request->invoice) && !empty($request->invoice)) {
            $clauses .= " AND qu.invoice = '$request->invoice'";
        }
        $quotations = DB::select("SELECT
                            qu.*,
                            u.name AS user_name
                        FROM
                            quotations AS qu
                        LEFT JOIN users AS u
                        ON u.id = qu.added_by
                        WHERE 1=1
                        $clauses ORDER BY qu.invoice DESC                            
                        ");

        foreach ($quotations as $quotation) {
            $quotation->quotationDetails = DB::select("SELECT
                                    qud.*,
                                    p.name,
                                    un.name as unit_name
                                FROM
                                    quotation_details AS qud
                                LEFT JOIN products AS p ON p.id = qud.product_id
                                LEFT JOIN units AS un ON un.id = p.unit_id
                                WHERE qud.quotation_id = ?", [$quotation->id]);
        }

        $invoice = $this->invoiceNumberQuotation();
        return response()->json(['invoice' => $invoice, 'quotations' => $quotations]);
    }



    public function saveQuotation(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->quotation['id'] == null) {
                $data = new Quotation();
            } else {
                $data = Quotation::find($request->quotation['id']);
                QuotationDetail::where("quotation_id", $request->quotation['id'])->delete();
            }

            $data->invoice          = $request->quotation['invoice'];
            $data->date             = $request->quotation['date'];
            $data->customer_name    = $request->quotation['customer_name'];
            $data->customer_phone   = $request->quotation['customer_phone'];
            $data->customer_address = $request->quotation['customer_address'];
            $data->subtotal         = $request->quotation['subtotal'];
            $data->total            = $request->quotation['total'];
            $data->vat              = $request->quotation['vat'];
            $data->vat_amount       = $request->quotation['vat_amount'];
            $data->discount         = $request->quotation['discount'];
            $data->discount_amount  = $request->quotation['discount_amount'];
            $data->transport_cost   = $request->quotation['transport_cost'];
            $data->note             = $request->quotation['note'];
            $data->added_by         = $request->quotation['added_by'];
            $data->save();

            $quotation_id = $data->id;

            foreach ($request->carts as $item) {
                $details                = new QuotationDetail();
                $details->quotation_id  = $quotation_id;
                $details->product_id    = $item['product_id'];
                $details->quantity      = $item['quantity'];
                $details->warranty      = $item['warranty'];
                $details->selling_price = $item['selling_price'];
                $details->total_amount  = $item['total_amount'];
                $details->save();
            }

            DB::commit();

            if ($request->quotation['id'] == null) {
                return response()->json(['invoice' => $request->quotation['invoice'], 'msg' => "Quotation save successfully"]);
            } else {
                return response()->json(['invoice' => $request->quotation['invoice'], 'msg' => "Quotation updated successfully"]);
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return "Opps! something went wrong";
        }
    }

    public function deleteQuotation($id)
    {
        Quotation::find($id)->delete();
        return "Quotation Delete";
    }
}
