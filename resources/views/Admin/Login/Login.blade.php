<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Log in </title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('panel/dist/img/logo/favicon.ico')}}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('panel/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('panel/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('panel/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <img src="{{asset('panel/dist/img/logo/logo.png')}}" height="40">
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form id="LOGINFORM"  method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" id="email" name="email" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" placeholder="Enter your"class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3 mb-3">
                        <button type="submit" href="#" class="btn btn-block btn-theme">
                            Login
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('panel/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('panel/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('panel/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('panel/dist/js/custom-alert.js')}}"></script>

<script>
    $("#email").focus();
    $("#LOGINFORM").on('submit',function(e){
        if(e.isDefaultPrevented()){
        }else{
            e.preventDefault();
            var email = $("#email").val();
            var password = $("#password").val();
            $.ajax({
                url : '{{route('Admin.Login.Check')}}',
                type : 'post',
                data : new FormData(this),
                contentType : false,
                cache : false,
                processData : false,
                error: function(xhr, status, error) {
                    if(xhr.status == 422){
                        var res = JSON.parse(xhr.responseText);
                        var text = '';
                        if('password' in res.errors){$("#password").focus();}
                        if('email' in res.errors){$("#email").focus();}
                        $.each(res.errors, function (key, value) {
                            $.each(value, function (k, v) {
                                text += v+'<br>';
                            });
                        });
                        CustomAlert('warning', text);
                    }else{
                        CustomAlert('warning', JSON.parse(xhr.responseText).message);
                    }
                },
                success : function (data) {
                    if(data['status']){
                        CustomAlert('success', data.message);
                        setTimeout(function(e){
                            {{--window.location.href = '{{url()->previous()}}';--}}
                                window.location.href = '{{route("Admin.Dashboard")}}';
                        },1000);
                    }else{
                        CustomAlert('danger', data.message);
                        $("#email").focus();
                    }
                }
            });
        }
    });
</script>
</body>
</html>
