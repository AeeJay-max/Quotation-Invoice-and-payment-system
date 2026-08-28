@extends('public-layout')

@section('title', 'Exhibition Quotation #' . ($quotation->quotation_number ?? $quotation->id))

@section('content')
<style>
    .invoice-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-top: 30px; margin-bottom: 50px; }
    .status-badge { font-size: 1rem; padding: 8px 16px; border-radius: 20px; font-weight: bold; }
    .ministry-header { border-bottom: 3px solid #1a5c1a; padding-bottom: 14px; margin-bottom: 18px; }
    @media print {
        .no-print { display: none !important; }
        .invoice-box { box-shadow: none; padding: 10px; margin: 0; }
    }
</style>

<div class="container">
    <div class="invoice-box">
        @if(session('success'))
            <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger font-weight-bold">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info font-weight-bold">{{ session('info') }}</div>
        @endif

        {{-- ══ MINISTRY OFFICIAL HEADER ══ --}}
        <div class="ministry-header d-flex align-items-center">
            <div class="mr-4" style="flex-shrink:0;">
                <img src="{{ asset($global_settings['logo'] ?? 'assets/files/ministry-logo.png') }}"
                     alt="Ministry Logo" style="width:90px; height:auto;">
            </div>
            <div style="flex:1;">
                <div style="font-size:16px; font-weight:700; color:#1a5c1a; text-transform:uppercase; letter-spacing:0.5px; line-height:1.3;">
                    {{ $global_settings['app_name'] ?? 'Ministry of Sports, Recreation, Arts and Culture' }}
                </div>
                <div style="font-size:12px; color:#555; margin-top:4px; line-height:1.8;">
                    {{ $global_settings['app_address'] ?? 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare' }}<br>
                    {{ $global_settings['app_postal_address'] ?? 'P.O. Box HR 480 Harare' }}<br>
                    <strong>Email:</strong> {{ $global_settings['app_email'] ?? 'minofsportandarts@gmail.com' }}
                    &nbsp;|&nbsp;
                    <strong>Tel:</strong> {{ $global_settings['app_phone'] ?? '+263242708345' }}
                </div>
            </div>
            <div class="text-right" style="flex-shrink:0; margin-left:20px;">
                @php
                    $statusDisplay = strtoupper($quotation->status);
                    if ($quotation->status === 'pending') $statusDisplay = 'PENDING ADMIN APPROVAL';
                    if ($quotation->status === 'approved') $statusDisplay = 'APPROVED — ACTION REQUIRED';
                    if ($quotation->status === 'rejected') $statusDisplay = 'REJECTED';
                    if ($quotation->status === 'accepted' || $quotation->status === 'confirmed') $statusDisplay = 'CONFIRMED';
                    $statusClass = match($quotation->status) {
                        'approved' => 'badge-success',
                        'rejected' => 'badge-danger',
                        'accepted', 'confirmed' => 'badge-info',
                        default => 'badge-warning text-dark',
                    };
                @endphp
                <span class="badge {{ $statusClass }} status-badge">{{ $statusDisplay }}</span>
            </div>
        </div>

        {{-- ══ DOCUMENT TITLE ══ --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold text-dark mb-0" style="text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-file-invoice text-success mr-2"></i> Exhibition Quotation
                </h4>
                <small class="text-muted">Reference: <strong>{{ $quotation->quotation_number ?? 'QUO-'.$quotation->id }}</strong>
                &nbsp;|&nbsp; Date: <strong>{{ optional($quotation->create_date)->format('d M Y') }}</strong></small>
            </div>
        </div>
        <hr style="border-color:#1a5c1a; border-width:1px;">

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="font-weight-bold" style="color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:4px;">QUOTATION TO:</h5>
                <p class="mb-1"><strong>{{ $quotation->client->company_name ?? $quotation->client->name ?? 'N/A' }}</strong></p>
                <p class="mb-1">{{ $quotation->client->name ?? '' }}</p>
                <p class="mb-1">{{ $quotation->client->address ?? '' }}</p>
                @if($quotation->client->phone ?? false)<p class="mb-1"><strong>Tel:</strong> {{ $quotation->client->phone }}</p>@endif
                <p class="mb-1"><strong>Email:</strong> {{ $quotation->client->email ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h5 class="font-weight-bold" style="color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:4px;">EVENT DETAILS:</h5>
                <p class="mb-1"><strong>Event:</strong> {{ $quotation->event->name ?? 'Exhibition 2026' }}</p>
                <p class="mb-1"><strong>Venue:</strong> {{ $quotation->event->venue ?? 'Exhibition Centre' }}</p>
                <p class="mb-1"><strong>Dates:</strong> {{ optional($quotation->event->start_date)->format('d M Y') }} - {{ optional($quotation->event->end_date)->format('d M Y') }}</p>
                <p class="mb-1"><strong>Currency:</strong> {{ $quotation->event->currency ?? 'USD' }}</p>
            </div>
        </div>

        <h5 class="font-weight-bold text-secondary mb-3">BOOKED ITEMS &amp; BREAKDOWN</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-items-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
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

        <div class="row justify-content-end mb-4">
            <div class="col-md-5">
                <table class="table table-sm text-right">
                    <tr>
                        <th>Subtotal:</th>
                        <td>${{ number_format($quotation->subtotal, 2) }}</td>
                    </tr>
                    @if($quotation->discount > 0)
                        <tr>
                            <th>Discount:</th>
                            <td class="text-danger">-${{ number_format($quotation->discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>VAT:</th>
                        <td>${{ number_format($quotation->vat, 2) }}</td>
                    </tr>
                    <tr class="h4 font-weight-bold bg-light">
                        <th>Grand Total:</th>
                        <td class="text-success">${{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ══ OFFICIAL BANKING DETAILS ══ --}}
        <div class="card mb-4 shadow-sm" style="border:1px solid #1a5c1a; border-radius:6px; overflow:hidden;">
            <div class="card-header font-weight-bold text-white" style="background:#1a5c1a;">
                <i class="fas fa-university mr-2"></i> Payment Instructions — Official Banking Details
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-7">
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
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0">
                        <div class="alert alert-warning p-3 mb-0">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>PAYMENT REFERENCE:</strong><br>
                            Use this reference when making payment:
                            <strong class="d-block h5 font-weight-bold text-dark mt-1 mb-0">{{ $quotation->quotation_number ?? 'QUO-'.$quotation->id }}</strong>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            After payment, upload proof via the Exhibitor Portal or email
                            <strong>{{ $global_settings['app_email'] ?? 'minofsportandarts@gmail.com' }}</strong>.
                            Payment is only confirmed after Ministry Finance verification.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($quotation->terms_condition)
            <div class="alert alert-light border mb-4">
                <h6 class="font-weight-bold text-dark"><i class="fas fa-gavel"></i> Terms &amp; Conditions</h6>
                <p class="small text-muted mb-0">{!! nl2br(e($quotation->terms_condition)) !!}</p>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center pt-3 border-top no-print">
            <a href="javascript:window.print()" class="btn btn-outline-secondary">
                <i class="fas fa-print mr-1"></i> Print / Save PDF
            </a>

            @if($quotation->status === 'approved')
                <form action="{{ route('public.quotation.confirm', $quotation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg font-weight-bold px-5 shadow">
                        <i class="fas fa-check-circle mr-2"></i> CONFIRM APPROVED QUOTATION
                    </button>
                </form>
            @elseif($quotation->status === 'accepted' || $quotation->status === 'confirmed')
                <a href="{{ route('customer.dashboard') }}" class="btn btn-primary btn-lg font-weight-bold px-4">
                    <i class="fas fa-user-shield mr-2"></i> Go to Customer Portal
                </a>
            @endif
        </div>

    </div>
</div>

@endsection
