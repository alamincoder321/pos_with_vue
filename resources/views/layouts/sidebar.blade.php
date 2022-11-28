@php
$useraccess = App\Models\UserAccess::where("user_id", Auth::user()->id)->pluck("permission")->toArray();
@endphp
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @if(in_array("dashboard", $useraccess))
                <router-link class="nav-link" exact to="/home">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </router-link>
                @endif
                <div class="sb-sidenav-menu-heading">Interface</div>
                @if(in_array("city.index", $useraccess) || in_array("category.index", $useraccess) || in_array("brand.index", $useraccess) || in_array("unit.index", $useraccess) || in_array("product.index", $useraccess))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-connectdevelop"></i></div>
                    Settings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("brand.index", $useraccess))
                        <router-link class="nav-link" to="/brands">Brand</router-link>
                        @endif
                        @if(in_array("category.index", $useraccess))
                        <router-link class="nav-link" to="/categories">Category</router-link>
                        @endif
                        @if(in_array("city.index", $useraccess))
                        <router-link class="nav-link" to="/cities">City</router-link>
                        @endif
                        @if(in_array("unit.index", $useraccess))
                        <router-link class="nav-link" to="/units">Unit</router-link>
                        @endif
                        @if(in_array("product.index", $useraccess))
                        <router-link class="nav-link" to="/products">Product</router-link>
                        @endif
                    </nav>
                </div>
                @endif

                @if(in_array("purchase.index", $useraccess))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts1">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-shopping-cart"></i></div>
                    Purchase
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("purchase.store", $useraccess))
                        <router-link class="nav-link" to="/purchases">Purchase Add</router-link>
                        @endif
                        @if(in_array("purchase.index", $useraccess))
                        <router-link class="nav-link" to="/purchases-list">Purchase List & Report</router-link>
                        @endif
                    </nav>
                </div>
                @endif

                @if(in_array("supplier.index", $useraccess) || in_array("customer.index", $useraccess))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-cogs"></i></div>
                    Administration
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("customer.index", $useraccess))
                        <router-link class="nav-link" to="/customers">Customer</router-link>
                        @endif
                        @if(in_array("supplier.index", $useraccess))
                        <router-link class="nav-link" to="/suppliers">Supplier</router-link>
                        @endif
                    </nav>
                </div>
                @endif
                @if(in_array("department.index", $useraccess) || in_array("employer.index", $useraccess))
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-users"></i></div>
                    HR & Payroll
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("department.index", $useraccess))
                        <router-link class="nav-link" to="/departments">Department</router-link>
                        @endif
                        @if(in_array("employer.index", $useraccess))
                        <router-link class="nav-link" to="/employers">Employer</router-link>
                        @endif
                    </nav>
                </div>
                @endif
                @if(in_array("user.index", $useraccess))
                <router-link class="nav-link" to="/users">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-user"></i></div>
                    Users
                </router-link>
                @endif
                @if(in_array("companyprofile.index", $useraccess))
                <router-link class="nav-link" to="/company_profile">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-university"></i></div>
                    Company Profile
                </router-link>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer text-center">
            <div class="small" style="background: rebeccapurple;padding: 5px 0;">Logged in as:</div>
            {{Auth::user()->name}}
        </div>
    </nav>
</div>