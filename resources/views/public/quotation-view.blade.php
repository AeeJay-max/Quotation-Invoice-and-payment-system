<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibition Quotation #{{ $quotation->quotation_number ?? $quotation->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .invoice-box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-top: 30px; margin-bottom: 50px; }
        .header-bg { background: #1e3c72; color: #fff; border-radius: 8px; padding: 20px; }
        .status-badge { font-size: 1rem; padding: 8px 16px; border-radius: 20px; font-weight: bold; }
    </style>
</head>
<body>

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

        <div class="header-bg mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 font-weight-bold"><i class="fas fa-file-invoice text-warning"></i> EXHIBITION QUOTATION</h2>
                <p class="mb-0 text-white-50">Reference: {{ $quotation->quotation_number ?? 'QUO-'.$quotation->id }}</p>
            </div>
            <div>
                <span class="badge badge-warning status-badge text-uppercase">{{ $quotation->status }}</span>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="font-weight-bold text-primary">EXHIBITION ORGANIZER</h5>
                <p class="mb-1"><strong>Event:</strong> {{ $quotation->event->name ?? 'Exhibition 2026' }}</p>
                <p class="mb-1"><strong>Venue:</strong> {{ $quotation->event->venue ?? 'Exhibition Centre' }}</p>
                <p class="mb-1"><strong>Dates:</strong> {{ optional($quotation->event->start_date)->format('d M Y') }} - {{ optional($quotation->event->end_date)->format('d M Y') }}</p>
                <p class="mb-1"><strong>Currency:</strong> {{ $quotation->event->currency ?? 'USD' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <h5 class="font-weight-bold text-primary">EXHIBITOR / CLIENT</h5>
                <p class="mb-1"><strong>Company:</strong> {{ $quotation->client->company_name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Contact:</strong> {{ $quotation->client->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $quotation->client->email ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $quotation->client->phone ?? 'N/A' }}</p>
            </div>
        </div>

        <h5 class="font-weight-bold text-secondary mb-3">BOOKED ITEMS & BREAKDOWN</h5>
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
                        <th>VAT (15%):</th>
                        <td>${{ number_format($quotation->vat, 2) }}</td>
                    </tr>
                    <tr class="h4 font-weight-bold bg-light">
                        <th>Grand Total:</th>
                        <td class="text-success">${{ number_format($quotation->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Official Banking Details & Payment Instruction Card -->
        <div class="card border-warning mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark font-weight-bold">
                <i class="fas fa-university mr-2"></i> Official Banking Details & Payment Instructions
            </div>
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <table class="table table-bordered table-sm mb-0">
                            <tr>
                                <th class="bg-light" style="width: 35%;">Bank Name:</th>
                                <td class="font-weight-bold text-primary">{{ $bankDetails['bank'] ?? 'Standard Chartered Bank' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Branch:</th>
                                <td>{{ $bankDetails['branch'] ?? 'Main Branch' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Number:</th>
                                <td class="font-weight-bold text-dark">{{ $bankDetails['acc_number'] ?? '109283746501' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Account Name:</th>
                                <td>{{ $bankDetails['acc_name'] ?? 'Invoice & Quotation System Ltd' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-5 mt-3 mt-md-0 border-left pl-md-4">
                        <div class="alert alert-danger p-2 text-center mb-0">
                            <i class="fas fa-exclamation-triangle mr-1"></i> <strong>PAYMENT REFERENCE REQUIREMENT:</strong><br>
                            When making a bank transfer, you MUST use this Quotation Number:
                            <strong class="d-block h5 font-weight-bold text-dark mt-1 mb-0">{{ $quotation->quotation_number ?? 'QUO-'.$quotation->id }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($quotation->terms_condition)
            <div class="alert alert-light border mb-4">
                <h6 class="font-weight-bold text-dark"><i class="fas fa-gavel"></i> Terms & Conditions</h6>
                <p class="small text-muted mb-0">{!! nl2br(e($quotation->terms_condition)) !!}</p>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
            <a href="javascript:window.print()" class="btn btn-outline-secondary">
                <i class="fas fa-print mr-1"></i> Download / Print PDF
            </a>

            @if($quotation->status !== 'accepted')
                <form action="{{ route('public.quotation.confirm', $quotation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg font-weight-bold px-5 shadow">
                        <i class="fas fa-check-circle mr-2"></i> CONFIRM QUOTATION
                    </button>
                </form>
            @else
                <a href="{{ route('customer.dashboard') }}" class="btn btn-primary btn-lg font-weight-bold px-4">
                    <i class="fas fa-user-shield mr-2"></i> Go to Customer Portal
                </a>
            @endif
        </div>

    </div>
</div>

</body>
</html>
