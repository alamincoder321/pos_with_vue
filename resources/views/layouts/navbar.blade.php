@php
$useraccess = App\Models\UserAccess::where("user_id", Auth::user()->id)->pluck("permission")->toArray();
@endphp
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{url('home')}}">Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        @if(in_array("sale.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-secondary btn-sm text-white shadow-none" style="margin-left: 10px !important;" to="/sales">POS SALE</router-link>
        @endif
        @if(in_array("purchase.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-secondary btn-sm text-white shadow-none" style="margin-left: 5px !important;" to="/purchases">PURCHASE</router-link>
        @endif
        @if(in_array("quotation.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-secondary btn-sm text-white shadow-none" style="margin-left: 5px !important;" to="/quotations">QUOTATION</router-link>
        @endif
        <!-- Navbar Search-->
        <form class=" d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div style="background: #ffbcbc;width: 150px;height: 56px;display: flex;align-items: center;justify-content: center;font-size: 20px;font-weight: 500;" class="text-success" id="clock"></div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><router-link class="dropdown-item" to="/user-edit"><i class="fas fa-cog"></i> Settings</<router-link></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a style="cursor: pointer;" class="dropdown-item" onclick="Logout()"><i class="fas fa-sign-out"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>