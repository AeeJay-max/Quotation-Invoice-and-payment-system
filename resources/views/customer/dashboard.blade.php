@extends('customer-layout')

@section('title', 'Exhibitor Dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-tachometer-alt text-primary"></i> Exhibitor Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- QUICK ACTIONS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-bolt"></i> Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('customer.quotations.index') }}" class="btn btn-outline-info m-1"><i class="fas fa-file-alt mr-1"></i> View My Quotations</a>
                            <a href="{{ route('customer.quotations.index') }}?status=approved" class="btn btn-outline-warning m-1"><i class="fas fa-exclamation-circle mr-1"></i> View Approved Quotations</a>
                            <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-success m-1"><i class="fas fa-file-contract mr-1"></i> View My Bookings</a>
                            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-primary m-1"><i class="fas fa-file-invoice-dollar mr-1"></i> View Invoices</a>
                            <a href="{{ route('customer.payments.index') }}" class="btn btn-outline-secondary m-1"><i class="fas fa-money-check mr-1"></i> Submit Payment Proof</a>
                            <a href="{{ route('customer.attendees.index') }}" class="btn btn-outline-dark m-1"><i class="fas fa-users mr-1"></i> Manage Attendees</a>
                            <a href="{{ route('customer.badges.index') }}" class="btn btn-outline-secondary m-1"><i class="fas fa-id-badge mr-1"></i> View Badges</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY CARDS -->
            <h5 class="mb-3 font-weight-bold">My Exhibition Summary</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner"><h3>{{ $pendingQuotations }}</h3><p>Pending Quotations</p></div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>{{ $approvedQuotations }}</h3><p>Approved Quotations</p></div>
                        <div class="icon"><i class="fas fa-check"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>{{ $confirmedBookings }}</h3><p>Confirmed Bookings</p></div>
                        <div class="icon"><i class="fas fa-file-contract"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner"><h3>{{ $attendeeCount }}</h3><p>Attendees</p></div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>

            <!-- FINANCIAL SUMMARY -->
            <h5 class="mb-3 font-weight-bold mt-4">Financial Overview</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner"><h3>${{ number_format($totalInvoiced, 2) }}</h3><p>Total Invoiced</p></div>
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>${{ number_format($totalPaid, 2) }}</h3><p>Verified Paid</p></div>
                        <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>${{ number_format($totalBalance, 2) }}</h3><p>Outstanding Balance</p></div>
                        <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>{{ $paidPercentage }}<sup style="font-size: 20px">%</sup></h3><p>Payment Progress</p></div>
                        <div class="icon"><i class="fas fa-chart-pie"></i></div>
                    </div>
                </div>
            </div>

            @if(($pendingPaymentsCount ?? 0) > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-clock fa-2x mr-3"></i>
                        <div>
                            <strong>{{ $pendingPaymentsCount }} payment(s) awaiting admin verification.</strong>
                            These amounts are not yet counted in your Verified Paid total.
                            <a href="{{ route('customer.payments.index') }}" class="btn btn-sm btn-warning ml-3">View Payments</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </section>
</div>

@endsection

