<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;

class SupplierPaymentController extends Controller
{
    public function getSupplierPayment()
    {
        return DB::select("SELECT
        sp.*,
        s.supplier_code,
        s.name,
        ba.account_no,
        ba.bank_name,
        u.name AS user_name
    FROM
        supplier_payments AS sp
        LEFT JOIN suppliers AS s ON s.id = sp.supplier_id
        LEFT JOIN bank_accounts AS ba ON ba.id = sp.bank_id
        LEFT JOIN users AS u ON u.id = sp.added_by
          ORDER BY sp.id DESC");
    }

    public function saveSupplierPayment(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new SupplierPayment();
            } else {
                $data = SupplierPayment::find($request->id);
            }

            $data->date           = $request->date;
            $data->payment_type   = $request->payment_type;
            $data->bank_id        = $request->bank_id;
            $data->supplier_id    = $request->supplier_id;
            $data->due            = $request->due;
            $data->payment_amount = $request->payment_amount;
            $data->description    = $request->description;
            $data->added_by       = $request->added_by;
            $data->save();

            if (empty($request->id)) {
                return "SupplierPayment save successfully";
            } else {
                return "SupplierPayment updated successfully";
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            return "Opps! something went wrong";
        }
    }

    public function deleteSupplierPayment($id)
    {
        $data = SupplierPayment::find($id);
        $data->delete();
        return "SupplierPayment Delete Successfully";
    }
}
