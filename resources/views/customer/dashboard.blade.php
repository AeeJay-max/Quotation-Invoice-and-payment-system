@extends('layout')

@section('title', 'Customer Exhibitor Portal')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-shield text-primary mr-2"></i> Exhibitor Customer Portal</h2>
            <p class="text-muted mb-0">Welcome back, <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->clientRecord->company_name ?? 'Exhibitor' }})</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif

    <!-- Active Event Overview Banner -->
    @if($activeBooking)
        <div class="card bg-gradient-primary text-white mb-4 elevation-2">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <span class="badge badge-warning text-dark font-weight-bold mb-2">ACTIVE EVENT BOOKING</span>
                        <h3 class="font-weight-bold mb-1">{{ $activeBooking->event->name ?? 'Exhibition Event' }}</h3>
                        <p class="mb-0">Booking Reference: <strong>{{ $activeBooking->booking_number }}</strong> | Space: <strong>{{ optional($activeBooking->space)->name }} ({{ $activeBooking->width }}m × {{ $activeBooking->length }}m)</strong></p>
                    </div>
                    <div class="col-md-4 text-md-right mt-3 mt-md-0">
                        <a href="{{ route('customer.bookings.show', $activeBooking->id) }}" class="btn btn-light btn-lg font-weight-bold text-primary shadow-sm">
                            <i class="fas fa-eye mr-1"></i> View Booking Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Paid Percentage Progress Card -->
    @if($activeBooking)
    <div class="card mb-4 border-left-info shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-chart-pie text-info mr-2"></i> Quotation Payment Progress</h5>
                    <p class="text-muted mb-2">Total Amount: <strong>${{ number_format($grandTotal, 2) }}</strong> | Paid: <strong class="text-success">${{ number_format($totalPaid, 2) }}</strong> | Balance: <strong class="text-danger">${{ number_format($totalBalance, 2) }}</strong></p>
                    <div class="progress progress-sm" style="height: 18px;">
                        <div class="progress-bar bg-{{ $paidPercentage == 100 ? 'success' : ($paidPercentage > 0 ? 'info' : 'warning') }} font-weight-bold" role="progressbar" style="width: {{ $paidPercentage }}%;" aria-valuenow="{{ $paidPercentage }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $paidPercentage }}% Paid
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <span class="h2 font-weight-bold text-{{ $paidPercentage == 100 ? 'success' : ($paidPercentage > 0 ? 'info' : 'warning') }}">{{ $paidPercentage }}%</span>
                    <span class="d-block text-muted small font-weight-bold">PAID PERCENTAGE</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Metric Stat Cards -->
    <div class="row">
        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-info elevation-2">
                <div class="inner">
                    <h3>${{ number_format($totalPaid, 2) }}</h3>
                    <p>Amount Paid ({{ $paidPercentage }}%)</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
                <a href="{{ route('customer.payments.index') }}" class="small-box-footer">View Payments <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-{{ $totalBalance > 0 ? 'warning' : 'success' }} elevation-2">
                <div class="inner">
                    <h3>${{ number_format($totalBalance, 2) }}</h3>
                    <p>Outstanding Balance</p>
                </div>
                <div class="icon"><i class="fas fa-balance-scale"></i></div>
                <a href="{{ route('customer.invoices.index') }}" class="small-box-footer">View Invoices <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-success elevation-2">
                <div class="inner">
                    <h3>{{ $approvedAttendees }} / {{ $attendeeCount }}</h3>
                    <p>Approved Attendees</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('customer.attendees.index') }}" class="small-box-footer">Manage Attendees <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="small-box bg-dark elevation-2">
                <div class="inner">
                    <h3>{{ $badgesGenerated }}</h3>
                    <p>Badges Generated</p>
                </div>
                <div class="icon"><i class="fas fa-id-badge"></i></div>
                <a href="{{ route('customer.badges.index') }}" class="small-box-footer">View Badges <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Official Banking Details Card -->
    <div class="card card-outline card-warning shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-university text-warning mr-2"></i> Official Banking Details & Payment Instructions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light" style="width: 35%;">Bank Name:</th>
                                    <td class="font-weight-bold text-primary">{{ $bankDetails['bank'] ?? 'Standard Chartered Bank' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Branch:</th>
                                    <td class="font-weight-bold">{{ $bankDetails['branch'] ?? 'Main Branch' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Account Number:</th>
                                    <td class="font-weight-bold text-dark h6 mb-0">{{ $bankDetails['acc_number'] ?? '109283746501' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Account Name:</th>
                                    <td class="font-weight-bold">{{ $bankDetails['acc_name'] ?? 'Invoice & Quotation System Ltd' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5 mt-3 mt-md-0">
                    <div class="p-3 bg-light border rounded">
                        <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> CRITICAL PAYMENT REFERENCE RULE:</h6>
                        <p class="small text-dark mb-2">When making a bank transfer, you <strong>MUST</strong> use your <strong>Quotation Number</strong> as the payment reference line.</p>
                        <div class="alert alert-warning p-2 text-center mb-2">
                            <span class="small d-block text-muted">YOUR PAYMENT REFERENCE:</span>
                            <strong class="h5 font-weight-bold text-dark">{{ $activeBooking->quotation->quotation_number ?? 'QUO-2026-XXXXXX' }}</strong>
                        </div>
                        <a href="{{ route('customer.payments.index') }}" class="btn btn-warning btn-block font-weight-bold text-dark shadow-sm btn-sm">
                            <i class="fas fa-upload mr-1"></i> Submit Proof of Payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Links -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-outline card-primary h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-tasks mr-2"></i> Exhibitor Checklist & Actions</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-invoice text-primary mr-2"></i> Review Quotation & Booking</span>
                            <a href="{{ route('customer.bookings.index') }}" class="btn btn-xs btn-outline-primary">View</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-credit-card text-success mr-2"></i> Submit Proof of Payment</span>
                            <a href="{{ route('customer.payments.index') }}" class="btn btn-xs btn-outline-success">Upload Payment</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-plus text-info mr-2"></i> Register Booth Staff Attendees</span>
                            <a href="{{ route('customer.attendees.index') }}" class="btn btn-xs btn-outline-info">Register Staff</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-outline card-secondary h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-history mr-2"></i> Recent Event Bookings</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Booking #</th>
                                <th>Event</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td class="font-weight-bold">{{ $b->booking_number }}</td>
                                    <td>{{ $b->event->name ?? 'N/A' }}</td>
                                    <td class="font-weight-bold">${{ number_format($b->grand_total, 2) }}</td>
                                    <td><span class="badge badge-success">{{ strtoupper($b->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
