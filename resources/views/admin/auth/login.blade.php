<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href=" {{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }} ">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href=" {{ asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }} ">
    <!-- Theme style -->
    <link rel="stylesheet" href=" {{ asset('assets/admin/dist/css/adminlte.min.css') }} ">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Вход</b>LTE</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg"></p>
            <form action="{{ route('admin.login.handler') }}" method="post">
                @csrf
                @error('email')
                <span class="text-red">{{ $message }}</span>
                @enderror
                <div class="input-group mb-3">
                    <input name="email" value="" type="email" class="form-control @error('email') is-invalid @enderror"  placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('password')
                    <span class="text-red">{{ $message }}</span>
                @enderror
                <div class="input-group mb-3">
                    <input name="password" value="" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Вход</button>
                    </div>
                </div>
            </form>
            <p class="mb-1">
                <a href="#"> Забыл пароль </a>
            </p>
            <p class="mb-0">
                <a href="#" class="text-center"> Зарегистрироваться </a>
            </p>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
