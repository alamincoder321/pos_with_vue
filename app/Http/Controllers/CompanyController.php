<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    public function getCompany()
    {
        return CompanyProfile::first();
    }

    public function saveCompany(Request $request)
    {
        try {
            $data             = CompanyProfile::find($request->id);
            $data->name       = $request->name;
            $data->phone      = $request->phone;
            $data->address    = $request->address;
            $data->email      = $request->email;
            $data->facebook   = $request->facebook;
            $data->instagram  = $request->instagram;
            $data->linkedin   = $request->linkedin;
            $data->twitter    = $request->twitter;
            $data->paper_type = $request->paper_type;

            if ($request->hasFile("company_logo")) {
                $old = $data->company_logo;
                if (File::exists($old)) {
                    File::delete($old);
                }
                $data->company_logo = $this->imageUpload($request, "company_logo", "uploads/company_profile");
            }
            $data->save();
            return "Company Profile updated successfully";
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }
}
