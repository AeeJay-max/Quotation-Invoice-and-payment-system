@extends('layout')
@section('title', 'Reports & Analytics')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Reports & Financial Analytics</h2>
            <p class="text-muted mb-0">Multi-event executive dashboard, attendance statistics, and interactive financial charts.</p>
        </div>
        <div>
            <a href="{{ route('admin.reports.export', 'pdf') }}" class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf mr-1"></i> Export Official PDF Report
            </a>
        </div>
    </div>

    <!-- Active Event Context Banner -->
    @if($activeEvent)
        <div class="alert alert-info d-flex justify-content-between align-items-center mb-4 shadow-sm">
            <div>
                <i class="fas fa-filter mr-2"></i> Filtering Analytics for Active Event:
                <strong class="h6 mb-0 ml-1">{{ $activeEvent->name }}</strong> ({{ $activeEvent->event_code }})
            </div>
            <form action="{{ route('admin.set-event') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="event_id" value="all">
                <button type="submit" class="btn btn-sm btn-outline-info bg-white text-info font-weight-bold">
                    View All Events Global Summary
                </button>
            </form>
        </div>
    @endif

    <!-- Summary Metric Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <small class="text-white-50 uppercase font-weight-bold">Total Invoiced</small>
                    <h3 class="font-weight-bold mb-0">${{ number_format($stats['total_invoiced'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <small class="text-white-50 uppercase font-weight-bold">Verified Payments</small>
                    <h3 class="font-weight-bold mb-0">${{ number_format($stats['total_verified_payments'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <small class="text-dark-50 uppercase font-weight-bold">Outstanding Receivables</small>
                    <h3 class="font-weight-bold mb-0">${{ number_format($stats['total_outstanding'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <small class="text-white-50 uppercase font-weight-bold">Entrance Check-Ins</small>
                    <h3 class="font-weight-bold mb-0">{{ number_format($stats['total_checkins']) }} / {{ number_format($stats['total_attendees']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Financial Breakdown Pie Chart -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white font-weight-bold border-bottom">
                    <i class="fas fa-chart-pie text-success mr-2"></i> Financial Distribution
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                    <div style="width: 100%; max-width: 280px; height: 260px;">
                        <canvas id="financialPieChart"></canvas>
                    </div>
                    <div class="mt-3 w-100 small">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span><i class="fas fa-circle text-success mr-1"></i> Verified Payments</span>
                            <span class="font-weight-bold">${{ number_format($chartFinancials['data'][0], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span><i class="fas fa-circle text-warning mr-1"></i> Outstanding Receivables</span>
                            <span class="font-weight-bold">${{ number_format($chartFinancials['data'][1], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span><i class="fas fa-circle text-info mr-1"></i> Pending Quotations</span>
                            <span class="font-weight-bold">${{ number_format($chartFinancials['data'][2], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings & Quotations Pie Chart -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white font-weight-bold border-bottom">
                    <i class="fas fa-chart-pie text-primary mr-2"></i> Application Status Ratio
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                    <div style="width: 100%; max-width: 280px; height: 260px;">
                        <canvas id="bookingsPieChart"></canvas>
                    </div>
                    <div class="mt-3 w-100 small">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span><i class="fas fa-circle text-primary mr-1"></i> Confirmed Bookings</span>
                            <span class="font-weight-bold">{{ $chartBookings['data'][0] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span><i class="fas fa-circle text-warning mr-1"></i> Pending Quotations</span>
                            <span class="font-weight-bold">{{ $chartBookings['data'][1] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span><i class="fas fa-circle text-danger mr-1"></i> Rejected Applications</span>
                            <span class="font-weight-bold">{{ $chartBookings['data'][2] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendee Check-In Doughnut Chart -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white font-weight-bold border-bottom">
                    <i class="fas fa-chart-pie text-info mr-2"></i> Attendee Check-In Ratio
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                    <div style="width: 100%; max-width: 280px; height: 260px;">
                        <canvas id="attendeeDoughnutChart"></canvas>
                    </div>
                    <div class="mt-3 w-100 small">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span><i class="fas fa-circle text-success mr-1"></i> Checked-In Attendees</span>
                            <span class="font-weight-bold">{{ $chartAttendees['data'][0] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span><i class="fas fa-circle text-secondary mr-1"></i> Awaiting Check-In</span>
                            <span class="font-weight-bold">{{ $chartAttendees['data'][1] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Detailed Breakdown Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white font-weight-bold py-3 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table text-primary mr-2"></i> Per-Event Financial & Operational Breakdown Table</span>
            <span class="badge badge-light border">{{ count($eventBreakdowns) }} Events</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Event Name</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Total Invoiced</th>
                            <th>Verified Payments</th>
                            <th>Outstanding</th>
                            <th>Bookings</th>
                            <th>Quotations</th>
                            <th>Check-Ins</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventBreakdowns as $row)
                            <tr>
                                <td><span class="font-weight-bold text-dark">{{ $row['name'] }}</span></td>
                                <td><code>{{ $row['code'] }}</code></td>
                                <td><span class="badge badge-success px-2 py-1">{{ strtoupper($row['status']) }}</span></td>
                                <td><span class="font-weight-bold text-dark">${{ number_format($row['invoiced'], 2) }}</span></td>
                                <td><span class="font-weight-bold text-success">${{ number_format($row['paid'], 2) }}</span></td>
                                <td><span class="font-weight-bold text-warning">${{ number_format($row['outstanding'], 2) }}</span></td>
                                <td><span class="badge badge-primary">{{ $row['bookings_count'] }}</span></td>
                                <td><span class="badge badge-light border">{{ $row['quotations_count'] }}</span></td>
                                <td><span class="badge badge-info">{{ $row['checkins_count'] }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No events data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include Chart.js via CDN for responsive pie charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Financial Distribution Pie Chart
    const ctxFinancial = document.getElementById('financialPieChart').getContext('2d');
    new Chart(ctxFinancial, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartFinancials['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartFinancials['data']) !!},
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Bookings & Applications Pie Chart
    const ctxBookings = document.getElementById('bookingsPieChart').getContext('2d');
    new Chart(ctxBookings, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartBookings['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartBookings['data']) !!},
                backgroundColor: ['#007bff', '#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. Attendee Check-In Doughnut Chart
    const ctxAttendee = document.getElementById('attendeeDoughnutChart').getContext('2d');
    new Chart(ctxAttendee, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartAttendees['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartAttendees['data']) !!},
                backgroundColor: ['#28a745', '#6c757d'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endpush
