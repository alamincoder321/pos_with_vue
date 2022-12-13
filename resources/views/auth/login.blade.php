<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <style>
        .fa-user {
            font-size: 20px !important;
            padding: 0 13px !important;
            display: flex !important;
            align-items: center !important;
        }

        .fa-unlock-alt {
            font-size: 20px !important;
            display: flex !important;
            padding: 0 13px !important;
            align-items: center !important;
        }
    </style>
</head>

<body style="background: linear-gradient(45deg, #54a7d7, #ab0f0ff7) !important;height: 100px;">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-10 col-lg-4" style="position: absolute;top: 35%;left: 50%;transform: translate(-50%, -50%);">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><i>Welcome Login Page</i></h4>
                    </div>
                    <div class="card-body">
                        <form onsubmit="Login(event)">
                            <div class="form-group pt-3 pb-1">
                                <div class="input-group">
                                    <input type="text" name="username" class="form-control shadow-none" placeholder="Username" autocomplete="off" autofocus /><i class="btn btn-secondary fa fa-user"></i>
                                </div>
                                <span class="text-danger error username"></span>
                            </div>
                            <div class="form-group pb-3 pt-1">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control shadow-none" placeholder="Password" /><i class="btn btn-secondary fa fa-unlock-alt"></i>
                                </div>
                                <span class="text-danger error password"></span>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-dark text-uppercase shadow-none"><i class="fa fa-sign-in"></i> Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function Login(event) {
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
                            $("." + index).text(value)
                        })
                    } else {
                        if (res.unauthenticate) {
                            alert(res.unauthenticate);
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