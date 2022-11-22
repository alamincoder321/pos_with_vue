<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user
Route::get("/get_user", [HomeController::class, "getUser"]);
Route::post("/save_user", [HomeController::class, "saveUser"]);
Route::get("/delete_user/{id}", [HomeController::class, "deleteUser"]);
//user access
Route::get("/get_useraccess", [HomeController::class, "getUserAccess"]);
Route::get("/get_permission/{id}", [HomeController::class, "getPermission"]);
Route::post("/save_permission", [HomeController::class, "savePermission"]);

//company_profile
Route::get("/get_company_profile", [CompanyController::class, "getCompany"]);
Route::post("/save_company", [CompanyController::class, "saveCompany"]);

//category
Route::get("/get_category", [CategoryController::class, "getCategory"]);
Route::post("/save_category", [CategoryController::class, "saveCategory"]);
Route::get("/delete_category/{id}", [CategoryController::class, "deleteCategory"]);
//brand
Route::get("/get_brand", [BrandController::class, "getBrand"]);
Route::post("/save_brand", [BrandController::class, "saveBrand"]);
Route::get("/delete_brand/{id}", [BrandController::class, "deleteBrand"]);
//unit
Route::get("/get_unit", [UnitController::class, "getUnit"]);
Route::post("/save_unit", [UnitController::class, "saveUnit"]);
Route::get("/delete_unit/{id}", [UnitController::class, "deleteUnit"]);
//department
Route::get("/get_department", [DepartmentController::class, "getDepartment"]);
Route::post("/save_department", [DepartmentController::class, "saveDepartment"]);
Route::get("/delete_department/{id}", [DepartmentController::class, "deleteDepartment"]);
//city
Route::get("/get_city", [CityController::class, "getCity"]);
Route::post("/save_city", [CityController::class, "saveCity"]);
Route::get("/delete_city/{id}", [CityController::class, "deleteCity"]);

//customer
Route::get("/get_customer", [CustomerController::class, "getCustomer"]);
Route::post("/save_customer", [CustomerController::class, "saveCustomer"]);
Route::get("/delete_customer/{id}", [CustomerController::class, "deleteCustomer"]);
//supplier
Route::get("/get_supplier", [SupplierController::class, "getSupplier"]);
Route::post("/save_supplier", [SupplierController::class, "saveSupplier"]);
Route::get("/delete_supplier/{id}", [SupplierController::class, "deleteSupplier"]);
//employer
Route::get("/get_employer", [EmployerController::class, "getEmployer"]);
Route::post("/save_employer", [EmployerController::class, "saveEmployer"]);
Route::get("/delete_employer/{id}", [EmployerController::class, "deleteEmployer"]);
//product
Route::get("/get_product", [ProductController::class, "getProduct"]);
Route::post("/save_product", [ProductController::class, "saveProduct"]);
Route::get("/delete_product/{id}", [ProductController::class, "deleteProduct"]);

