import unauthorize from "./components/unauthorize.vue";
import home from "./components/home.vue";
import user from "./components/user.vue";
import useraccess from "./components/useraccess.vue";
import company from "./components/company.vue";
import category from "./components/settings/category.vue";
import brand from "./components/settings/brand.vue";
import bank from "./components/account/bank.vue";
import unit from "./components/settings/unit.vue";
import city from "./components/settings/city.vue";
import customer from "./components/administration/customer.vue";
import supplier from "./components/administration/supplier.vue";
import department from "./components/hrpayroll/department.vue";
import employer from "./components/hrpayroll/employer.vue";
import product from "./components/settings/product.vue";
import purchase from "./components/purchase/purchase.vue";
import purchaselist from "./components/purchase/purchaselist.vue";
import editpurchase from "./components/purchase/editpurchase.vue";
import sale from "./components/sale/sale.vue";
import salelist from "./components/sale/salelist.vue";
import editsale from "./components/sale/editsale.vue";

const routes = [
    { path: "/unauthorize", component: unauthorize },
    { path: "/home", component: home },
    { path: "/users", component: user },
    { path: "/user-access/:id", component: useraccess },
    { path: "/company_profile", component: company },
    // settings
    { path: "/categories", component: category },
    { path: "/brands", component: brand },
    { path: "/units", component: unit },
    { path: "/cities", component: city },
    // bankaccount
    { path: "/bankaccounts", component: bank },
    //administration
    { path: "/customers", component: customer },
    { path: "/suppliers", component: supplier },
    //hrpayrol
    { path: "/departments", component: department },
    { path: "/employers", component: employer },
    //content
    { path: "/products", component: product },
    //purchase
    { path: "/purchases", component: purchase },
    { path: "/purchases-list", component: purchaselist },
    { path: "/purchases-edit/:id", component: editpurchase },
    // sales
    { path: "/sales", component: sale },
    { path: "/sales-list", component: salelist },
    { path: "/sales-edit/:id", component: editsale },
];

export default routes;
