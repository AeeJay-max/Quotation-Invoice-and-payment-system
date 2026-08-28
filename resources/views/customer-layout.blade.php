<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/images/favicon.png')}}">
    <title>@yield('title') | {{$global_settings['app_name'] ?? 'Exhibitor Portal'}}</title>
    @include('partials.styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('customer.dashboard') }}" class="nav-link">Exhibitor Dashboard</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                   <form action="{{ route('logout') }}" method="post">
                       @csrf
                       <button class="dropdown-item">
                           <i class="fas fa-sign-out-alt mr-2"></i> Logout
                       </button>
                   </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('customer.dashboard') }}" class="m-auto brand-link">
            <img src="{{asset($global_settings['logo'] ?? '')}}" alt="Logo" class="elevation-3" style="opacity: .8;width: 108px">
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info align-items-center">
                    <a href="#" class="d-block text-warning font-weight-bold">
                        EXHIBITOR: {{strtoupper(Auth::user()->name)}}
                    </a>
                </div>
            </div>

            <nav class="mt-2 mb-5">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-header font-weight-bold text-warning">MY EXHIBITION</li>

                    <li class="nav-item">
                        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.quotations.index') }}" class="nav-link {{ request()->routeIs('customer.quotations.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>My Quotations</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.bookings.index') }}" class="nav-link {{ request()->routeIs('customer.bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-file-contract nav-icon"></i>
                            <p>My Bookings</p>
                        </a>
                    </li>

                    <li class="nav-header font-weight-bold text-muted">FINANCE</li>
                    <li class="nav-item">
                        <a href="{{ route('customer.invoices.index') }}" class="nav-link {{ request()->routeIs('customer.invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>My Invoices</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.payments.index') }}" class="nav-link {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card nav-icon"></i>
                            <p>Payments</p>
                        </a>
                    </li>

                    <li class="nav-header font-weight-bold text-muted">ATTENDEES</li>
                    <li class="nav-item">
                        <a href="{{ route('customer.attendees.index') }}" class="nav-link {{ request()->routeIs('customer.attendees.*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <p>My Attendees</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.badges.index') }}" class="nav-link {{ request()->routeIs('customer.badges.*') ? 'active' : '' }}">
                            <i class="fas fa-id-badge nav-icon"></i>
                            <p>My Badges</p>
                        </a>
                    </li>

                    <li class="nav-header font-weight-bold text-muted">ACCOUNT</li>
                    <li class="nav-item">
                        <a href="/profile" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                            <i class="fas fa-user-cog nav-icon"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt nav-icon text-danger"></i>
                            <p class="text-danger">Logout</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    @section('content')
    @show

</div>
@include('partials.footer')
@include('partials.scripts')
@stack('scripts')
</body>
</html>
