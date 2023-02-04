import unauthorize from "./components/unauthorize.vue";
import home from "./components/home.vue";
import user from "./components/user.vue";
import user_profile from "./components/user_profile.vue";
import useraccess from "./components/useraccess.vue";
import company from "./components/company.vue";
import category from "./components/settings/category.vue";
import brand from "./components/settings/brand.vue";
import bank from "./components/account/bank.vue";
import customerpayment from "./components/account/customerpayment.vue";
import supplierpayment from "./components/account/supplierpayment.vue";
import transaction from "./components/account/transaction.vue";
import expense from "./components/account/expense.vue";
import unit from "./components/settings/unit.vue";
import city from "./components/settings/city.vue";
import customer from "./components/administration/customer.vue";
import supplier from "./components/administration/supplier.vue";
import department from "./components/hrpayroll/department.vue";
import employer from "./components/hrpayroll/employer.vue";
import salaryGenerate from "./components/hrpayroll/salaryGenerate.vue";
import salaryReport from "./components/hrpayroll/salaryReport.vue";
import product from "./components/settings/product.vue";
import purchase from "./components/purchase/purchase.vue";
import purchaselist from "./components/purchase/purchaselist.vue";
import editpurchase from "./components/purchase/editpurchase.vue";
import purchaseReturn from "./components/purchase/purchaseReturn.vue";
import damage from "./components/purchase/damage.vue";
import sale from "./components/sale/sale.vue";
import salelist from "./components/sale/salelist.vue";
import editsale from "./components/sale/editsale.vue";
import quotation from "./components/quotation/quotation.vue";
import quotationlist from "./components/quotation/quotationlist.vue";
import editquotation from "./components/quotation/editquotation.vue";

import salesinvoice from "./components/invoice/salesinvoice.vue";
import purchaseinvoice from "./components/invoice/purchaseinvoice.vue";
import quotationinvoice from "./components/invoice/quotationinvoice.vue";

// report
import stock from "./components/report/stock.vue";

// ledger
import productLedger from "./components/ledger/productLedger.vue";
import customerLedger from "./components/ledger/customerLedger.vue";
import supplierLedger from "./components/ledger/supplierLedger.vue";
import bankLedger from "./components/ledger/bankLedger.vue";


const routes = [
    { path: "/unauthorize", component: unauthorize },
    { path: "/home", component: home },
    { path: "/users", component: user },
    { path: "/user-access/:id", component: useraccess },
    { path: "/user-edit", component: user_profile },
    { path: "/company_profile", component: company },
    // settings
    { path: "/categories", component: category },
    { path: "/brands", component: brand },
    { path: "/units", component: unit },
    { path: "/cities", component: city },
    // bankaccount
    { path: "/bankaccounts", component: bank },
    { path: "/customerpayments", component: customerpayment },
    { path: "/supplierpayments", component: supplierpayment },
    { path: "/transactions", component: transaction },
    { path: "/expenses", component: expense },
    //administration
    { path: "/customers", component: customer },
    { path: "/suppliers", component: supplier },
    //hrpayrol
    { path: "/departments", component: department },
    { path: "/employers", component: employer },
    { path: "/salary-generate", component: salaryGenerate },
    { path: "/salary-report", component: salaryReport },
    //content
    { path: "/products", component: product },
    //purchase
    { path: "/purchases", component: purchase },
    { path: "/purchases-list", component: purchaselist },
    { path: "/purchases-edit/:id", component: editpurchase },
    { path: "/damages", component: damage },
    { path: "/purchase_return", component: purchaseReturn },
    // sales
    { path: "/sales", component: sale },
    { path: "/sales-list", component: salelist },
    { path: "/sales-edit/:id", component: editsale },
    // quotations
    { path: "/quotations", component: quotation },
    { path: "/quotations-list", component: quotationlist },
    { path: "/quotations-edit/:id", component: editquotation },
    //invoice
    { path: "/invoice/:id", component: salesinvoice },
    { path: "/purchase-invoice/:id", component: purchaseinvoice },
    { path: "/quotation-invoice/:id", component: quotationinvoice },
    //report
    { path: "/stocks", component: stock },
    //ledger
    { path: "/product-ledger", component: productLedger },
    { path: "/customer-ledger", component: customerLedger },
    { path: "/supplier-ledger", component: supplierLedger },
    { path: "/bank-ledger", component: bankLedger },


];

export default routes;
