<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function getTransaction()
    {
        return DB::select("SELECT t.*, ba.bank_name, CONCAT(ba.account_no, '-(', ba.bank_name, ')') AS display_name FROM transactions AS t LEFT JOIN bank_accounts AS ba ON ba.id = t.account_id ORDER BY date DESC");
    }

    public function saveTransaction(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Transaction;
            } else {
                $data = Transaction::find($request->id);
            }

            $data->date             = $request->date;
            $data->transaction_type = $request->transaction_type;
            $data->account_id       = $request->account_id;
            $data->amount           = $request->amount;
            $data->description      = $request->description;
            $data->save();

            if (empty($request->id)) {
                return "Transaction save successfully";
            } else {
                return "Transaction updated successfully";
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            return "Opps! something went wrong";
        }
    }

    public function deleteTransaction($id)
    {
        $data = Transaction::find($id);
        $data->delete();
        return "Transaction Delete Successfully";
    }
}
