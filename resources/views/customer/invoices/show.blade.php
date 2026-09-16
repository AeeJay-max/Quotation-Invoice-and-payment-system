@extends('customer-layout')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<style>
    .invoice-doc { background:#fff; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.09); padding:32px; margin-bottom:40px; }
    .ministry-header { border-bottom:3px solid #1a5c1a; padding-bottom:14px; margin-bottom:18px; }
    .stamp-paid       { display:inline-block; border:4px solid #28a745; border-radius:6px; padding:6px 20px; transform:rotate(-8deg); }
    .stamp-partial    { display:inline-block; border:4px solid #fd7e14; border-radius:6px; padding:6px 14px; transform:rotate(-8deg); }
    .stamp-unpaid     { display:inline-block; border:4px solid #dc3545; border-radius:6px; padding:6px 20px; transform:rotate(-8deg); }
    .stamp-cancelled  { display:inline-block; border:4px solid #6c757d; border-radius:6px; padding:6px 14px; transform:rotate(-8deg); }
    @media print {
        .no-print { display:none !important; }
        .invoice-doc { box-shadow:none; padding:10px; }
    }
</style>

<div class="content-wrapper p-3 p-md-4">
    {{-- Back button --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ route('customer.invoices.index') }}" class="btn btn-secondary font-weight-bold">
            <i class="fas fa-arrow-left mr-1"></i> Back to Invoices
        </a>
        <a href="javascript:window.print()" class="btn btn-outline-secondary font-weight-bold">
            <i class="fas fa-print mr-1"></i> Print / Save PDF
        </a>
    </div>

    <div class="invoice-doc">

        {{-- ══ MINISTRY OFFICIAL HEADER ══ --}}
        <div class="ministry-header d-flex align-items-center">
            <div class="mr-4" style="flex-shrink:0;">
                <img src="{{ asset($ministrySettings['logo'] ?? 'assets/files/ministry-logo.png') }}"
                     alt="Ministry Logo" style="width:85px; height:auto;">
            </div>
            <div style="flex:1;">
                <div style="font-size:15px; font-weight:700; color:#1a5c1a; text-transform:uppercase; letter-spacing:0.5px; line-height:1.3;">
                    {{ $ministrySettings['app_name'] ?? 'Ministry of Sport, Recreation, Arts and Culture' }}
                </div>
                <div style="font-size:12px; color:#555; margin-top:4px; line-height:1.8;">
                    {{ $ministrySettings['app_address'] ?? 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare' }}<br>
                    {{ $ministrySettings['app_postal_address'] ?? 'P.O. Box HR 480 Harare' }}<br>
                    <strong>Email:</strong> {{ $ministrySettings['app_email'] ?? 'mosrac@kuzana.org.zw' }}
                    &nbsp;|&nbsp;
                    <strong>CC:</strong> {{ $ministrySettings['app_email_cc'] ?? 'secretariat@kuzana.org.zw' }}
                    &nbsp;|&nbsp;
                    <strong>Tel:</strong> {{ $ministrySettings['app_phone'] ?? '+263 772 394036 / +263 717 720 641 / +263 719 226 279 / +263 716 801 385' }}
                </div>
            </div>
        </div>

        {{-- ══ DOCUMENT TITLE ══ --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="font-weight-bold text-dark mb-0" style="text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-file-invoice text-success mr-2"></i> Tax Invoice
                </h4>
                <small class="text-muted">
                    Invoice No: <strong>{{ $invoice->invoice_number }}</strong>
                    &nbsp;|&nbsp;
                    Event: <strong>{{ $invoice->event->name ?? 'N/A' }}</strong>
                    &nbsp;|&nbsp;
                    Date: <strong>{{ optional($invoice->invoice_date ?? $invoice->created_at)->format('d M Y') }}</strong>
                </small>
            </div>

            {{-- ══ PAYMENT STAMP ══ --}}
            @php
                $stampVerifiedPaid = $verified_paid ?? 0;
                $stampOutstanding  = $outstanding_balance ?? floatval($invoice->total ?? 0);
                $isCancelled = $invoice->payment_status == 3;

                if ($isCancelled) {
                    $stamp = 'cancelled';
                } elseif ($invoice->payment_status == 1) { // Admin manually marked Paid
                    $stamp = 'paid';
                    $stampVerifiedPaid = $invoice->total;
                    $stampOutstanding = 0;
                } elseif ($invoice->payment_status == 4 && $stampVerifiedPaid <= 0) {
                    $stamp = 'partially_paid';
                } elseif ($stampVerifiedPaid <= 0) {
                    $stamp = 'unpaid';
                } elseif ($stampOutstanding > 0) {
                    $stamp = 'partially_paid';
                } else {
                    $stamp = 'paid';
                }
            @endphp
            <div class="text-right">
                @if($stamp === 'paid')
                    <div class="stamp-paid">
                        <span style="color:#28a745; font-size:18px; font-weight:900; letter-spacing:2px;">&#10004; PAID</span>
                    </div>
                @elseif($stamp === 'partially_paid')
                    <div class="stamp-partial">
                        <span style="color:#fd7e14; font-size:13px; font-weight:900; letter-spacing:1px;">PARTIALLY PAID</span>
                    </div>
                @elseif($stamp === 'cancelled')
                    <div class="stamp-cancelled">
                        <span style="color:#6c757d; font-size:15px; font-weight:900; letter-spacing:2px;">CANCELLED</span>
                    </div>
                @else
                    <div class="stamp-unpaid">
                        <span style="color:#dc3545; font-size:18px; font-weight:900; letter-spacing:2px;">UNPAID</span>
                    </div>
                @endif
            </div>
        </div>
        <hr style="border-color:#1a5c1a; border-width:1px; margin-bottom:20px;">

        {{-- ══ BILLING PARTIES ══ --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="font-weight-bold" style="color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:3px; display:inline-block;">INVOICE TO:</h6>
                <p class="mb-1 mt-2"><strong>{{ $invoice->client->company_name ?? $invoice->client->name ?? 'N/A' }}</strong></p>
                <p class="mb-1 text-muted" style="font-size:13px;">{{ $invoice->client->name ?? '' }}</p>
                @if($invoice->client->address ?? false)
                    <p class="mb-1" style="font-size:13px;">{{ $invoice->client->address }}</p>
                @endif
                @if($invoice->client->phone ?? false)
                    <p class="mb-1" style="font-size:13px;"><strong>Tel:</strong> {{ $invoice->client->phone }}</p>
                @endif
                <p class="mb-1" style="font-size:13px;"><strong>Email:</strong> {{ $invoice->client->email ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h6 class="font-weight-bold" style="color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:3px; display:inline-block;">EVENT DETAILS:</h6>
                <p class="mb-1 mt-2"><strong>{{ $invoice->event->name ?? 'Exhibition 2026' }}</strong></p>
                <p class="mb-1" style="font-size:13px;">{{ $invoice->event->venue ?? 'Exhibition Centre' }}</p>
            </div>
        </div>

        {{-- ══ LINE ITEMS ══ --}}
        <div class="table-responsive mb-3">
            <table class="table table-bordered mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Description</th>
                        <th class="text-center" style="width:80px;">Qty</th>
                        <th class="text-right" style="width:120px;">
                            Unit Price
                            @if($invoice->payment_currency == 2)
                                <small class="font-weight-normal text-warning">(USD)</small>
                            @endif
                        </th>
                        <th class="text-right" style="width:130px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ══ TOTALS ══ --}}
        <div class="row justify-content-end mb-4">
            <div class="col-md-5">
                <table class="table table-sm text-right">
                    <tr>
                        <th>VAT:</th>
                        <td>${{ number_format($invoice->vat, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Discount:</th>
                        <td class="text-danger">-${{ number_format($invoice->discount ?? 0, 2) }}</td>
                    </tr>
                    <tr class="font-weight-bold bg-light">
                        <th>Grand Total:</th>
                        <td class="text-dark">
                            @if($invoice->payment_currency == 2)
                                <small class="font-weight-normal text-muted">USD</small>
                            @endif
                            ${{ number_format($invoice->total, 2) }}
                        </td>
                    </tr>
                    @if($invoice->payment_currency == 2)
                    <tr style="background:#fff8e1;">
                        <td colspan="2" style="font-size:12px; color:#856404; padding:6px 8px;">
                            <strong>&#9432; ZWL Payment:</strong> Amounts are in <strong>USD</strong>. Payable in <strong>ZIG</strong> at the interbank rate on the date of payment.
                        </td>
                    </tr>
                    @endif
                    @if($stampVerifiedPaid > 0)
                    <tr class="text-success font-weight-bold">
                        <th>Verified Paid:</th>
                        <td>${{ number_format($stampVerifiedPaid, 2) }}</td>
                    </tr>
                    <tr class="{{ $stampOutstanding > 0 ? 'text-danger' : 'text-success' }} font-weight-bold">
                        <th>Outstanding Balance:</th>
                        <td>${{ number_format($stampOutstanding, 2) }}</td>
                    </tr>
                    @else
                    <tr class="text-danger font-weight-bold">
                        <th>Outstanding Balance:</th>
                        <td>${{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- ══ PAYMENT HISTORY ══ --}}
        @if($invoice->payments->count() > 0)
        <div class="mb-4">
            <h6 class="font-weight-bold" style="color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:3px;">PAYMENT HISTORY</h6>
            <table class="table table-sm table-bordered mt-2">
                <thead class="thead-light">
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th class="text-right">Claimed</th>
                        <th class="text-right">Verified</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $pmt)
                    <tr>
                        <td>{{ optional($pmt->created_at)->format('d M Y') }}</td>
                        <td>{{ $pmt->reference ?? 'N/A' }}</td>
                        <td class="text-right">${{ number_format($pmt->amount_claimed ?? $pmt->amount ?? 0, 2) }}</td>
                        <td class="text-right">
                            @if($pmt->status === 'verified')
                                <span class="text-success font-weight-bold">${{ number_format($pmt->amount_verified, 2) }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($pmt->status === 'verified')
                                <span class="badge badge-success">Verified</span>
                            @elseif($pmt->status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning text-dark">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ══ OFFICIAL BANKING DETAILS ══ --}}
        <div class="card mb-4 shadow-sm" style="border:1px solid #1a5c1a; border-radius:6px; overflow:hidden;">
            <div class="card-header font-weight-bold text-white" style="background:#1a5c1a;">
                <i class="fas fa-university mr-2"></i> Payment Instructions — Official Banking Details
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-7">
                        @if($invoice->payment_currency == 2)
                        {{-- ZWL / ZIG account --}}
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th class="bg-light" style="width:35%;">Account Name:</th>
                                <td class="font-weight-bold">Sports and Recreation</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Bank:</th>
                                <td class="font-weight-bold">EmpowerBank</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Number:</th>
                                <td class="font-weight-bold text-success" style="letter-spacing:1px;">900262824149</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Type:</th>
                                <td>ZWL / ZIG Account</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Currency:</th>
                                <td class="font-weight-bold">ZWL (ZIG)</td>
                            </tr>
                        </table>
                        @else
                        {{-- USD account (default) --}}
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th class="bg-light" style="width:35%;">Account Name:</th>
                                <td class="font-weight-bold">Sports and Recreation</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Bank:</th>
                                <td class="font-weight-bold">EmpowerBank</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Number:</th>
                                <td class="font-weight-bold text-success" style="letter-spacing:1px;">953869211833</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Type:</th>
                                <td>Corporate Nostro FCA (Domestic) USD</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Currency:</th>
                                <td class="font-weight-bold">USD</td>
                            </tr>
                        </table>
                        @endif
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0">
                        @if($invoice->payment_currency == 2)
                        <div class="alert alert-warning py-2 px-3 mb-2" style="font-size:13px;">
                            <strong>&#9432; ZWL Payment Note:</strong> Unit prices on this invoice are quoted in <strong>USD</strong>. Payment is to be made in <strong>ZIG</strong> at the prevailing <strong>interbank rate</strong> on the date of payment.
                        </div>
                        @endif
                        <p class="small text-muted mb-0">
                            After payment, upload your proof via the Exhibitor Portal or email
                            <strong>{{ $ministrySettings['app_email'] ?? 'mosrac@kuzana.org.zw' }}</strong> (CC: <strong>{{ $ministrySettings['app_email_cc'] ?? 'secretariat@kuzana.org.zw' }}</strong>).
                            Payments are confirmed only after Ministry Finance verification.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ ACTIONS ══ --}}
        <div class="d-flex justify-content-between flex-wrap gap-2 border-top pt-3 no-print">
            <a href="{{ route('customer.invoices.index') }}" class="btn btn-outline-secondary font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to Invoices
            </a>

            <div class="d-flex flex-wrap gap-2">
                @if(!$invoice->is_confirmed && $stamp !== 'paid' && $stamp !== 'cancelled')
                    {{-- CONFIRM BOOKING BUTTON --}}
                    <form method="POST" action="{{ route('customer.invoices.confirm', $invoice->id) }}" onsubmit="return confirm('Are you sure you want to confirm this booking? This action cannot be undone.')">
                        @csrf
                        <button type="submit" class="btn btn-warning font-weight-bold shadow">
                            <i class="fas fa-check-circle mr-1"></i> Confirm Booking
                        </button>
                    </form>
                @elseif($invoice->is_confirmed)
                    <span class="badge badge-success p-2" style="font-size:13px; align-self:center;">
                        <i class="fas fa-check-circle mr-1"></i> Booking Confirmed
                        @if($invoice->confirmed_at)
                            <small class="font-weight-normal">({{ optional($invoice->confirmed_at)->format('d M Y') }})</small>
                        @endif
                    </span>
                @endif

                @if($stamp !== 'paid' && $stamp !== 'cancelled')
                    @if($invoice->is_confirmed)
                        <a href="{{ route('customer.payments.index') }}" class="btn btn-success font-weight-bold shadow">
                            <i class="fas fa-credit-card mr-1"></i> Upload Payment Proof
                        </a>
                    @else
                        <button class="btn btn-success font-weight-bold shadow" disabled
                                title="You must confirm your booking before uploading payment proof">
                            <i class="fas fa-credit-card mr-1"></i> Upload Payment Proof
                            <small class="d-block font-weight-normal" style="font-size:10px;">(Confirm booking first)</small>
                        </button>
                    @endif
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
