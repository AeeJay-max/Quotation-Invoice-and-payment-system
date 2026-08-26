
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description-Pritech" content="">
    <meta name="author-Pritech" content="">
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{asset('assets\images\favicon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets\images\favicon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets\images\favicon.png')}}">
    <title></title>
    @include('partials/styles')

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><img src="{{asset($global_settings['logo']  ?? '')}}" alt="{{$global_settings['app_name'] ?? ''}}" class=" elevation-3"
                     style="opacity: .8;width: 108px"></a>
  </div>
  <!-- /.login-logo -->
  <div class="card card-outline card-primary shadow">
    <div class="card-header text-center bg-primary text-white py-3">
        <h4 class="font-weight-bold mb-0"><i class="fas fa-user-shield mr-2"></i> EXHIBITOR PORTAL LOGIN</h4>
        <small class="text-white-50">Sign in to manage your quotations, payments & attendees</small>
    </div>
    <div class="card-body login-card-body p-4">
<div class="row">
    <div class="col-lg-12">
                @if(session('error'))
                    <div id="error_m" class="alert alert-danger font-weight-bold">
                        {{session('error')}}
                    </div>
                @endif
                @if(session('success'))
                    <div id="success_m" class="alert alert-success font-weight-bold">
                        {{session('success')}}
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
    </div>
</div>
      <form action="/login" method="post" enctype="multipart/form-data">
          @csrf
        <div class="input-group mb-3">
          <input name="email" type="email" class="form-control form-control-lg" placeholder="Exhibitor Email" value="{{ old('email') }}" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control form-control-lg" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember Me</label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block font-weight-bold">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="text-center mt-3 pt-3 border-top">
          <p class="mb-1 text-muted">Don't have an account yet?</p>
          <a href="/" class="btn btn-outline-success btn-sm font-weight-bold">
              <i class="fas fa-calculator mr-1"></i> Request Exhibition Quotation
          </a>
      </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


@include('partials/scripts')
</body>
</html>
