<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="{{asset('backend/css/styles.css')}}" rel="stylesheet" />

    <style>
        * {
            font-family: monospace !important;
        }

        .router-link-exact-active {
            background: #eb971a !important;
            color: white !important;
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
                <footer class="py-2 bg-dark mt-auto">
                    <div class="container-fluid px-4" style="padding-left: 0 !important;">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div style="width: 32%;background: #646464;padding: 3px 7px;" class="text-white">Design and Developed By <span class="text-warning text-uppercase">Md. Al Amin Islam</span></div>
                            <div style="width: 68%;">
                                <marquee onmouseover="this.stop()" onmouseout="this.start()" behavior="scroll" scrollamount="3" style="color: #10ef17;font-weight: 800;">Welcome To {{$profile->name}}</marquee>
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