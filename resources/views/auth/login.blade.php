<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kaushan+Script&family=Poppins&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #eee;
            height: 90vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to top, #fff 10%, rgba(93, 42, 141, 0.4) 90%) no-repeat;
        }

        .wrapper {
            max-width: 500px;
            border-radius: 10px;
            margin: 50px auto;
            padding: 30px 40px;
            box-shadow: 20px 20px 80px rgb(206, 206, 206);
        }

        .h2 {
            font-family: 'Kaushan Script', cursive;
            font-size: 3.5rem;
            font-weight: bold;
            color: #400485;
            font-style: italic;
        }

        .h4 {
            font-family: 'Poppins', sans-serif;
        }

        .input-field {
            border-radius: 5px;
            padding: 5px;
            display: flex;
            align-items: center;
            cursor: pointer;
            border: 1px solid #400485;
            color: #400485;
        }

        .input-field:hover {
            color: #7b4ca0;
            border: 1px solid #7b4ca0;
        }

        input {
            border: none;
            outline: none;
            box-shadow: none;
            width: 100%;
            padding: 0px 2px;
            font-family: 'Poppins', sans-serif;
        }

        .fa-eye-slash.btn {
            border: none;
            outline: none;
            box-shadow: none;
        }

        a {
            text-decoration: none;
            color: #400485;
            font-weight: 700;
        }

        a:hover {
            text-decoration: none;
            color: #7b4ca0;
        }

        .option {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
        }

        .option label.text-muted {
            display: block;
            cursor: pointer;
        }

        .option input {
            display: none;
        }

        .checkmark {
            position: absolute;
            top: 0px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
            cursor: pointer;
        }

        .option input:checked~.checkmark:after {
            display: block;
        }

        .option .checkmark:after {
            content: "";
            width: 13px;
            height: 13px;
            display: block;
            background: #400485;
            position: absolute;
            top: 48%;
            left: 53%;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: 300ms ease-in-out 0s;
        }

        .option input[type="radio"]:checked~.checkmark {
            background: #fff;
            transition: 300ms ease-in-out 0s;
            border: 1px solid #400485;
        }

        .option input[type="radio"]:checked~.checkmark:after {
            transform: translate(-50%, -50%) scale(1);
        }

        .btn.btn-block {
            border-radius: 20px;
            background-color: #400485;
            color: #fff;
        }

        .btn.btn-block:hover {
            background-color: #55268be0;
        }

        @media(max-width: 575px) {
            .wrapper {
                margin: 10px;
            }
        }

        @media(max-width:424px) {
            .wrapper {
                padding: 30px 10px;
                margin: 5px;
            }

            .option {
                position: relative;
                padding-left: 22px;
            }

            .option label.text-muted {
                font-size: 0.95rem;
            }

            .checkmark {
                position: absolute;
                top: 2px;
            }

            .option .checkmark:after {
                top: 50%;
            }

            #forgot {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <div id="app" class="wrapper bg-white">
        <div class="h2 text-center">
            @php
                $setting = App\Models\CompanyProfile::first();
            @endphp
            <img src="{{asset($setting->image != null ? $setting->image: 'no-image.jpg')}}" alt="{{$setting->company_name}}">
        </div>
        <form class="pt-3" onsubmit="AdminLogin(event)">
            <div class="form-group py-2">
                <div class="input-field">
                    <span class="far fa-user p-2"></span>
                    <input type="text" name="username" placeholder="Username or Email Address" autocomplete="off">
                </div>
                <span class="text-danger error error-username"></span>
            </div>
            <div class="form-group py-1 pb-2">
                <div class="input-field">
                    <span class="fas fa-lock p-2"></span>
                    <input type="password" name="password" placeholder="Enter your Password" autocomplete="off">
                    <button type="button" onclick="passwordShow()" class="btn bg-white text-muted" style="padding: 0 5px;">
                        <span id="Eye" class="far fa-eye-slash"></span>
                    </button>
                </div>
                <span class="text-danger error error-password"></span>
            </div>
            <div class="d-flex align-items-start pt-3">
                <div class="remember">
                    <label class="option text-muted"> Remember me
                        <input type="radio">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="ml-auto">
                    <a href="#" id="forgot">Forgot Password?</a>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-block text-center my-3">Log in</button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{asset('js/app.js')}}"></script>
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