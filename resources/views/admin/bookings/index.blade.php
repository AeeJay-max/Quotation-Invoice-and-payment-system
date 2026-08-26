@extends('layout')

@section('title', 'Confirmed Quotations & Event Bookings')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-contract text-success mr-2"></i> Confirmed Quotations & Event Bookings</h2>
            <p class="text-muted mb-0">Manage all accepted exhibition bookings, space allocations, invoices, attendees, and badge status.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4 elevation-1">
        <div class="card-body">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="form-row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Search by booking #, company, or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="event_id" class="form-control">
                        <option value="">All Events</option>
                        @foreach($events as $e)
                            <option value="{{ $e->id }}" {{ request('event_id') == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted / Confirmed</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted / Pending</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold"><i class="fas fa-filter mr-1"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-outline card-success elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Booking #</th>
                            <th>Quotation #</th>
                            <th>Exhibitor / Company</th>
                            <th>Event</th>
                            <th>Space & Size</th>
                            <th>Grand Total</th>
                            <th>Accepted Date</th>
                            <th>Invoice Status</th>
                            <th>Payment</th>
                            <th>Attendees</th>
                            <th>Badges</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="font-weight-bold text-primary">
                                        {{ $b->booking_number }}
                                    </a>
                                </td>
                                <td><span class="badge badge-light border">{{ optional($b->quotation)->quotation_number ?? 'QUO-'.$b->quotation_id }}</span></td>
                                <td>
                                    <strong class="d-block">{{ $b->client->company_name ?? 'N/A' }}</strong>
                                    <small class="text-muted">{{ $b->client->name ?? 'N/A' }}</small>
                                </td>
                                <td><small class="font-weight-bold">{{ $b->event->name ?? 'N/A' }}</small></td>
                                <td>
                                    <small class="d-block font-weight-bold">{{ optional($b->space)->name }}</small>
                                    <span class="badge badge-secondary">{{ $b->width }}m × {{ $b->length }}m ({{ $b->area_sqm }}m²)</span>
                                </td>
                                <td class="font-weight-bold text-success">${{ number_format($b->grand_total, 2) }}</td>
                                <td><small>{{ $b->accepted_at ? $b->accepted_at->format('d M Y H:i') : $b->created_at->format('d M Y') }}</small></td>
                                <td>
                                    @if($b->invoice)
                                        <span class="badge badge-info">{{ $b->invoice->invoice_number }}</span>
                                    @else
                                        <span class="badge badge-warning">Pending Invoice</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $b->payment_status === 'paid' ? 'success' : ($b->payment_status === 'partially_paid' ? 'warning' : 'danger') }}">
                                        {{ strtoupper(str_replace('_', ' ', $b->payment_status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-dark">{{ $b->attendees->count() }} Attendees</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $b->badges->count() > 0 ? 'success' : 'light' }}">
                                        {{ $b->badges->count() }} Badges
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $b->id) }}" class="btn btn-sm btn-primary font-weight-bold">
                                        <i class="fas fa-eye mr-1"></i> View 360°
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">No confirmed bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
