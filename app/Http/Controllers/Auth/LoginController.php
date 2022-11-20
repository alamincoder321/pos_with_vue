<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function showLoginForm()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "username" => "required",
                "password" => "required"
            ]);
            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()]);
            }

            if (Auth::attempt(["username" => $request->username, "password" => $request->password])) {
                return response()->json(["msg"=>"Login successfully", "user_id"=>Auth::user()->id]);
            } else {
                return response()->json(["unauthenticate" => "Invalid authentication"]);
            }
        } catch (\Throwable $e) {
            return "Opps! Something went wrong";
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/");
    }
}
