<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function getCity()
    {
        return DB::select("SELECT * FROM cities ORDER BY name");
    }

    public function saveCity(Request $request)
    {
        try {
            if (empty($request->id)) {
                $data = new City();
            } else {
                $data = City::find($request->id);
            }

            $data->name     = $request->name;
            $data->save();

            if (empty($request->id)) {
                return "City save successfully";
            } else {
                return "City updated successfully";
            }
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    public function deleteCity($id)
    {
        $data = City::find($id);
        $data->delete();
        return "City Delete Successfully";
    }
}
