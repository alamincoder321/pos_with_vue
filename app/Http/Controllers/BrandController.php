<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function getBrand()
    {
        return DB::select("SELECT * FROM brands ORDER BY name");
    }

    public function saveBrand(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Brand();
            } else {
                $data = Brand::find($request->id);
                $old  = $data->image;
            }

            $data->name     = $request->name;
            $data->description = $request->description;
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/brands");
            }
            $data->save();

            if (empty($request->id)) {
                return "Brand save successfully";
            } else {
                return "Brand updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteBrand($id)
    {
        $data = Brand::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Brand Delete Successfully";
    }
}
