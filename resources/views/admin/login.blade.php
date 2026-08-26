<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>System Administrator Login - Admin Portal</title>
    <link rel="apple-touch-icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/images/favicon.png')}}">
    @include('partials/styles')
    <style>
        body { background: #1a252f; color: #fff; }
        .admin-card { background: #2c3e50; border-radius: 12px; border: 1px solid #34495e; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .admin-header { background: #e74c3c; color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 20px; text-align: center; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box" style="width: 420px;">
    <div class="card admin-card">
        <div class="admin-header">
            <h4 class="font-weight-bold mb-0"><i class="fas fa-user-shield mr-2"></i> SYSTEM ADMINISTRATOR</h4>
            <small class="text-white-50">RESTRICTED MANAGEMENT PORTAL</small>
        </div>
        <div class="card-body login-card-body p-4">
            <p class="login-box-msg text-light">Enter administrative credentials to proceed</p>

            @if(session('error'))
                <div id="error_m" class="alert alert-danger font-weight-bold">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div id="success_m" class="alert alert-success font-weight-bold">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/admin/mosrac" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Admin Email" value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text bg-secondary border-secondary text-white">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input name="password" type="password" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text bg-secondary border-secondary text-white">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mt-4">
                    <div class="col-8">
                        <div class="icheck-danger">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="text-light">Remember Me</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-danger btn-block font-weight-bold btn-lg">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials/scripts')
</body>
</html>
