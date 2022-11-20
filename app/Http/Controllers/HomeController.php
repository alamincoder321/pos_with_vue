<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        return view("master");
    }

    public function getUser()
    {
        return DB::select("SELECT * FROM users WHERE id!=1 ORDER BY name");
    }

    public function saveUser(Request $request)
    {
        try{
            if(empty($request->id)){
                $data = new User;
            }else{
                $data = User::find($request->id);
                $old  = $data->image;
            }

            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            if (!empty($request->password)) {
                $data->password = Hash::make($request->password);
            }
            if($request->hasFile("image")){
                if(isset($old)){
                    if(File::exists($old)){
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/users");
            }
            $data->save();

            if(empty($request->id)){
                return "User save successfully";
            }else{
                return "User updated successfully";
            }
        }catch(\Throwable $e){
            return "Opps! something went wrong";
        }
    }

    public function deleteUser($id)
    {
        $data = User::find($id);
        $old = $data->image;
        if (File::exists($old)) {
            File::delete($old);
        }
        return "User Delete Successfully";
    }
}
