<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Public Event Exhibition Quotation Wizard">
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/images/favicon.png')}}">
    <title>@yield('title') | {{$global_settings['app_name'] ?? 'Exhibition Portal'}}</title>
    @include('partials.styles')
    <style>
        .public-navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 10px 0; }
        .public-navbar .nav-link { color: #333; font-weight: 500; padding: 10px 20px; text-transform: uppercase; letter-spacing: 1px; }
        .public-navbar .nav-link:hover { color: #FFD200; }
        .public-navbar .nav-item.active .nav-link { color: #006B3F; font-weight: bold; }
        .public-navbar .navbar-brand img { height: 40px; }
        body.public-layout { background-color: #f8f9fa; padding-top: 70px; }
    </style>
</head>

<body class="public-layout">

    <nav class="navbar navbar-expand-lg public-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset($global_settings['logo'] ?? '') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#publicNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home mr-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.booking.wizard') }}"><i class="fas fa-file-invoice-dollar mr-1"></i> Exhibition Quotation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary font-weight-bold" href="{{ route('login') }}"><i class="fas fa-sign-in-alt mr-1"></i> Exhibitor Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @section('content')
    @show

    @include('partials.scripts')
    @stack('scripts')
</body>

</html>
