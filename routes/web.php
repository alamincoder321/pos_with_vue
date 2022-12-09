<?php
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

//login
Route::get("/", [LoginController::class, "showLoginForm"]);
Route::post("/login", [LoginController::class, "login"]);
Route::get("/logout", [LoginController::class, "logout"])->name("logout");

Route::get("/user-access/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/invoice/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/purchase-invoice/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/purchases-edit/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/sales-edit/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/{any}", [HomeController::class, "index"])->middleware('auth');
Route::get("/home", [HomeController::class, "index"])->middleware('auth');