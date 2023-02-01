@php
$useraccess = App\Models\UserAccess::where("user_id", Auth::user()->id)->pluck("permission")->toArray();
@endphp
<nav class="sb-topnav navbar navbar-expand" style="background: linear-gradient(45deg, #4c1fff, #943aa3);">
<!-- background:repeating-linear-gradient(45deg, black, #9e7effeb 100px) -->
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 text-white text-uppercase" style="font-size: 20px;" href="{{url('home')}}">Inventory System</a>
        <!-- Sidebar Toggle-->
        <button style="font-size: 16px;" class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 shadow-none text-white"  id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        @if(in_array("sale.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-sm text-white shadow-none border-0" style="margin-left: 10px !important;background: linear-gradient(to right, #f5771f, #dd3675, #ff25be);font-size: 13px;font-weight: 700;" to="/sales">POS SALE</router-link>
        @endif
        @if(in_array("purchase.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-sm text-white shadow-none border-0" style="margin-left: 5px !important;background: linear-gradient(to right, #f5771f, #dd3675, #ff25be);font-size: 13px;font-weight: 700;" to="/purchases">PURCHASE</router-link>
        @endif
        @if(in_array("quotation.store", $useraccess))
        <router-link class="d-none d-md-block text-decoration-none btn btn-sm text-white shadow-none border-0" style="margin-left: 5px !important;background: linear-gradient(to right, #f5771f, #dd3675, #ff25be);font-size: 13px;font-weight: 700;" to="/quotations">QUOTATION</router-link>
        @endif
        <!-- Navbar Search-->
        <form class=" d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <!-- linear-gradient(45deg, #0fd3d3, #e14747); -->
            <div style="margin: 0px;background: #1007076e;height: 100%;display: flex;align-items: center;padding: 15px;color: white !important;font-weight: 800;padding:15px 25px;font-size: 17px;" class="text-success" id="clock"></div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link text-danger" title="{{Auth::user()->name}}" id="navbarDropdown" style="background: linen;border-radius: 50%;margin: 0px;padding: 0px;border: 5px dashed #2e240c;" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><img src="{{asset(Auth::user()->image != null ? Auth::user()->image:'no-image.jpg')}}" style="width: 40px;height: 40px;border-radius: 50%;" alt=""></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><router-link class="dropdown-item" to="/user-edit"><i class="bi bi-gear"></i> Settings </router-link></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a style="cursor: pointer;" class="dropdown-item" onclick="Logout()"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>