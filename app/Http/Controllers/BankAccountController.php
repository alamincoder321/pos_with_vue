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
        return DB::select("SELECT *, CONCAT(account_no, '-(', bank_name, ')') AS display_name FROM bank_accounts ORDER BY account_name");
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
}
