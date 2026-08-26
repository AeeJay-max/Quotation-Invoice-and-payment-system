@extends('layout')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-invoice text-success mr-2"></i> INVOICE {{ $invoice->invoice_number }}</h2>
            <p class="text-muted mb-0">Event: <strong>{{ $invoice->event->name ?? 'N/A' }}</strong></p>
        </div>
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-secondary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> Back to Invoices</a>
    </div>

    <div class="card card-outline card-success shadow-sm p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="font-weight-bold text-primary">ORGANIZER</h5>
                <p class="mb-1">{{ $invoice->event->name ?? 'Event Exhibition' }}</p>
                <p class="mb-1">{{ $invoice->event->venue ?? 'Exhibition Centre' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h5 class="font-weight-bold text-primary">EXHIBITOR</h5>
                <p class="mb-1">{{ $invoice->client->company_name ?? 'N/A' }}</p>
                <p class="mb-1">{{ $invoice->client->email ?? 'N/A' }}</p>
            </div>
        </div>

        <table class="table table-bordered mb-4">
            <thead class="thead-dark">
                <tr>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row justify-content-end mb-4">
            <div class="col-md-5">
                <table class="table table-sm text-right">
                    <tr><th>VAT:</th><td>${{ number_format($invoice->vat, 2) }}</td></tr>
                    <tr><th>Amount Paid:</th><td class="text-success font-weight-bold">${{ number_format($invoice->amount_paid, 2) }}</td></tr>
                    <tr class="h5 font-weight-bold"><th>Outstanding Balance:</th><td class="text-danger">${{ number_format($invoice->amount_outstanding, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between border-top pt-3">
            <a href="javascript:window.print()" class="btn btn-outline-secondary font-weight-bold"><i class="fas fa-print mr-1"></i> Print Invoice</a>
            <a href="{{ route('customer.payments.index') }}" class="btn btn-success font-weight-bold shadow"><i class="fas fa-credit-card mr-1"></i> Upload Payment Proof</a>
        </div>
    </div>
</div>
@endsection
