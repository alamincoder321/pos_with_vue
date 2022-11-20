<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function getCategory()
    {
        return DB::select("SELECT * FROM categories ORDER BY name");
    }

    public function saveCategory(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Category();
            } else {
                $data = Category::find($request->id);
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
                $data->image = $this->imageUpload($request, "image", "uploads/categories");
            }
            $data->save();

            if (empty($request->id)) {
                return "Category save successfully";
            } else {
                return "Category updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteCategory($id)
    {
        $data = Category::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Category Delete Successfully";
    }
}
