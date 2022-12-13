<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Models\UserAccess;
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
        try {
            if (empty($request->id)) {
                $data = new User;
            } else {
                $data = User::find($request->id);
                $old  = $data->image;
            }

            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->role     = $request->role;
            if (!empty($request->password)) {
                $data->password = Hash::make($request->password);
            }
            if ($request->hasFile("image")) {
                if (isset($old)) {
                    if (File::exists($old)) {
                        File::delete($old);
                    }
                }
                $data->image = $this->imageUpload($request, "image", "uploads/users");
            }
            $data->save();

            if (empty($request->id)) {
                return "User save successfully";
            } else {
                return "User updated successfully";
            }
        } catch (\Throwable $e) {
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
        $data->delete();
        return "User Delete Successfully";
    }

    // useraccess

    public function getUserAccess()
    {
        $all_permission = DB::select("SELECT p.id, p.group_name, p.permission FROM permissions AS p");
        $group_name = DB::select("SELECT p.id, p.group_name, p.permission FROM permissions AS p GROUP BY group_name ORDER BY id");
        return response()->json(["permission"=>$all_permission, "group"=>$group_name]);
    }

    public function savePermission(Request $request)
    {
        try {
            UserAccess::where('user_id', $request->user_id)->delete();
            $perm = Permission::all();           
            foreach ($perm as $key => $value) {
                if (in_array($value->id, $request->permission)) {
                    UserAccess::create([
                        'user_id'     => $request->user_id,
                        'group_name'  => $value->group_name,
                        'permission' => $value->permission,
                    ]);
                }
            }
            return "Successfully add permission";
        } catch (\Throwable $e) {
            return $e->getMessage();
            return "Opps! something went wrong";;
        }

    }

    public function getPermission($id)
    {
        return UserAccess::where('user_id', $id)->pluck('permission')->toArray();
    }

    // edit user
    public function editUser($id)
    {
        return DB::select("SELECT * FROM users WHERE id='$id' ORDER BY name");
    }
}
