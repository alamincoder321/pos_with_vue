<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ModelTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    public function getCustomer()
    {
        $gen_code = $this->generateCode('Customer', 'C');
        $customers = DB::select("SELECT c.*, CONCAT(c.customer_code, ' - ', c.name) AS display_name, ci.name AS city_name FROM customers AS c LEFT JOIN cities AS ci ON ci.id=c.city_id ORDER BY name");
        return response()->json(["gen_code" => $gen_code, "customers" => $customers]);
    }

    public function saveCustomer(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Customer();
            } else {
                $data = Customer::find($request->id);
                $old  = $data->image;
            }

            $data->customer_code = $request->customer_code;
            $data->name          = $request->name;
            $data->owner_name    = $request->owner_name;
            $data->address       = $request->address;
            $data->phone         = $request->phone;
            $data->city_id       = $request->city_id;
            $data->previous_due  = $request->previous_due;
            $data->customer_type = $request->customer_type;
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/customers");
            }
            $data->save();

            if (empty($request->id)) {
                return "Customer save successfully";
            } else {
                return "Customer updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteCustomer($id)
    {
        $data = Customer::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Customer Delete Successfully";
    }

    // total due get
    public function totalDue(Request $request)
    {
        return ModelTable::customerDue($request->id);
    }

    // ledger
    public function CustomerLedger(Request $request)
    {
        $cusDue = DB::select("SELECT c.previous_due AS previousDue FROM customers c WHERE c.id = '$request->id'");

        $result = DB::select("SELECT
                        'a' AS sequence,
                        sm.date AS date,
                        concat('Sale Invoice: ',sm.invoice) AS description,
                        sm.total AS billAmount,
                        sm.paid AS paidAmount,
                        sm.due AS dueAmount
                        FROM sales sm
                        WHERE sm.customer_id = '$request->id'
                        
                    UNION
                        SELECT
                        'b' AS sequence,
                        cp.date AS date,
                        'Customer Payment' AS description,
                        0.00 AS billAmount,
                        cp.payment_amount AS paidAmount,
                        0.00 AS dueAmount
                        FROM customer_payments cp
                        WHERE cp.customer_id = '$request->id'
                        ORDER BY date, sequence ASC");

        $ledger = array_map(function ($key, $row) use ($result) {
            $row->due = $key == 0 ? $row->billAmount - $row->paidAmount : ($result[$key - 1]->due + ($row->billAmount - $row->paidAmount));
            return $row;
        }, array_keys($result), $result);

        $preDue = $cusDue[0]->previousDue;
        if (count($ledger) > 0) {
            foreach($ledger as $val){
                $val->due += $preDue;
            }
        }

        $previousRows = array_filter($ledger, function ($row) use ($request) {
            return $row->date < $request->dateFrom;
        });
        $previousDue = empty($previousRows) ? $preDue : end($previousRows)->due;

        return ["ledger" => $ledger, "previousDue" => $previousDue];
    }
}
