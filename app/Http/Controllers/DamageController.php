<?php

namespace App\Http\Controllers;

use App\Models\Damage;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function getDamage()
    {
        $gen_code = $this->generateCode('Damage', 'D-');
        $damages = DB::select("SELECT d.*, CONCAT(p.product_code, ' - ', p.name) AS display_name, p.name FROM damages AS d LEFT JOIN products AS p ON p.id=d.product_id ORDER BY name");
        return response()->json(["gen_code" => $gen_code, "damages" => $damages]);
    }

    public function saveDamage(Request $request)
    {
        try {
            DB::beginTransaction();
            if (empty($request->id)) {
                $data = new Damage();
            } else {
                $data                      = Damage::find($request->id);
                $old_inventory             = ProductInventory::where("product_id", $data->product_id)->first();
                $old_inventory->damage_qty = $old_inventory->damage_qty-$data->quantity;
                $old_inventory->save();
            }

            $data->damage_code  = $request->damage_code;
            $data->date         = $request->date;
            $data->product_id   = $request->product_id;
            $data->quantity     = $request->quantity;
            $data->price        = $request->price;
            $data->total_amount = $request->total_amount;
            $data->description  = $request->description;
            $data->save();

            //update damage_qty
            $damage_inventory = ProductInventory::where("product_id", $request->product_id)->first();
            $damage_inventory->damage_qty = $damage_inventory->damage_qty+$request->quantity;
            $damage_inventory->save();

            DB::commit();
            if (empty($request->id)) {
                return "Damage save successfully";
            } else {
                return "Damage updated successfully";
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
            DB::rollBack();
            return "Opps! something went wrong";
        }
    }

    public function deleteDamage($id)
    {
        $data = Damage::find($id);
        $data->delete();
        return "Product Delete Successfully";
    }
}
