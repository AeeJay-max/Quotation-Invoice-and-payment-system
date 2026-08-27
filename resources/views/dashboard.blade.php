@extends('layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-tachometer-alt text-primary"></i> Admin Dashboard</h1>
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
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title font-weight-bold"><i class="fas fa-bolt"></i> Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-outline-primary m-1"><i class="fas fa-calendar-plus mr-1"></i> Create Event</a>
                            <a href="/quotation?status=pending" class="btn btn-outline-warning m-1"><i class="fas fa-file-signature mr-1"></i> Review Quotations</a>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-success m-1"><i class="fas fa-file-contract mr-1"></i> Manage Bookings</a>
                            <a href="/invoice" class="btn btn-outline-info m-1"><i class="fas fa-money-check-alt mr-1"></i> View Invoices</a>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-danger m-1">
                                <i class="fas fa-money-check-alt mr-1"></i> Payment Verifications
                                @if($pendingPaymentsCount > 0)
                                    <span class="badge badge-danger ml-1">{{ $pendingPaymentsCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.attendees.index') }}" class="btn btn-outline-secondary m-1"><i class="fas fa-users mr-1"></i> Manage Attendees</a>
                            <a href="{{ route('admin.badges.index') }}" class="btn btn-outline-dark m-1"><i class="fas fa-id-badge mr-1"></i> Manage Badges</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CORE METRICS -->
            <h5 class="mb-3 font-weight-bold">Operational Statistics</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>{{ $totalEvents }}</h3><p>Total Events</p></div>
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>{{ $upcomingEventsCount }}</h3><p>Upcoming Events</p></div>
                        <div class="icon"><i class="fas fa-calendar-day"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner"><h3>{{ $totalExhibitors }}</h3><p>Total Exhibitors</p></div>
                        <div class="icon"><i class="fas fa-store"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner"><h3>{{ $pendingQuotations }}</h3><p>Pending Quotations</p></div>
                        <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>{{ $approvedQuotations }}</h3><p>Approved Quotations</p></div>
                        <div class="icon"><i class="fas fa-check"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>{{ $rejectedQuotations }}</h3><p>Rejected Quotations</p></div>
                        <div class="icon"><i class="fas fa-times"></i></div>
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
                        <div class="inner"><h3>{{ $totalAttendees }}</h3><p>Total Attendees</p></div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
            </div>

            <!-- FINANCIAL METRICS -->
            <h5 class="mb-3 font-weight-bold mt-4">Financial Statistics</h5>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <a href="{{ route('admin.payments.index') }}" style="text-decoration:none;">
                        <div class="small-box {{ $pendingPaymentsCount > 0 ? 'bg-danger' : 'bg-secondary' }}">
                            <div class="inner">
                                <h3>{{ $pendingPaymentsCount }}</h3>
                                <p>Payments Pending Verification</p>
                            </div>
                            <div class="icon"><i class="fas fa-money-check-alt"></i></div>
                            <span class="small-box-footer">
                                {{ $pendingPaymentsCount > 0 ? 'Click to review →' : 'All verified ✓' }}
                            </span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>${{ number_format($totalInvoiced, 2) }}</h3><p>Total Invoiced</p></div>
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>${{ number_format($totalPaid, 2) }}</h3><p>Total Paid</p></div>
                        <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>${{ number_format($outstandingBalance, 2) }}</h3><p>Outstanding Balance</p></div>
                        <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    </div>
                </div>
            </div>

            <!-- RECENT LISTS -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Recent Quotations</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentQuotations as $q)
                                        <tr>
                                            <td>{{ $q->quotation_number ?? 'QUO-'.$q->id }}</td>
                                            <td>{{ $q->client->company_name ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusDisplay = strtoupper($q->status);
                                                    if ($q->status === 'pending') $statusDisplay = 'PENDING ADMIN APPROVAL';
                                                    if ($q->status === 'approved') $statusDisplay = 'APPROVED';
                                                @endphp
                                                <span class="badge {{ $q->status == 'pending' ? 'badge-warning' : ($q->status == 'approved' ? 'badge-info' : 'badge-secondary') }}">
                                                    {{ $statusDisplay }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No recent quotations.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Recent Bookings</h3>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table class="table table-striped table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Booking #</th>
                                        <th>Company</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBookings as $b)
                                        <tr>
                                            <td>{{ $b->booking_number ?? 'BOOK-'.$b->id }}</td>
                                            <td>{{ $b->client->company_name ?? 'N/A' }}</td>
                                            <td>${{ number_format($b->grand_total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-muted">No recent bookings.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
