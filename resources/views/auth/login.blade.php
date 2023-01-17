<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <style>
        .register {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            /* margin-top: 3%; */
            /* padding: 3%;  */
            width: 100%;
            height: 100vh;
        }

        .register-left {
            text-align: center;
            color: #fff;
            margin-top: 4%;
        }

        .register-left input {
            border: none;
            border-radius: 1.5rem;
            padding: 2%;
            width: 60%;
            background: #f8f9fa;
            font-weight: bold;
            color: #383d41;
            margin-top: 30%;
            margin-bottom: 3%;
            cursor: pointer;
        }

        .register-right {
            background: #f8f9fa;
            border-top-left-radius: 10% 50%;
            border-bottom-left-radius: 10% 50%;
            margin-top: 20px;
        }

        .register-left img {
            margin-top: 15%;
            margin-bottom: 5%;
            width: 25%;
            -webkit-animation: mover 2s infinite alternate;
            animation: mover 1s infinite alternate;
        }

        @media screen and (min-device-width: 360px) and (max-device-width: 768px) {
            .register-right{
                margin-top: 0;
            }
        }

        @-webkit-keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        @keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        .register-left p {
            font-weight: lighter;
            padding: 12%;
            margin-top: -9%;
        }

        .register .register-form {
            padding: 10%;
            margin-top: 10%;
        }

        .btnRegister {
            float: right;
            margin-top: 10%;
            border: none;
            border-radius: 1.5rem;
            padding: 2%;
            background: #0062cc;
            color: #fff;
            font-weight: 600;
            width: 50%;
            cursor: pointer;
        }

        .register .nav-tabs {
            margin-top: 3%;
            border: none;
            background: #0062cc;
            border-radius: 1.5rem;
            width: 28%;
            float: right;
        }

        .register .nav-tabs .nav-link {
            padding: 2%;
            height: 34px;
            font-weight: 600;
            color: #fff;
            border-top-right-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }

        .register .nav-tabs .nav-link:hover {
            border: none;
        }

        .register .nav-tabs .nav-link.active {
            width: 100px;
            color: #0062cc;
            border: 2px solid #0062cc;
            border-top-left-radius: 1.5rem;
            border-bottom-left-radius: 1.5rem;
        }

        .register-heading {
            text-align: center;
            margin-top: 8%;
            margin-bottom: -15%;
            color: #495057;
        }
    </style>
</head>

<body>
    <div class="container-fluid register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="{{asset($profile->company_logo != null ? $profile->company_logo: 'no-image.jpg')}}" alt="{{$profile->name}}" style="width: 150px;" />
                <h3>Welcome</h3>
                <p>{{$profile->name}}</p>
            </div>
            <div class="col-md-9 register-right">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">{{$profile->name}}</h3>
                        <form onsubmit="AdminLogin(event)">
                            <div class="row register-form">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="username" id="username" class="form-control shadow-none" placeholder="Username or Email Address" autocomplete="off" />
                                        <span class="text-danger error error-username"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control shadow-none" placeholder="Enter your Password" autocomplete="off" />
                                        <span class="text-danger error error-password"></span>
                                    </div>
                                    <input type="submit" class="btnRegister" value="Login" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-md-block d-none" style="width:100%;position: absolute;bottom:25px;left:0;">
                <marquee onmouseover="this.stop()" onmouseout="this.start()" behavior="scroll" scrollamount="3" style="color: white;font-weight: 800;">Developed By Al Amin Islam</marquee>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function AdminLogin(event) {
            event.preventDefault();
            var formdata = new FormData(event.target)
            $.ajax({
                url: location.origin + "/login",
                method: "POST",
                data: formdata,
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $(".error").text("")
                },
                success: res => {
                    if (res.error) {
                        $.each(res.error, (index, value) => {
                            $(".error-" + index).text(value)
                        })
                    } else {
                        if (res.unauthenticate) {
                            $(".error-username").text(res.unauthenticate)
                            return
                        }
                        alert(res.msg)
                        location.href = "/home"
                        localStorage.setItem("user_id", res.user_id)
                    }
                }
            })
        }

        function passwordShow() {
            let eye = $("#Eye").attr("class")
            if (eye == "far fa-eye-slash") {
                $("#Eye").removeClass("far fa-eye-slash")
                $("#Eye").addClass("far fa-eye")
                $("input[name='password']").prop("type", "text")
            } else {
                $("#Eye").removeClass("far fa-eye")
                $("#Eye").addClass("far fa-eye-slash")
                $("input[name='password']").prop("type", "password")
            }
        }
    </script>
</body>

</html>