@extends('layout')

@section('title', 'Payment Verifications')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-money-check-alt text-primary mr-2"></i>Payment Verifications
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="/dashboard" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>
            @endif

            {{-- ── SUMMARY CARDS ──────────────────────────────────────── --}}
            <div class="row mb-4">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total Submissions</p></div>
                        <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <a href="#pending-table">
                        <div class="small-box bg-warning">
                            <div class="inner"><h3>{{ $summary['pending'] }}</h3><p>Pending Verification</p></div>
                            <div class="icon"><i class="fas fa-clock"></i></div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner"><h3>{{ $summary['verified'] }}</h3><p>Verified</p></div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>{{ $summary['rejected'] }}</h3><p>Rejected</p></div>
                        <div class="icon"><i class="fas fa-times-circle"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>USD {{ number_format($summary['total_verified_amount'], 2) }}</h3>
                            <p>Total Verified Amount</p>
                        </div>
                        <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>

            {{-- ── PENDING SUBMISSIONS (highlighted) ──────────────────── --}}
            @php $pendingPayments = $payments->whereIn('status', ['submitted', 'pending']); @endphp
            @if($pendingPayments->count())
            <div class="card card-outline card-warning shadow mb-4" id="pending-table">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                        Pending Verification ({{ $pendingPayments->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th><th>Exhibitor</th><th>Event</th>
                                    <th>Invoice</th><th>Quotation</th>
                                    <th>Claimed</th><th>Inv. Total</th><th>Outstanding</th>
                                    <th>Reference</th><th>Submitted</th><th>Proof</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPayments as $pay)
                                <tr>
                                    <td>{{ $pay->id }}</td>
                                    <td>
                                        <strong>{{ optional($pay->client)->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ optional($pay->client)->company_name }}</small>
                                    </td>
                                    <td><small>{{ optional(optional($pay->booking)->event)->name ?? 'N/A' }}</small></td>
                                    <td><span class="badge badge-info">{{ optional($pay->invoice)->invoice_number ?? 'N/A' }}</span></td>
                                    <td><small>{{ $pay->quotation_number ?? 'N/A' }}</small></td>
                                    <td class="font-weight-bold text-warning">
                                        {{ $pay->currency }} {{ number_format(floatval($pay->amount_claimed ?? $pay->amount), 2) }}
                                    </td>
                                    <td>{{ $pay->currency }} {{ number_format(floatval(optional($pay->invoice)->total), 2) }}</td>
                                    <td>{{ $pay->currency }} {{ number_format(floatval(optional($pay->invoice)->amount_outstanding), 2) }}</td>
                                    <td><small>{{ $pay->transaction_reference }}</small></td>
                                    <td><small>{{ optional($pay->payment_date)->format('d M Y') }}</small></td>
                                    <td>
                                        @if($pay->proof_of_payment_path)
                                            <a href="{{ route('admin.payments.proof', $pay->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @else
                                            <span class="text-muted small">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.payments.show', $pay->id) }}" class="btn btn-sm btn-warning font-weight-bold">
                                            <i class="fas fa-check-double mr-1"></i>Verify
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── ALL PAYMENTS TABLE ──────────────────────────────────── --}}
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-list mr-2"></i>All Payment Submissions
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="paymentsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th><th>Exhibitor</th><th>Invoice</th>
                                    <th>Claimed</th><th>Verified</th>
                                    <th>Reference</th><th>Submitted</th>
                                    <th>Status</th><th>Proof</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $pay)
                                <tr>
                                    <td>{{ $pay->id }}</td>
                                    <td>
                                        <strong>{{ optional($pay->client)->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ optional($pay->client)->company_name }}</small>
                                    </td>
                                    <td><span class="badge badge-info">{{ optional($pay->invoice)->invoice_number ?? 'N/A' }}</span></td>
                                    <td class="font-weight-bold">
                                        {{ $pay->currency }} {{ number_format(floatval($pay->amount_claimed ?? $pay->amount), 2) }}
                                    </td>
                                    <td>
                                        @if($pay->status === 'verified')
                                            <span class="text-success font-weight-bold">
                                                {{ $pay->currency }} {{ number_format(floatval($pay->amount_verified), 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $pay->transaction_reference }}</small></td>
                                    <td><small>{{ optional($pay->payment_date)->format('d M Y') }}</small></td>
                                    <td>
                                        @php
                                            $badgeClass = match($pay->status) {
                                                'verified' => 'badge-success',
                                                'rejected' => 'badge-danger',
                                                default    => 'badge-warning',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $pay->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($pay->proof_of_payment_path)
                                            <a href="{{ route('admin.payments.proof', $pay->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.payments.show', $pay->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-search mr-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="10" class="text-center text-muted py-4">No payment submissions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
