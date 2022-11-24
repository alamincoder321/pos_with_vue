<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function getProduct()
    {
        $gen_code = $this->generateCode('Product', 'P-');
        $products = DB::select("SELECT p.*, CONCAT(p.product_code, ' - ', p.name) AS display_name, ifnull(b.name, 0) AS brand_name, ifnull(c.name, 0) AS category_name, ifnull(u.name, 0) AS unit_name FROM products AS p LEFT JOIN brands AS b ON b.id=p.brand_id LEFT JOIN categories AS c ON c.id=p.category_id LEFT JOIN units AS u ON u.id=p.unit_id ORDER BY name");
        return response()->json(["gen_code" => $gen_code, "products" => $products]);
    }

    public function saveProduct(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new Product();
            } else {
                $data = Product::find($request->id);
                $old  = $data->image;
            }

            $data->product_code   = $request->product_code;
            $data->name           = $request->name;
            $data->brand_id       = $request->brand_id;
            $data->category_id    = $request->category_id;
            $data->unit_id        = $request->unit_id;
            $data->re_order       = $request->re_order;
            $data->purchase_price = $request->purchase_price;
            $data->selling_price  = $request->selling_price;
            $data->description    = $request->description;
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/products");
            }
            $data->save();

            if (empty($request->id)) {
                return "Product save successfully";
            } else {
                return "Product updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteProduct($id)
    {
        $data = Product::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        $data->delete();
        return "Product Delete Successfully";
    }
}
