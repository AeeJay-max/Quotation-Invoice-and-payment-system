@extends('layout')

@section('title', 'Verify Payment #' . $payment->id)

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-check-double text-primary mr-2"></i>Payment Verification
                        <span class="badge badge-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'warning') }} ml-2">
                            {{ $payment->status_label }}
                        </span>
                    </h1>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Payments
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

            <div class="row">

                {{-- ── LEFT COLUMN: Payment Info + Proof ──────────────────────── --}}
                <div class="col-lg-7">

                    {{-- Exhibitor & Document Info --}}
                    <div class="card card-outline card-info shadow-sm mb-3">
                        <div class="card-header"><h5 class="card-title font-weight-bold mb-0"><i class="fas fa-user mr-2"></i>Exhibitor & Document Details</h5></div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="40%" class="text-muted">Exhibitor / Company:</th>
                                    <td><strong>{{ optional($payment->client)->name ?? 'N/A' }}</strong>
                                        @if(optional($payment->client)->company_name)
                                            <br><small class="text-muted">{{ $payment->client->company_name }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Event:</th>
                                    <td>{{ optional(optional($payment->booking)->event)->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Booking:</th>
                                    <td>{{ optional($payment->booking)->booking_number ?? ('BOOK-' . $payment->booking_id) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Invoice:</th>
                                    <td><span class="badge badge-info">{{ optional($payment->invoice)->invoice_number ?? 'INV-'.$payment->invoice_id }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Quotation:</th>
                                    <td>{{ $payment->quotation_number ?? optional($payment->quotation)->quotation_number ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Invoice Financial Summary --}}
                    @if($invoice)
                    <div class="card card-outline card-secondary shadow-sm mb-3">
                        <div class="card-header"><h5 class="card-title font-weight-bold mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i>Invoice Financial Summary</h5></div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th>Invoice Total:</th>
                                    <td class="text-right font-weight-bold">{{ $payment->currency }} {{ number_format(floatval($invoice->total), 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Previously Verified & Paid:</th>
                                    <td class="text-right text-success font-weight-bold">{{ $payment->currency }} {{ number_format($previouslyVerified, 2) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <th>Outstanding Before This Payment:</th>
                                    <td class="text-right font-weight-bold text-danger">
                                        {{ $payment->currency }} {{ number_format(max(0, floatval($invoice->total) - $previouslyVerified), 2) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Payment Submission Details --}}
                    <div class="card card-outline card-warning shadow-sm mb-3">
                        <div class="card-header"><h5 class="card-title font-weight-bold mb-0"><i class="fas fa-paper-plane mr-2"></i>Payment Submission</h5></div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th>Claimed Amount by Exhibitor:</th>
                                    <td class="text-right font-weight-bold text-warning" style="font-size:1.1rem;">
                                        {{ $payment->currency }} {{ number_format(floatval($payment->amount_claimed ?? $payment->amount), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Transaction Reference:</th>
                                    <td class="text-right">{{ $payment->transaction_reference ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td class="text-right">{{ $payment->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Submitted Date:</th>
                                    <td class="text-right">{{ optional($payment->payment_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Exhibitor Notes:</th>
                                    <td class="text-right">{{ $payment->notes ?? '—' }}</td>
                                </tr>
                                @if($payment->status === 'verified')
                                <tr class="table-success">
                                    <th>Admin Verified Amount:</th>
                                    <td class="text-right font-weight-bold text-success" style="font-size:1.1rem;">
                                        {{ $payment->currency }} {{ number_format(floatval($payment->amount_verified), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Verified By:</th>
                                    <td class="text-right">{{ optional($payment->verifiedBy)->name ?? 'Admin' }} — {{ optional($payment->verified_at)->format('d M Y H:i') }}</td>
                                </tr>
                                @elseif($payment->status === 'rejected')
                                <tr class="table-danger">
                                    <th>Rejection Reason:</th>
                                    <td class="text-right text-danger">{{ $payment->rejection_reason }}</td>
                                </tr>
                                <tr>
                                    <th>Rejected By:</th>
                                    <td class="text-right">{{ optional($payment->rejectedBy)->name ?? 'Admin' }} — {{ optional($payment->rejected_at)->format('d M Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- Proof of Payment --}}
                    <div class="card card-outline card-dark shadow-sm mb-3">
                        <div class="card-header">
                            <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-file-upload mr-2"></i>Proof of Payment</h5>
                        </div>
                        <div class="card-body">
                            @if($payment->proof_of_payment_path)
                                @php
                                    $ext = strtolower(pathinfo($payment->proof_of_payment_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isPdf   = $ext === 'pdf';
                                    $proofUrl = route('admin.payments.proof', $payment->id);
                                @endphp

                                <div class="mb-2">
                                    <a href="{{ $proofUrl }}" target="_blank" class="btn btn-outline-dark btn-sm mr-2">
                                        <i class="fas fa-external-link-alt mr-1"></i>Open in New Tab
                                    </a>
                                    <a href="{{ $proofUrl }}" download class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>

                                @if($isImage)
                                    <div class="text-center border rounded p-2 bg-light">
                                        <img src="{{ $proofUrl }}" alt="Proof of Payment"
                                             style="max-width:100%; max-height:500px; object-fit:contain; cursor:pointer;"
                                             onclick="window.open('{{ $proofUrl }}','_blank')"
                                             title="Click to open full size">
                                    </div>
                                    <small class="text-muted">Click image to open full size.</small>
                                @elseif($isPdf)
                                    <div class="border rounded bg-light" style="height:500px;">
                                        <iframe src="{{ $proofUrl }}" width="100%" height="500" frameborder="0">
                                            <p>Your browser cannot display PDFs.
                                               <a href="{{ $proofUrl }}" target="_blank">Open PDF</a></p>
                                        </iframe>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-file mr-2"></i>
                                        Document type: <strong>{{ strtoupper($ext) }}</strong>.
                                        <a href="{{ $proofUrl }}" target="_blank">Open/Download File</a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>No proof of payment was uploaded with this submission.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- ── RIGHT COLUMN: Verification Action ──────────────────────── --}}
                <div class="col-lg-5">

                    @if($payment->status === 'verified')
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Payment Already Verified</h5>
                            <p class="mb-0">This payment was verified by <strong>{{ optional($payment->verifiedBy)->name ?? 'Admin' }}</strong>
                            on {{ optional($payment->verified_at)->format('d M Y \a\t H:i') }}.</p>
                            <hr>
                            <p class="mb-0">Verified Amount: <strong>{{ $payment->currency }} {{ number_format(floatval($payment->amount_verified), 2) }}</strong></p>
                        </div>

                    @elseif($payment->status === 'rejected')
                        <div class="alert alert-danger">
                            <h5><i class="fas fa-times-circle"></i> Payment Rejected</h5>
                            <p><strong>Reason:</strong> {{ $payment->rejection_reason }}</p>
                            <p class="mb-0">Rejected by <strong>{{ optional($payment->rejectedBy)->name ?? 'Admin' }}</strong>
                            on {{ optional($payment->rejected_at)->format('d M Y \a\t H:i') }}.</p>
                        </div>

                    @else
                        {{-- ── VERIFICATION FORM ────────────────────────────────── --}}
                        @php
                            $claimedAmt      = floatval($payment->amount_claimed ?? $payment->amount);
                            $invoiceTotal    = floatval(optional($invoice)->total);
                            $newTotalIfVerifyFull = $previouslyVerified + $claimedAmt;
                            $newOutstandingIfFull = max(0, $invoiceTotal - $newTotalIfVerifyFull);
                        @endphp

                        <div class="card card-outline card-success shadow-sm mb-3">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title font-weight-bold mb-0">
                                    <i class="fas fa-check-double mr-2"></i>Admin Verification
                                </h5>
                            </div>
                            <div class="card-body">

                                {{-- Live Preview Summary --}}
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-bordered">
                                        <tr class="table-light">
                                            <th>Invoice Total:</th>
                                            <td class="text-right font-weight-bold">{{ $payment->currency }} {{ number_format($invoiceTotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Previously Verified Paid:</th>
                                            <td class="text-right text-success">{{ $payment->currency }} {{ number_format($previouslyVerified, 2) }}</td>
                                        </tr>
                                        <tr class="table-warning">
                                            <th>Exhibitor Claimed This Payment:</th>
                                            <td class="text-right font-weight-bold">{{ $payment->currency }} {{ number_format($claimedAmt, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" id="verifyForm">
                                    @csrf

                                    <div class="form-group">
                                        <label class="font-weight-bold text-success">Admin Verified Amount ({{ $payment->currency }})</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number"
                                                   name="verified_amount"
                                                   id="verified_amount"
                                                   class="form-control form-control-lg font-weight-bold"
                                                   value="{{ number_format($claimedAmt, 2, '.', '') }}"
                                                   step="0.01"
                                                   min="0"
                                                   max="{{ $invoiceTotal }}"
                                                   required>
                                        </div>
                                        <small class="text-muted">
                                            Change this if the proof shows a different amount to what the exhibitor claimed.
                                        </small>
                                    </div>

                                    {{-- Live recalculation preview --}}
                                    <div class="card bg-light border mb-3">
                                        <div class="card-body p-3">
                                            <p class="mb-1 text-muted small">LIVE PREVIEW (updates as you type):</p>
                                            <table class="table table-sm mb-0">
                                                <tr>
                                                    <td>Claimed by Exhibitor:</td>
                                                    <td class="text-right">
                                                        <span id="preview_claimed">{{ $payment->currency }} {{ number_format($claimedAmt, 2) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Admin Verified:</td>
                                                    <td class="text-right font-weight-bold text-success" id="preview_verified">
                                                        {{ $payment->currency }} {{ number_format($claimedAmt, 2) }}
                                                    </td>
                                                </tr>
                                                <tr id="diff-row" style="display:none;">
                                                    <td>Difference:</td>
                                                    <td class="text-right text-danger font-weight-bold" id="preview_diff"></td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td><strong>New Total Verified Paid:</strong></td>
                                                    <td class="text-right font-weight-bold text-success" id="preview_total">
                                                        {{ $payment->currency }} {{ number_format($previouslyVerified + $claimedAmt, 2) }}
                                                    </td>
                                                </tr>
                                                <tr class="table-danger">
                                                    <td><strong>New Outstanding Balance:</strong></td>
                                                    <td class="text-right font-weight-bold text-danger" id="preview_outstanding">
                                                        {{ $payment->currency }} {{ number_format(max(0, $invoiceTotal - $previouslyVerified - $claimedAmt), 2) }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block font-weight-bold"
                                            onclick="return confirm('Verify payment of ' + document.getElementById('verified_amount').value + '? This will update the invoice balance.')">
                                        <i class="fas fa-check-circle mr-2"></i>VERIFY &amp; CONFIRM PAYMENT
                                    </button>
                                </form>

                            </div>
                        </div>

                        {{-- ── REJECTION FORM ───────────────────────────────────── --}}
                        <div class="card card-outline card-danger shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title font-weight-bold mb-0 text-danger">
                                    <i class="fas fa-times-circle mr-2"></i>Reject Payment
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" id="rejectForm">
                                    @csrf
                                    <div class="form-group">
                                        <label class="font-weight-bold text-danger">Rejection Reason <span class="text-danger">*</span></label>
                                        <textarea name="rejection_reason" class="form-control" rows="3"
                                                  placeholder="e.g. Payment amount shown on proof could not be verified." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-block font-weight-bold"
                                            onclick="return confirm('Reject this payment? The invoice balance will NOT be updated.')">
                                        <i class="fas fa-times-circle mr-2"></i>REJECT PAYMENT
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var currency    = '{{ $payment->currency }}';
    var prevVerified = {{ $previouslyVerified }};
    var invoiceTotal = {{ floatval(optional($invoice)->total) }};
    var claimed      = {{ floatval($payment->amount_claimed ?? $payment->amount) }};

    var input = document.getElementById('verified_amount');
    if (!input) return;

    function fmt(n) {
        return currency + ' ' + parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    input.addEventListener('input', function() {
        var verified    = parseFloat(this.value) || 0;
        var newTotal    = prevVerified + verified;
        var outstanding = Math.max(0, invoiceTotal - newTotal);
        var diff        = claimed - verified;

        document.getElementById('preview_verified').textContent    = fmt(verified);
        document.getElementById('preview_total').textContent       = fmt(newTotal);
        document.getElementById('preview_outstanding').textContent = fmt(outstanding);

        var diffRow = document.getElementById('diff-row');
        if (Math.abs(diff) > 0.001) {
            diffRow.style.display = '';
            document.getElementById('preview_diff').textContent = '−' + fmt(Math.abs(diff));
        } else {
            diffRow.style.display = 'none';
        }
    });

    // Prevent double-click on verify form
    document.getElementById('verifyForm').addEventListener('submit', function() {
        var btn = this.querySelector('button[type=submit]');
        btn.disabled = true;
        btn.textContent = 'Processing...';
    });
})();
</script>
@endpush
