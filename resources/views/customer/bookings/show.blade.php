@extends('layout')

@section('title', 'Booking Details #' . $booking->booking_number)

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-bookmark text-primary mr-2"></i> BOOKING #{{ $booking->booking_number }}</h2>
            <p class="text-muted mb-0">Event: <strong>{{ $booking->event->name ?? 'N/A' }}</strong></p>
        </div>
        <a href="{{ route('customer.bookings.index') }}" class="btn btn-secondary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> Back to Bookings</a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0">Stand & Space Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Hall / Space:</strong> {{ optional($booking->space)->name }}</div>
                        <div class="col-md-6 mb-2"><strong>Stand Type:</strong> {{ optional($booking->standType)->name }}</div>
                        <div class="col-md-6 mb-2"><strong>Dimensions:</strong> {{ $booking->width }}m × {{ $booking->length }}m ({{ $booking->area_sqm }}m²)</div>
                        <div class="col-md-6 mb-2"><strong>Position:</strong> {{ optional($booking->position)->position_number ?? 'Standard' }}</div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0">Financial Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm text-right">
                            <tr><th>Space Cost:</th><td>${{ number_format($booking->space_cost, 2) }}</td></tr>
                            <tr><th>Furniture Subtotal:</th><td>${{ number_format($booking->furniture_total, 2) }}</td></tr>
                            <tr><th>Services Subtotal:</th><td>${{ number_format($booking->services_total, 2) }}</td></tr>
                            <tr><th>Subtotal:</th><td>${{ number_format($booking->subtotal, 2) }}</td></tr>
                            <tr><th>VAT (15%):</th><td>${{ number_format($booking->vat_amount, 2) }}</td></tr>
                            <tr class="h5 font-weight-bold"><th>Grand Total:</th><td class="text-success">${{ number_format($booking->grand_total, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header"><h5 class="card-title font-weight-bold mb-0">Quick Links</h5></div>
                <div class="card-body">
                    @if($booking->invoice)
                        <a href="{{ route('customer.invoices.show', $booking->invoice->id) }}" class="btn btn-outline-primary btn-block font-weight-bold mb-2">
                            <i class="fas fa-file-invoice mr-1"></i> View Invoice ({{ $booking->invoice->invoice_number }})
                        </a>
                    @endif
                    <a href="{{ route('customer.attendees.index', ['booking_id' => $booking->id]) }}" class="btn btn-outline-info btn-block font-weight-bold mb-2">
                        <i class="fas fa-user-plus mr-1"></i> Register Company Attendees
                    </a>
                    <a href="{{ route('customer.payments.index') }}" class="btn btn-outline-success btn-block font-weight-bold">
                        <i class="fas fa-credit-card mr-1"></i> Upload Proof of Payment
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
