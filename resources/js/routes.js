import unauthorize from "./components/unauthorize.vue";
import home from "./components/home.vue";
import user from "./components/user.vue";
import useraccess from "./components/useraccess.vue";
import company from "./components/company.vue";
import category from "./components/settings/category.vue";
import brand from "./components/settings/brand.vue";
import unit from "./components/settings/unit.vue";
import city from "./components/settings/city.vue";
import department from "./components/settings/department.vue";
import customer from "./components/administration/customer.vue";
import supplier from "./components/administration/supplier.vue";
import employer from "./components/administration/employer.vue";
import product from "./components/contents/product.vue";

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
    { path: "/departments", component: department },
    //administration
    { path: "/customers", component: customer },
    { path: "/suppliers", component: supplier },
    { path: "/employers", component: employer },
    //content
    {path: "/products", component: product},
    
];

export default routes;
