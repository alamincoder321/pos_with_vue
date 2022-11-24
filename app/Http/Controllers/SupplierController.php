<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SupplierController extends Controller
{
    public function getSupplier()
    {
        $gen_code = $this->generateCode('Supplier', 'S-');
        $suppliers = DB::select("SELECT s.*, CONCAT(s.supplier_code, ' - ', s.name) AS display_name, ci.name AS city_name FROM suppliers AS s LEFT JOIN cities AS ci ON ci.id=s.city_id ORDER BY name");
        return response()->json(["gen_code" => $gen_code, "suppliers" => $suppliers]);
    }

    public function saveSupplier(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Supplier();
            } else {
                $data = Supplier::find($request->id);
                $old  = $data->image;
            }

            $data->supplier_code = $request->supplier_code;
            $data->name          = $request->name;
            $data->owner_name    = $request->owner_name;
            $data->address       = $request->address;
            $data->phone         = $request->phone;
            $data->city_id       = $request->city_id;
            $data->previous_due  = $request->previous_due;
            $data->supplier_type = $request->supplier_type;
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/suppliers");
            }
            $data->save();

            if (empty($request->id)) {
                return "Supplier save successfully";
            } else {
                return "Supplier updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteSupplier($id)
    {
        $data = Supplier::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Supplier Delete Successfully";
    }
}
