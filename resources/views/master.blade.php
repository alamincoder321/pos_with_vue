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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <marquee>Design and Developed By Al Amin Islam</marquee>
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website {{date('Y')}}</div>
                            <div>
                                <a href="#">Privacy Policy</a>
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
            if (confirm("Are you sure")) {
                location.href = "{{route('logout')}}"
            }
        }
    </script>
</body>

</html>