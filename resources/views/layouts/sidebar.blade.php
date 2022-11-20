<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <router-link class="nav-link" exact to="/home">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </router-link>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-connectdevelop"></i></div>
                    Contents
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <router-link class="nav-link" to="/categories">Category</router-link>
                        <router-link class="nav-link" to="/brands">Brand</router-link>
                        <router-link class="nav-link" to="/units">Unit</router-link>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts1">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-male"></i></div>
                    Administration
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <router-link class="nav-link" to="/customers">Customer</router-link>
                        <router-link class="nav-link" to="/suppliers">Supplier</router-link>
                        <router-link class="nav-link" to="/employers">Employer</router-link>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-cog"></i></div>
                    Settings
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <router-link class="nav-link" to="/cities">City</router-link>
                        <router-link class="nav-link" to="/departments">Department</router-link>
                    </nav>
                </div>
                <router-link class="nav-link" to="/users">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-users"></i></div>
                    Users
                </router-link>
                <router-link class="nav-link" to="/company_profile">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-university"></i></div>
                    Company Profile
                </router-link>
            </div>
        </div>
        <div class="sb-sidenav-footer text-center">
            <div class="small" style="background: rebeccapurple;padding: 5px 0;">Logged in as:</div>
            {{Auth::user()->name}}
        </div>
    </nav>
</div>