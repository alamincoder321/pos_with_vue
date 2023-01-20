<?php

namespace App\Http\Controllers;

use App\Models\ModelTable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function getProduct()
    {
        $gen_code = $this->generateCode('Product', 'P');
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

    //product stock
    public function stock(Request $request)
    {
        return ModelTable::Stock($request->id);
    }

    // product ledger
    public function ProductLedger(Request $request)
    {
        $result = DB::select("SELECT
                'a' AS sequence,
                pm.date AS date,
                concat('Purchase Invoice: ',pm.invoice) AS description,
                pd.quantity AS in_quantity,
                pd.total_amount AS in_stockvalue,
                0 AS out_quantity,
                0.00 as out_stockvalue
                FROM purchase_details pd
                LEFT JOIN purchases pm ON pm.id = pd.purchase_id
                WHERE pd.product_id = '$request->id'
            UNION
                SELECT
                'b' AS sequence,
                sm.date AS date,
                concat('Sale Invoice: ',sm.invoice) AS description,
                0 AS in_quantity,
                0.00 as in_stockvalue,
                sd.quantity AS out_quantity,
                sd.total_amount AS out_stockvalue
                FROM sale_details sd
                LEFT JOIN sales sm ON sm.id = sd.sale_id
                WHERE sd.product_id = '$request->id'
                
                ORDER BY date, sequence ASC");

        $ledger = array_map(function ($key, $row) use ($result) {
            $row->stock = $key == 0 ? $row->in_quantity - $row->out_quantity : ($result[$key - 1]->stock + ($row->in_quantity - $row->out_quantity));
            return $row;
        }, array_keys($result), $result);

        $previousRows = array_filter($ledger, function ($row) use ($request) {
            return $row->date < $request->dateFrom;
        });

        $previousStock = empty($previousRows) ? 0 : end($previousRows)->stock;

        return ['ledger'=> $ledger, 'previousStock' => $previousStock];
    }
}
