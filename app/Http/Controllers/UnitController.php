<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function getUnit()
    {
        return DB::select("SELECT * FROM units ORDER BY name");
    }

    public function saveUnit(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Unit();
            } else {
                $data = Unit::find($request->id);
            }

            $data->name     = $request->name;
            $data->save();

            if (empty($request->id)) {
                return "Unit save successfully";
            } else {
                return "Unit updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteUnit($id)
    {
        $data = Unit::find($id);
        $data->delete();
        return "Unit Delete Successfully";
    }
}
