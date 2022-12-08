<?php

namespace App\Http\Controllers;

use App\Models\CustomerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    public function getCustomerPayment()
    {
        return DB::select("SELECT
        cp.*,
        c.customer_code,
        c.name,
        ba.account_no,
        ba.bank_name,
        u.name AS user_name
    FROM
        customer_payments AS cp
        LEFT JOIN customers AS c ON c.id = cp.customer_id
        LEFT JOIN bank_accounts AS ba ON ba.id = cp.bank_id
        LEFT JOIN users AS u ON u.id = cp.added_by
          ORDER BY cp.id DESC");
    }

    public function saveCustomerPayment(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new CustomerPayment();
            } else {
                $data = CustomerPayment::find($request->id);
            }

            $data->date           = $request->date;
            $data->payment_type   = $request->payment_type;
            $data->bank_id        = $request->bank_id;
            $data->customer_id    = $request->customer_id;
            $data->due            = $request->due;
            $data->payment_amount = $request->payment_amount;
            $data->description    = $request->description;
            $data->added_by       = $request->added_by;
            $data->save();

            if (empty($request->id)) {
                return "CustomerPayment save successfully";
            } else {
                return "CustomerPayment updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteCustomerPayment($id)
    {
        $data = CustomerPayment::find($id);
        $data->delete();
        return "CustomerPayment Delete Successfully";
    }
}
