<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{asset($profile->company_logo)}}" sizes="150x150">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="{{asset('backend/css/styles.css')}}" rel="stylesheet" />

    <style>
        * {
            font-family: monospace !important;
        }

        .router-link-exact-active {
            background: #6e2cd4 !important;
            color: white !important;
        }

        .btn-print {
            position: absolute;
            bottom: 6px;
            right: 13px;
            background: #e1347d;
            border-color: #e1347d;
            font-size: 13px;
            padding: 4px 12px;
        }

        .btn-invoice {
            background: #e1347d;
            border-color: #e1347d;
            font-size: 13px;
        }

        .ImageBackground .imageShow {
            display: block;
            height: 120px;
            width: 135px;
            margin-top: 10px;
            border: 1px solid #27ff00;
            border-bottom: 0;
            box-sizing: border-box;
        }

        .ImageBackground input {
            display: none;
        }

        .ImageBackground label {
            background: green;
            width: 135px;
            color: white;
            padding: 5px;
            text-align: center;
            cursor: pointer;
            font-family: monospace;
            text-transform: uppercase;
        }

        @media only screen and (max-width: 600px) and (min-width: 320px) {
            #clock {
                padding: 5px 15px !important;
                font-size: 10px !important;
            }

        }

        .AlAmin {
            /* padding: 0.39rem 27px; */
            color: white;
            animation: example;
            animation-duration: 4s;
            animation-iteration-count: infinite;
        }

        @keyframes example {
            0% {
                background: red;
            }

            25% {
                background: purple;
            }

            50% {
                background: blue;
            }

            100% {
                background: green;
            }
        }
    </style>

</head>

<body class="sb-nav-fixed">
    <section id="app">
        @include("layouts.navbar")
        <div id="layoutSidenav">
            @include("layouts.sidebar")
            <div id="layoutSidenav_content">
                <main>
                    <router-view></router-view>
                </main>
                <footer class="py-2 bg-dark mt-auto position-relative">
                    <div class="container-fluid p-lg-0 p-md-0">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div style="padding-left:5px;width: 31%;background: #50206d9e;height: 100%;position: absolute;left:0;z-index:999999;display: flex;align-items: center;" class="text-white AlAmin">Design and Developed By<span class="text-uppercase text-warning" style="margin-left: 25px;font-size: 14px;font-weight: 900;"> Md. Al Amin Islam </span></div>
                            <div style="width: 100%;display:flex;align-items:center;">
                                <marquee onmouseover="this.stop()" onmouseout="this.start()" behavior="scroll" scrollamount="3" style="color: rgb(40 255 185);font-weight: 800;font-size: 14px;text-transform:uppercase;">Welcome To {{$profile->name}}</marquee>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </section>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('backend/js/scripts.js')}}"></script>
    <script>
        function Logout() {
            location.href = "{{route('logout')}}"
        }

        // window.onload = displayClock();
        function displayClock() {
            var display = new Date().toLocaleTimeString();
            var dispalyDate = new Date().toDateString();
            document.getElementById("clock").innerText = dispalyDate + ', ' + display;
            setTimeout(displayClock, 1000);
        }
        displayClock();
    </script>
</body>

</html>