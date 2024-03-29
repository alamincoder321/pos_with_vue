@php
$useraccess = App\Models\UserAccess::where("user_id", Auth::user()->id)->pluck("permission")->toArray();
@endphp
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @if(in_array("dashboard", $useraccess))
                <router-link class="nav-link text-uppercase" exact to="/home">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-speedometer"></i></div>
                    Dashboard
                </router-link>
                @endif
                @if(in_array("purchase.index", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-cart-plus"></i></div>
                    Purchase
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("purchase.store", $useraccess))
                        <router-link class="nav-link" to="/purchases">Purchase Entry</router-link>
                        @endif
                        @if(in_array("damage.index", $useraccess))
                        <router-link class="nav-link" to="/damages">Damage Entry</router-link>
                        @endif
                        @if(in_array("purchase.index", $useraccess))
                        <router-link class="nav-link" to="/purchases-list">Purchase List & Report</router-link>
                        @endif                        
                        @if(in_array("purchase.index", $useraccess))
                        <router-link class="nav-link" to="/purchase_return">Purchase Return</router-link>
                        @endif                        
                    </nav>
                </div>
                @endif

                @if(in_array("sale.index", $useraccess) || in_array("sale.store", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts1">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-cart-dash"></i></div>
                    Sale
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("sale.store", $useraccess))
                        <router-link class="nav-link" to="/sales">Sale Entry</router-link>
                        @endif
                        @if(in_array("sale.index", $useraccess))
                        <router-link class="nav-link" to="/sales-list">Sale List & Report</router-link>
                        @endif
                    </nav>
                </div>
                @endif

                @if(in_array("quotation.index", $useraccess) || in_array("quotation.store", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-journal-check"></i></div>
                    Quotation
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("quotation.store", $useraccess))
                        <router-link class="nav-link" to="/quotations">Quotation Entry</router-link>
                        @endif
                        @if(in_array("quotation.index", $useraccess))
                        <router-link class="nav-link" to="/quotations-list">Quotation List & Report</router-link>
                        @endif
                    </nav>
                </div>
                @endif

                @if(in_array("city.index", $useraccess) || in_array("category.index", $useraccess) || in_array("brand.index", $useraccess) || in_array("unit.index", $useraccess) || in_array("product.index", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-gear"></i></div>
                    Settings
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
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

                @if(in_array("supplier.index", $useraccess) || in_array("customer.index", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts4">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-person-gear"></i></div>
                    Administration
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
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
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts5" aria-expanded="false" aria-controls="collapseLayouts5">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-people"></i></div>
                    HR & Payroll
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts5" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("department.index", $useraccess))
                        <router-link class="nav-link" to="/departments">Department</router-link>
                        @endif
                        @if(in_array("employer.index", $useraccess))
                        <router-link class="nav-link" to="/employers">Employer</router-link>
                        @endif
                        <router-link class="nav-link" to="/salary-generate">Salary Generate</router-link>
                        <router-link class="nav-link" to="/salary-report">Salary Report</router-link>
                    </nav>
                </div>
                @endif
                @if(in_array("transaction.index", $useraccess) || in_array("bankaccount.index", $useraccess) || in_array("customerpayment.index", $useraccess) || in_array("supplierpayment.index", $useraccess))
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts6" aria-expanded="false" aria-controls="collapseLayouts6">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-cash"></i></div>
                    Accounts Module
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts6" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(in_array("bankaccount.index", $useraccess))
                        <router-link class="nav-link" to="/bankaccounts">Bank Account</router-link>
                        @endif
                        @if(in_array("transaction.index", $useraccess))
                        <router-link class="nav-link" to="/transactions">Transactions</router-link>
                        @endif
                        @if(in_array("customerpayment.index", $useraccess))
                        <router-link class="nav-link" to="/customerpayments">CustomerPayment</router-link>
                        @endif
                        @if(in_array("supplierpayment.index", $useraccess))
                        <router-link class="nav-link" to="/supplierpayments">SupplierPayment</router-link>
                        @endif
                        <router-link class="nav-link" to="/expenses">Expense</router-link>                        
                    </nav>
                </div>
                @endif
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts7" aria-expanded="false" aria-controls="collapseLayouts7">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-file-earmark-text"></i></div>
                    Report
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts7" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <router-link class="nav-link" to="/stocks">Stock</router-link>
                    </nav>
                </div>
                <a class="nav-link collapsed text-uppercase" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts8" aria-expanded="false" aria-controls="collapseLayouts8">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-file-earmark-ruled"></i></div>
                    All Ledger
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts8" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <router-link class="nav-link" to="/product-ledger">Product Ledger</router-link>
                        <router-link class="nav-link" to="/customer-ledger">Customer Ledger</router-link>
                        <router-link class="nav-link" to="/supplier-ledger">Supplier Ledger</router-link>
                        <router-link class="nav-link" to="/bank-ledger">Bank Ledger</router-link>
                    </nav>
                </div>
                @if(in_array("user.index", $useraccess))
                <router-link class="nav-link text-uppercase" to="/users">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-person-circle"></i></div>
                    Users
                </router-link>
                @endif
                @if(in_array("companyprofile.index", $useraccess))
                <router-link class="nav-link text-uppercase" to="/company_profile">
                    <div class="sb-nav-link-icon text-white"><i class="bi bi-bank2"></i></div>
                    Company Profile
                </router-link>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer text-center text-uppercase">
            <div class="small" style="background: #0d6efd;padding: 5px 0;">Logged in as:</div>
            {{Auth::user()->role}}
        </div>
    </nav>
</div>