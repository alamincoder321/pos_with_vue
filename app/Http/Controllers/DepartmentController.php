<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function getDepartment()
    {
        return DB::select("SELECT * FROM departments ORDER BY name");
    }

    public function saveDepartment(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Department();
            } else {
                $data = Department::find($request->id);
            }

            $data->name     = $request->name;
            $data->save();

            if (empty($request->id)) {
                return "Department save successfully";
            } else {
                return "Department updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteDepartment($id)
    {
        $data = Department::find($id);
        $data->delete();
        return "Department Delete Successfully";
    }
} 
