@extends('layout')

@section('title', 'Booking Details #' . $booking->booking_number)

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-bookmark text-primary mr-2"></i> BOOKING #{{ $booking->booking_number }}</h2>
            <p class="text-muted mb-0">Exhibitor: <strong>{{ $booking->client->company_name ?? 'N/A' }}</strong> | Event: <strong>{{ $booking->event->name ?? 'N/A' }}</strong></p>
        </div>
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary font-weight-bold mr-2"><i class="fas fa-arrow-left mr-1"></i> Back</a>
            <span class="badge badge-success px-3 py-2 text-uppercase font-weight-bold h6 mb-0">{{ $booking->status }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif

    <!-- 360 Progress Timeline Card -->
    <div class="card card-outline card-primary mb-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-stream mr-2"></i> Booking Progress Timeline</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col font-weight-bold text-success">
                    <i class="fas fa-check-circle fa-2x d-block mb-1"></i>
                    Quotation Submitted
                </div>
                <div class="col font-weight-bold text-success">
                    <i class="fas fa-check-circle fa-2x d-block mb-1"></i>
                    Quotation Accepted
                </div>
                <div class="col font-weight-bold {{ $booking->user_id ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-{{ $booking->user_id ? 'check-circle' : 'circle' }} fa-2x d-block mb-1"></i>
                    Account Created
                </div>
                <div class="col font-weight-bold {{ $booking->invoice_id ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-{{ $booking->invoice_id ? 'check-circle' : 'circle' }} fa-2x d-block mb-1"></i>
                    Invoice Generated
                </div>
                <div class="col font-weight-bold {{ $booking->payment_status === 'paid' ? 'text-success' : ($booking->payment_status === 'partially_paid' ? 'text-warning' : 'text-muted') }}">
                    <i class="fas fa-{{ $booking->payment_status !== 'unpaid' ? 'check-circle' : 'circle' }} fa-2x d-block mb-1"></i>
                    Payment ({{ strtoupper(str_replace('_', ' ', $booking->payment_status)) }})
                </div>
                <div class="col font-weight-bold {{ $booking->attendee_status === 'approved' ? 'text-success' : ($booking->attendee_status === 'submitted' ? 'text-warning' : 'text-muted') }}">
                    <i class="fas fa-{{ $booking->attendees->count() > 0 ? 'check-circle' : 'circle' }} fa-2x d-block mb-1"></i>
                    Attendees ({{ $booking->attendees->count() }})
                </div>
                <div class="col font-weight-bold {{ $booking->badges->count() > 0 ? 'text-success' : 'text-muted' }}">
                    <i class="fas fa-{{ $booking->badges->count() > 0 ? 'check-circle' : 'circle' }} fa-2x d-block mb-1"></i>
                    Badges ({{ $booking->badges->count() }})
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Booking & Space Details -->
        <div class="col-lg-8">
            <div class="card card-outline card-info mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-cube mr-2"></i> Exhibition Space & Package Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Hall / Space:</strong> {{ optional($booking->space)->name }}</p>
                            <p class="mb-1"><strong>Stand Type:</strong> {{ optional($booking->standType)->name }}</p>
                            <p class="mb-1"><strong>Dimensions:</strong> {{ $booking->width }}m × {{ $booking->length }}m</p>
                            <p class="mb-1"><strong>Total Area:</strong> {{ $booking->area_sqm }} m²</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Position / Booth #:</strong> {{ optional($booking->position)->position_number ?? 'Standard' }}</p>
                            <p class="mb-1"><strong>Space Cost:</strong> ${{ number_format($booking->space_cost, 2) }}</p>
                            <p class="mb-1"><strong>Furniture Subtotal:</strong> ${{ number_format($booking->furniture_total, 2) }}</p>
                            <p class="mb-1"><strong>Services Subtotal:</strong> ${{ number_format($booking->services_total, 2) }}</p>
                        </div>
                    </div>

                    <h6 class="font-weight-bold border-bottom pb-2">Financial Totals</h6>
                    <div class="row">
                        <div class="col-md-3"><strong>Subtotal:</strong><br>${{ number_format($booking->subtotal, 2) }}</div>
                        <div class="col-md-3"><strong>Discount:</strong><br><span class="text-danger">-${{ number_format($booking->discount, 2) }}</span></div>
                        <div class="col-md-3"><strong>VAT:</strong><br>${{ number_format($booking->vat_amount, 2) }}</div>
                        <div class="col-md-3"><strong class="h5 font-weight-bold text-success">Grand Total:</strong><br><span class="h4 font-weight-bold text-success">${{ number_format($booking->grand_total, 2) }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Attendees & Badges Card -->
            <div class="card card-outline card-secondary mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-users mr-2"></i> Registered Company Attendees & Badges</h5>
                    @if($booking->attendees->count() > 0 && $booking->attendee_status !== 'approved')
                        <form action="{{ route('admin.bookings.approve-attendees', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success font-weight-bold">
                                <i class="fas fa-check-double mr-1"></i> Approve All Attendees & Generate Badges
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Ticket Type</th>
                                <th>Status</th>
                                <th>Badge Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking->attendees as $att)
                                <tr>
                                    <td class="font-weight-bold">{{ $att->full_name }}</td>
                                    <td>{{ $att->position ?? 'N/A' }}</td>
                                    <td><span class="badge badge-info">{{ optional($att->attendeeType)->name }}</span></td>
                                    <td><span class="badge badge-{{ $att->status === 'approved' ? 'success' : 'warning' }}">{{ strtoupper($att->status) }}</span></td>
                                    <td>
                                        @if($att->badge)
                                            <a href="{{ route('admin.badges.print', $att->badge->id) }}" class="btn btn-xs btn-outline-primary" target="_blank">
                                                <i class="fas fa-print mr-1"></i> {{ $att->badge->badge_code }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not Generated</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No attendees registered yet by exhibitor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Column -->
        <div class="col-lg-4">
            <!-- Exhibitor Contact Card -->
            <div class="card card-outline card-primary mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-building mr-2"></i> Exhibitor Profile</h5>
                </div>
                <div class="card-body">
                    <h5 class="font-weight-bold text-primary">{{ $booking->client->company_name ?? 'N/A' }}</h5>
                    <p class="mb-1"><strong>Contact Person:</strong> {{ $booking->client->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $booking->client->email ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $booking->client->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Country:</strong> {{ $booking->client->country ?? 'Zimbabwe' }}</p>
                    <p class="mb-0"><strong>Category:</strong> {{ $booking->client->business_category ?? 'General' }}</p>
                </div>
            </div>

            <!-- Invoice & Payment Status Card -->
            <div class="card card-outline card-success mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i> Invoice & Payment Summary</h5>
                </div>
                <div class="card-body">
                    @if($booking->invoice)
                        <p class="mb-1"><strong>Invoice Number:</strong> {{ $booking->invoice->invoice_number }}</p>
                        <p class="mb-1"><strong>Total Invoiced:</strong> ${{ number_format($booking->invoice->vat + $booking->invoice->items->sum(function($i){ return $i->quantity * $i->unit_price; }), 2) }}</p>
                        <p class="mb-1"><strong>Amount Paid:</strong> <span class="text-success font-weight-bold">${{ number_format($booking->invoice->amount_paid, 2) }}</span></p>
                        <p class="mb-3"><strong>Outstanding Balance:</strong> <span class="text-danger font-weight-bold">${{ number_format($booking->invoice->amount_outstanding, 2) }}</span></p>
                        
                        <!-- Verify Submitted Payments -->
                        @if($booking->invoice->payments->where('status', 'submitted')->count() > 0)
                            <h6 class="font-weight-bold text-warning border-top pt-2">Pending Proof of Payment:</h6>
                            @foreach($booking->invoice->payments->where('status', 'submitted') as $pay)
                                <div class="p-2 border rounded mb-2 bg-light">
                                    <small class="d-block">Amount: <strong>${{ number_format($pay->amount, 2) }}</strong> (Ref: {{ $pay->transaction_reference }})</small>
                                    <form action="{{ route('admin.payments.verify', $pay->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success mt-1"><i class="fas fa-check"></i> Verify Payment</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    @else
                        <p class="text-muted">Invoice pending generation.</p>
                    @endif
                </div>
            </div>

            <!-- History Log -->
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-history mr-2"></i> Status History</h5>
                </div>
                <div class="card-body p-2">
                    <ul class="list-unstyled mb-0">
                        @foreach($booking->statusHistories as $h)
                            <li class="border-bottom pb-2 mb-2">
                                <span class="font-weight-bold text-dark d-block">{{ $h->status }}</span>
                                <small class="text-muted d-block">{{ $h->notes }}</small>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $h->created_at->format('d M Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
