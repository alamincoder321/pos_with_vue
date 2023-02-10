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
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfitController;
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
Route::get("/get-user", [HomeController::class, "getUser"]);
Route::post("/save-user", [HomeController::class, "saveUser"]);
Route::get("/delete-user/{id}", [HomeController::class, "deleteUser"]);
Route::get("/edit-user/{id}", [HomeController::class, "editUser"]);
//user access
Route::get("/get-useraccess", [HomeController::class, "getUserAccess"]);
Route::get("/get-permission/{id}", [HomeController::class, "getPermission"]);
Route::post("/save-permission", [HomeController::class, "savePermission"]);

//company_profile
Route::get("/get-company-profile", [CompanyController::class, "getCompany"]);
Route::post("/save-company", [CompanyController::class, "saveCompany"]);

//category
Route::get("/get-category", [CategoryController::class, "getCategory"]);
Route::post("/save-category", [CategoryController::class, "saveCategory"]);
Route::get("/delete-category/{id}", [CategoryController::class, "deleteCategory"]);
//brand
Route::get("/get-brand", [BrandController::class, "getBrand"]);
Route::post("/save-brand", [BrandController::class, "saveBrand"]);
Route::get("/delete-brand/{id}", [BrandController::class, "deleteBrand"]);
//bankaccount
Route::get("/get-bankaccount", [BankAccountController::class, "getBankAccount"]);
Route::post("/save-bankaccount", [BankAccountController::class, "saveBankAccount"]);
Route::get("/delete-bankaccount/{id}", [BankAccountController::class, "deleteBankAccount"]);
Route::post("/getbank-balance", [BankAccountController::class, "bankBalance"]);
//customerpayment
Route::get("/get-customerpayment", [CustomerPaymentController::class, "getCustomerPayment"]);
Route::post("/save-customerpayment", [CustomerPaymentController::class, "saveCustomerPayment"]);
Route::get("/delete-customerpayment/{id}", [CustomerPaymentController::class, "deleteCustomerPayment"]);
//supplierpayment
Route::get("/get-supplierpayment", [SupplierPaymentController::class, "getSupplierPayment"]);
Route::post("/save-supplierpayment", [SupplierPaymentController::class, "saveSupplierPayment"]);
Route::get("/delete-supplierpayment/{id}", [SupplierPaymentController::class, "deleteSupplierPayment"]);
//transaction
Route::get("/get-transaction", [TransactionController::class, "getTransaction"]);
Route::post("/save-transaction", [TransactionController::class, "saveTransaction"]);
Route::get("/delete-transaction/{id}", [TransactionController::class, "deleteTransaction"]);
//expense
Route::get("/get-expense", [ExpenseController::class, "getExpense"]);
Route::post("/save-expense", [ExpenseController::class, "saveExpense"]);
Route::get("/delete-expense/{id}", [ExpenseController::class, "deleteExpense"]);
//unit
Route::get("/get-unit", [UnitController::class, "getUnit"]);
Route::post("/save-unit", [UnitController::class, "saveUnit"]);
Route::get("/delete-unit/{id}", [UnitController::class, "deleteUnit"]);
//department
Route::get("/get-department", [DepartmentController::class, "getDepartment"]);
Route::post("/save-department", [DepartmentController::class, "saveDepartment"]);
Route::get("/delete-department/{id}", [DepartmentController::class, "deleteDepartment"]);
//city
Route::get("/get-city", [CityController::class, "getCity"]);
Route::post("/save-city", [CityController::class, "saveCity"]);
Route::get("/delete-city/{id}", [CityController::class, "deleteCity"]);

//customer
Route::get("/get-customer", [CustomerController::class, "getCustomer"]);
Route::post("/save-customer", [CustomerController::class, "saveCustomer"]);
Route::get("/delete-customer/{id}", [CustomerController::class, "deleteCustomer"]);
Route::post("/get-custduetotal", [CustomerController::class, "totalDue"]);
//supplier
Route::get("/get-supplier", [SupplierController::class, "getSupplier"]);
Route::post("/save-supplier", [SupplierController::class, "saveSupplier"]);
Route::get("/delete-supplier/{id}", [SupplierController::class, "deleteSupplier"]);
Route::post("/get-supduetotal", [SupplierController::class, "totalDue"]);
//employer
Route::get("/get-employer", [EmployerController::class, "getEmployer"]);
Route::post("/save-employer", [EmployerController::class, "saveEmployer"]);
Route::get("/delete-employer/{id}", [EmployerController::class, "deleteEmployer"]);
Route::post("/salary-generate", [EmployerController::class, "salaryGenerate"]);
Route::post("/salary-payment", [EmployerController::class, "salaryPayment"]);
Route::post("/salary-report", [EmployerController::class, "salaryReport"]);
//product
Route::get("/get-product", [ProductController::class, "getProduct"]);
Route::get("/product-fetch/{id}", [ProductController::class, "fetch"]);
Route::post("/save-product", [ProductController::class, "saveProduct"]);
Route::get("/delete-product/{id}", [ProductController::class, "deleteProduct"]);
Route::post("/get-product-stock", [ProductController::class, "stock"]);
//damage
Route::get("/get-damage", [DamageController::class, "getDamage"]);
Route::post("/save-damage", [DamageController::class, "saveDamage"]);
Route::get("/delete-damage/{id}", [DamageController::class, "deleteDamage"]);
//purchase
Route::post("/get-purchase", [PurchaseController::class, "getPurchase"]);
Route::post("/save-purchase", [PurchaseController::class, "savePurchase"]);
Route::get("/delete-purchase/{id}", [PurchaseController::class, "deletePurchase"]);
//sales
Route::post("/get-sale", [SaleController::class, "getSale"]);
Route::post("/save-sale", [SaleController::class, "saveSale"]);
Route::get("/delete-sale/{id}", [SaleController::class, "deleteSale"]);
//quotations
Route::post("/get-quotation", [QuotationController::class, "getQuotation"]);
Route::post("/save-quotation", [QuotationController::class, "saveQuotation"]);
Route::get("/delete-quotation/{id}", [QuotationController::class, "deleteQuotation"]);

//all ledger
Route::post("/getproduct-ledger", [ProductController::class, "ProductLedger"]);
Route::post("/getcustomer-ledger", [CustomerController::class, "CustomerLedger"]);
Route::post("/getsupplier-ledger", [SupplierController::class, "SupplierLedger"]);
Route::post("/getbank-ledger", [BankAccountController::class, "BankLedger"]);

//get profit
Route::get("/profit", [ProfitController::class, "getProfit"]);

