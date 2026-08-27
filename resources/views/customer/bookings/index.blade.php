@extends('customer-layout')

@section('title', 'My Event Bookings')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark"><i class="fas fa-bookmark text-primary mr-2"></i> My Event Bookings</h2>
    </div>

    <div class="card card-outline card-primary elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Booking #</th>
                            <th>Event</th>
                            <th>Space & Package</th>
                            <th>Dimensions</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                            <tr>
                                <td><strong class="text-primary">{{ $b->booking_number }}</strong></td>
                                <td>{{ $b->event->name ?? 'N/A' }}</td>
                                <td>{{ optional($b->space)->name }} ({{ optional($b->standType)->name }})</td>
                                <td>{{ $b->width }}m × {{ $b->length }}m ({{ $b->area_sqm }}m²)</td>
                                <td class="font-weight-bold text-success">${{ number_format($b->grand_total, 2) }}</td>
                                <td><span class="badge badge-success">{{ strtoupper($b->status) }}</span></td>
                                <td>
                                    <a href="{{ route('customer.bookings.show', $b->id) }}" class="btn btn-sm btn-info font-weight-bold">
                                        <i class="fas fa-eye mr-1"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No event bookings found.</td>
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

