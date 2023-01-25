<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function getExpense()
    {
        $expenses = Expense::orderBy("date", "DESC")->get();
        $expense_code = $this->generateCode("Expense", "EX");
        return ["expenses" => $expenses, "expense_code" => $expense_code];
    }

    public function saveExpense(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Expense();
            } else {
                $data = Expense::find($request->id);
            }

            $data->expense_code = $request->expense_code;
            $data->date         = $request->date;
            $data->description  = $request->description;
            $data->amount       = $request->amount;
            $data->save();

            if (empty($request->id)) {
                return "Expense save successfully";
            } else {
                return "Expense updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteExpense($id)
    {
        $data = Expense::find($id);
        $data->delete();
        return "Expense Delete Successfully";
    }
}

