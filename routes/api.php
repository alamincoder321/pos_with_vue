<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\TransactionController;
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
Route::get("/edit_user/{id}", [HomeController::class, "editUser"]);
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
//bankaccount
Route::get("/get_bankaccount", [BankAccountController::class, "getBankAccount"]);
Route::post("/save_bankaccount", [BankAccountController::class, "saveBankAccount"]);
Route::get("/delete_bankaccount/{id}", [BankAccountController::class, "deleteBankAccount"]);
Route::post("/getbank_balance", [BankAccountController::class, "bankBalance"]);
//customerpayment
Route::get("/get_customerpayment", [CustomerPaymentController::class, "getCustomerPayment"]);
Route::post("/save_customerpayment", [CustomerPaymentController::class, "saveCustomerPayment"]);
Route::get("/delete_customerpayment/{id}", [CustomerPaymentController::class, "deleteCustomerPayment"]);
//supplierpayment
Route::get("/get_supplierpayment", [SupplierPaymentController::class, "getSupplierPayment"]);
Route::post("/save_supplierpayment", [SupplierPaymentController::class, "saveSupplierPayment"]);
Route::get("/delete_supplierpayment/{id}", [SupplierPaymentController::class, "deleteSupplierPayment"]);
//transaction
Route::get("/get_transaction", [TransactionController::class, "getTransaction"]);
Route::post("/save_transaction", [TransactionController::class, "saveTransaction"]);
Route::get("/delete_transaction/{id}", [TransactionController::class, "deleteTransaction"]);
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
Route::post("/get_custduetotal", [CustomerController::class, "totalDue"]);
//supplier
Route::get("/get_supplier", [SupplierController::class, "getSupplier"]);
Route::post("/save_supplier", [SupplierController::class, "saveSupplier"]);
Route::get("/delete_supplier/{id}", [SupplierController::class, "deleteSupplier"]);
Route::post("/get_supduetotal", [SupplierController::class, "totalDue"]);
//employer
Route::get("/get_employer", [EmployerController::class, "getEmployer"]);
Route::post("/save_employer", [EmployerController::class, "saveEmployer"]);
Route::get("/delete_employer/{id}", [EmployerController::class, "deleteEmployer"]);
//product
Route::get("/get_product", [ProductController::class, "getProduct"]);
Route::post("/save_product", [ProductController::class, "saveProduct"]);
Route::get("/delete_product/{id}", [ProductController::class, "deleteProduct"]);
Route::post("/get_product_stock", [ProductController::class, "stock"]);
//damage
Route::get("/get_damage", [DamageController::class, "getDamage"]);
Route::post("/save_damage", [DamageController::class, "saveDamage"]);
Route::get("/delete_damage/{id}", [DamageController::class, "deleteDamage"]);
//purchase
Route::post("/get_purchase", [PurchaseController::class, "getPurchase"]);
Route::post("/save_purchase", [PurchaseController::class, "savePurchase"]);
Route::get("/delete_purchase/{id}", [PurchaseController::class, "deletePurchase"]);
//sales
Route::post("/get_sale", [SaleController::class, "getSale"]);
Route::post("/save_sale", [SaleController::class, "saveSale"]);
Route::get("/delete_sale/{id}", [SaleController::class, "deleteSale"]);
//quotations
Route::post("/get_quotation", [QuotationController::class, "getQuotation"]);
Route::post("/save_quotation", [QuotationController::class, "saveQuotation"]);
Route::get("/delete_quotation/{id}", [QuotationController::class, "deleteQuotation"]);

