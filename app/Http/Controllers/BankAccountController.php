<?php

namespace App\Http\Controllers;

use App\Models\ModelTable;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function getBankAccount()
    {
        return DB::select("SELECT *, CONCAT(account_no, '-(', bank_name, ')') AS display_name FROM bank_accounts ORDER BY account_name ASC");
    }

    public function saveBankAccount(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new BankAccount();
            } else {
                $data = BankAccount::find($request->id);
            }

            $data->account_name = $request->account_name;
            $data->account_no   = $request->account_no;
            $data->account_type = $request->account_type;
            $data->bank_name    = $request->bank_name;
            $data->branch_name  = $request->branch_name;
            $data->description  = $request->description;
            $data->status       = $request->status;
            $data->save();

            if (empty($request->id)) {
                return "BankAccount save successfully";
            } else {
                return "BankAccount updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteBankAccount($id)
    {
        $data = BankAccount::find($id);        
        $data->delete();
        return "BankAccount Delete Successfully";
    }

    // bank balance
    public function bankBalance(Request $request)
    {
        return ModelTable::bankBalance($request->id, $request->fromDate, $request->toDate);
    }

    // bank ledger
    public function BankLedger(Request $request)
    {
        $result = DB::select("SELECT
                                'a' AS sequence,
                                tr.date AS date,
                                CONCAT('Deposit From Bank') AS description,
                                tr.transaction_type AS transaction_type,
                                tr.amount AS deposit,
                                0.00 AS withdraw
                                FROM transactions tr
                                WHERE tr.transaction_type = 'deposit'
                                AND tr.account_id = '$request->id'
                        UNION
                            SELECT
                                'b' AS sequence,
                                tr.date AS date,
                                CONCAT('Withdraw From Bank') AS description,
                                tr.transaction_type AS transaction_type,
                                0.00 AS deposit,
                                tr.amount AS withdraw
                                FROM transactions tr
                                WHERE tr.transaction_type = 'withdraw' 
                                AND tr.account_id = '$request->id'
                        UNION
                            SELECT
                                'c' AS sequence,
                                pm.date AS date,
                                CONCAT('Purchase by Bank') AS description,
                                'withdraw' AS transaction_type,
                                0.00 AS deposit,
                                pm.paid AS withdraw
                                FROM purchases pm
                                WHERE pm.payment_type = 'bank' 
                                AND pm.account_id = '$request->id'
                        UNION
                            SELECT
                                'd' AS sequence,
                                sm.date AS date,
                                CONCAT('Sales by Bank') AS description,
                                'deposit' AS transaction_type,
                                sm.paid AS deposit,
                                0.00 AS withdraw
                                FROM sales sm
                                WHERE sm.payment_type = 'bank' 
                                AND sm.account_id = '$request->id'
                        UNION
                            SELECT
                                'e' AS sequence,
                                sp.date AS date,
                                CONCAT('Supplier payment by Bank') AS description,
                                'withdraw' AS transaction_type,
                                0.00 AS deposit,
                                sp.payment_amount AS withdraw
                                FROM supplier_payments sp
                                WHERE sp.payment_type = 'bank'
                                AND sp.bank_id = '$request->id'
                        UNION
                            SELECT
                                'f' AS sequence,
                                cp.date AS date,
                                CONCAT('Customer payment by Bank') AS description,
                                'deposit' AS transaction_type,
                                cp.payment_amount AS deposit,
                                0.00 AS withdraw
                                FROM customer_payments cp
                                WHERE cp.payment_type = 'bank'
                                AND cp.bank_id = '$request->id'
                            ORDER BY date, sequence ASC");

        $ledger = array_map(function ($key, $row) use ($result) {
            $row->balance = $key == 0 ? $row->deposit - $row->withdraw : ($result[$key - 1]->balance + ($row->deposit - $row->withdraw));
            return $row;
        }, array_keys($result), $result);

        $previousRows = array_filter($ledger, function ($row) use ($request) {
            return $row->date < $request->dateFrom;
        });

        $previousBalance = empty($previousRows) ? 0 : end($previousRows)->balance;

        return ["ledger" => $ledger, "previousBalance" => $previousBalance];
    }
}
