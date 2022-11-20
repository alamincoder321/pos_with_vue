<?php
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//login
Route::get("/", [LoginController::class, "showLoginForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::get("/logout", [LoginController::class, "logout"])->name("logout");

Route::get("/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/home", [HomeController::class, "index"])->middleware('auth');