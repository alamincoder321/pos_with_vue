<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet" />
    <style>
        .gradient-custom-2 {
            /* fallback for old browsers */
            background: #fccb90;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
        }

        @media (min-width: 768px) {
            .gradient-form {
                height: 100vh !important;
            }
        }

        @media (min-width: 769px) {
            .gradient-custom-2 {
                border-top-left-radius: .3rem;
                border-bottom-left-radius: .3rem;
            }
        }

        .register-left img {
            margin-bottom: 5%;
            width: 25%;
            -webkit-animation: mover 2s infinite alternate;
            animation: mover 1s infinite alternate;
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
    </style>
</head>

<body style="position: relative;">
    <section class="h-100 gradient-form" style="background-color: #eee;">
        <div class="container pb-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10" title="প্রোপাইটরঃ {{$profile->owner_name}}">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                    <h4 class="mb-4 text-center" style="border: 2px dashed #ebb8b8;padding: 5px 15px;">{{$profile->name}}</h4>
                                    <p class="small mb-0">{{$profile->address}}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">

                                    <div class="text-center register-left">
                                        <img src="{{asset($profile->company_logo != null ? $profile->company_logo : 'no-image.jpg')}}" title="{{$profile->name}}" style="width: 150px;border-radius:20px;" alt="logo">
                                        <h4 class="mt-1 mb-5 pb-1"></h4>
                                    </div>

                                    <form onsubmit="AdminLogin(event)">
                                        <p>Please login to your account</p>
                                        <div class="form-outline mb-4">
                                            <input type="text" name="username" id="form2Example11" class="form-control" placeholder="Username or email address" autocomplete="off" />
                                            <label class="form-label error error-username" for="form2Example11">Username</label>
                                        </div>
                                        <div class="form-outline mb-4">
                                            <input type="password" name="password" id="form2Example22" class="form-control" autocomplete="off" />
                                            <label class="form-label error error-password" for="form2Example22">Password</label>
                                        </div>
                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-white d-flex py-1" style="background:linear-gradient(45deg, #ff4419, #4c1fff)">
                <marquee onmouseover="this.stop()" onmouseout="this.start()" behavior="scroll" scrollamount="3" style="color: white;font-weight: 800;">Design and Developed By Al Amin Islam</marquee>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"></script>

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
                    $(".error-username").text("Username").removeClass("text-danger")
                    $(".error-password").text("Password").removeClass("text-danger")
                },
                success: res => {
                    if (res.error) {
                        $.each(res.error, (index, value) => {
                            $(".error-" + index).text(value).addClass("text-danger")
                        })
                    } else {
                        if (res.unauthenticate) {
                            $(".error-username").text(res.unauthenticate).addClass("text-danger")
                            return
                        }
                        alert(res.msg)
                        location.href = "/home"
                        localStorage.setItem("user_id", res.user_id)
                    }
                }
            })
        }
    </script>
</body>

</html>