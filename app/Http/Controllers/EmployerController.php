<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\SalaryGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EmployerController extends Controller
{
    public function getEmployer()
    {
        $gen_code = $this->generateCode('Employer', 'E');
        $employers = DB::select("SELECT e.*, ci.name AS city_name, d.name AS department_name FROM employers AS e LEFT JOIN cities AS ci ON ci.id=e.city_id LEFT JOIN departments AS d ON d.id=e.department_id ORDER BY name");
        return response()->json(["gen_code" => $gen_code, "employers" => $employers]);
    }

    public function saveEmployer(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Employer();
            } else {
                $data = Employer::find($request->id);
                $old  = $data->image;
            }

            $data->employer_code = $request->employer_code;
            $data->name          = $request->name;
            $data->phone         = $request->phone;
            $data->designation   = $request->designation;
            $data->department_id = $request->department_id;
            $data->mother_name   = $request->mother_name;
            $data->father_name   = $request->father_name;
            $data->dob           = $request->dob;
            $data->join_date     = $request->join_date;
            $data->city_id       = $request->city_id;
            $data->address       = $request->address;
            $data->salary        = $request->salary;
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/employers");
            }
            $data->save();

            if (empty($request->id)) {
                return "Employer save successfully";
            } else {
                return "Employer updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong".$e->getMessage();
        }
    }

    public function deleteEmployer($id)
    {
        $data = Employer::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Employer Delete Successfully";
    }

    // generate Salary
    public function salaryGenerate(Request $request)
    {
        try{
            $clauses = "";
            if(!empty($request->employeeId)){
                $clauses = " AND e.id = '$request->employeeId'";
            }
            $employers = DB::select("SELECT e.* FROM employers e WHERE 1 = 1 $clauses");

            $monthyear = explode("-", $request->monthName);
            $month = $monthyear[1];
            $year = $monthyear[0];
            $totalDaysMonth = date('t', mktime(0, 0, 0, $month, 1, $year));

            foreach($employers as $item){
                $check = SalaryGenerate::where("month", $request->monthName)->where("employee_id", $item->id)->first();
                if(empty($check)){
                    for ($i=0; $i < $totalDaysMonth; $i++) { 
                        $data                 = new SalaryGenerate();
                        $data->employee_id    = $item->id;
                        $data->month          = $request->monthName;
                        $data->salary         = $item->salary;
                        $data->overTimeBonus  = 0;
                        $data->leaveDeduction = 0;
                        $data->advance        = 0;
                        $data->added_by       = $request->added_by;
                        $data->save();
                    }
                }
            }

            return SalaryGenerate::where("month", $request->monthName)->where('status', 'p')->get();
        }catch(\Throwable $e){
            return "Opps! somehting went wrong";
        }
    }
}
