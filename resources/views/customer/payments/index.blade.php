@extends('customer-layout')

@section('title', 'Payments & Proof Upload')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-credit-card text-success mr-2"></i> Invoices &amp; Payment Submissions</h2>
            <p class="text-muted mb-0">Track your invoice balances and submit proof of payments for event bookings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger font-weight-bold alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── OFFICIAL MINISTRY BANKING DETAILS ──────────────────────────── --}}
    <div class="card card-outline card-warning shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-university text-warning mr-2"></i> Official Ministry Banking Details
            </h5>
        </div>
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block small">Account Name:</span>
                            <strong class="text-dark">{{ $bankSettings['acc_name'] ?? 'Sports and Recreation' }}</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block small">Bank:</span>
                            <strong class="text-primary">{{ $bankSettings['bank'] ?? 'EmpowerBank' }}</strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block small">Account Number:</span>
                            <strong class="text-dark font-weight-bold" style="font-size:1.05rem; letter-spacing:1px;">
                                {{ $bankSettings['acc_number'] ?? '953869211833' }}
                            </strong>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <span class="text-muted d-block small">Account Type:</span>
                            <strong>{{ $bankSettings['acc_type'] ?? 'Corporate Nostro FCA (Domestic) USD' }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Currency:</span>
                            <strong>{{ $bankSettings['acc_currency'] ?? 'USD' }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Branch:</span>
                            <strong>{{ $bankSettings['branch'] ?? 'Main Branch' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                    <div class="alert alert-warning mb-0 p-2 text-center">
                        <i class="fas fa-exclamation-circle text-danger mr-1"></i>
                        <strong>Mandatory Reference:</strong><br>
                        Use your <strong>Quotation Number</strong> (e.g. <code>QUO-2026-XXXXXX</code>) as your bank transfer reference.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── INVOICE BALANCES ─────────────────────────────────────────────── --}}
    @if($invoices->count())
    <div class="card card-outline card-info shadow-sm mb-4">
        <div class="card-header">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-file-invoice-dollar mr-2"></i>My Invoice Balances
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Quotation #</th>
                            <th>Invoice Total</th>
                            <th>Verified Paid</th>
                            <th>Outstanding Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $inv)
                        @php
                            $verifiedPaid = \App\Models\Payment::where('invoice_id', $inv->id)
                                ->where('status', 'verified')
                                ->sum('amount_verified');
                            $outstanding = max(0, floatval($inv->total) - $verifiedPaid);
                        @endphp
                        <tr>
                            <td><span class="badge badge-info">{{ $inv->invoice_number }}</span></td>
                            <td><small>{{ optional($inv->quotation)->quotation_number ?? 'N/A' }}</small></td>
                            <td class="font-weight-bold">USD {{ number_format(floatval($inv->total), 2) }}</td>
                            <td class="text-success font-weight-bold">USD {{ number_format($verifiedPaid, 2) }}</td>
                            <td class="font-weight-bold {{ $outstanding > 0 ? 'text-danger' : 'text-success' }}">
                                USD {{ number_format($outstanding, 2) }}
                                @if($outstanding <= 0)
                                    <span class="badge badge-success ml-1">PAID IN FULL</span>
                                @endif
                            </td>
                            <td>
                                @if($outstanding <= 0)
                                    <span class="badge badge-success">Paid</span>
                                @elseif($verifiedPaid > 0)
                                    <span class="badge badge-warning">Partially Paid</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        {{-- ── SUBMIT PROOF FORM ─────────────────────────────────────────── --}}
        <div class="col-lg-5 mb-4">
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-upload mr-2"></i> Submit Proof of Payment
                    </h5>
                </div>
                <form action="{{ route('customer.payments.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="alert alert-info p-2 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            <small>After submitting, your payment will show as <strong>Pending Admin Verification</strong>.
                            The Ministry will verify your proof and update your balance accordingly.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Select Invoice <span class="text-danger">*</span></label>
                            <select name="invoice_id" id="invoice_id" class="form-control" required>
                                <option value="">— Choose Invoice —</option>
                                @foreach($invoices as $inv)
                                    <option value="{{ $inv->id }}"
                                        data-quotation="{{ optional($inv->quotation)->quotation_number ?? '' }}">
                                        {{ $inv->invoice_number }}
                                        (Outstanding: USD {{ number_format(floatval($inv->amount_outstanding), 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Quotation Reference Number <span class="text-danger">*</span></label>
                            <input type="text" name="quotation_number" id="quotation_number"
                                   class="form-control font-weight-bold text-primary"
                                   placeholder="QUO-2026-XXXXXX" required>
                            <small class="form-text text-muted">Auto-filled when you select an invoice. Use the exact number from your quotation document.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Amount You Are Claiming to Have Paid (USD) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="0.00" required>
                            </div>
                            <small class="text-muted">Enter the amount shown on your proof of payment.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Bank Transfer">Bank Transfer / Telegraphic Transfer</option>
                                <option value="Ecocash">Ecocash / Mobile Money</option>
                                <option value="Credit Card">Credit / Debit Card</option>
                                <option value="Cash">Cash Deposit</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Transaction Reference / Deposit Slip # <span class="text-danger">*</span></label>
                            <input type="text" name="transaction_reference" class="form-control"
                                   placeholder="e.g. TXN-98765432 or Deposit Slip #" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Attach Proof of Payment (PDF / JPG / PNG) <span class="text-danger">*</span></label>
                            <input type="file" name="proof_file" class="form-control-file"
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Max 10MB. Upload your bank receipt, transfer confirmation, or deposit slip.</small>
                        </div>

                        <div class="form-group">
                            <label>Additional Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Any additional information..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success font-weight-bold btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Payment Proof for Verification
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── PAYMENT HISTORY TABLE ─────────────────────────────────────── --}}
        <div class="col-lg-7 mb-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-history mr-2"></i> Payment Submission History
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice #</th>
                                    <th>Claimed</th>
                                    <th>Verified</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Proof</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $pay)
                                    <tr class="{{ $pay->status === 'rejected' ? 'table-danger' : ($pay->status === 'verified' ? 'table-success' : '') }}">
                                        <td><small>{{ optional($pay->payment_date)->format('d M Y') }}</small></td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ optional($pay->invoice)->invoice_number ?? 'INV-'.$pay->invoice_id }}
                                            </span>
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ $pay->currency }} {{ number_format(floatval($pay->amount_claimed ?? $pay->amount), 2) }}
                                        </td>
                                        <td>
                                            @if($pay->status === 'verified')
                                                <span class="font-weight-bold text-success">
                                                    {{ $pay->currency }} {{ number_format(floatval($pay->amount_verified), 2) }}
                                                </span>
                                                @if(abs(floatval($pay->amount_verified) - floatval($pay->amount_claimed ?? $pay->amount)) > 0.01)
                                                    <br><small class="text-muted">
                                                        (Claimed: {{ number_format(floatval($pay->amount_claimed ?? $pay->amount), 2) }})
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="d-block font-weight-bold">{{ $pay->payment_method }}</small>
                                            <small class="text-muted">{{ $pay->transaction_reference }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $pay->status === 'verified' ? 'success' : ($pay->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ $pay->status_label }}
                                            </span>
                                            @if($pay->status === 'rejected' && $pay->rejection_reason)
                                                <br><small class="text-danger d-block mt-1">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ Str::limit($pay->rejection_reason, 60) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pay->proof_of_payment_path)
                                                <a href="{{ route('customer.payments.proof', $pay->id) }}"
                                                   target="_blank"
                                                   class="btn btn-xs btn-outline-secondary"
                                                   title="View your proof">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No payment submissions yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Explanation of statuses --}}
            <div class="card card-body bg-light border mt-3 p-3">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-info-circle text-info mr-1"></i> Payment Status Guide</h6>
                <div class="row">
                    <div class="col-sm-4 mb-1">
                        <span class="badge badge-warning mr-1">Pending Admin Verification</span>
                        <small class="text-muted d-block">Awaiting Ministry review</small>
                    </div>
                    <div class="col-sm-4 mb-1">
                        <span class="badge badge-success mr-1">Payment Verified</span>
                        <small class="text-muted d-block">Ministry confirmed receipt</small>
                    </div>
                    <div class="col-sm-4 mb-1">
                        <span class="badge badge-danger mr-1">Payment Rejected</span>
                        <small class="text-muted d-block">Please resubmit with correct proof</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-fill quotation number when invoice is selected
document.getElementById('invoice_id').addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var qNum = selected.getAttribute('data-quotation');
    if (qNum) {
        document.getElementById('quotation_number').value = qNum;
    }
});
// Fire on page load for first option
var first = document.getElementById('invoice_id').options[1];
if (first) {
    var qNum = first.getAttribute('data-quotation');
    if (qNum) document.getElementById('quotation_number').value = qNum;
}
</script>
@endpush
@endsection
