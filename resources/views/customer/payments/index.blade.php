@extends('layout')

@section('title', 'Payments & Proof Upload')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-credit-card text-success mr-2"></i> Invoices & Payment Submissions</h2>
            <p class="text-muted mb-0">Track your invoice balances and submit proof of payments for event bookings.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger font-weight-bold">{{ session('error') }}</div>
    @endif

    <!-- Banking Details Box -->
    <div class="card card-outline card-warning shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title font-weight-bold text-dark mb-0"><i class="fas fa-university text-warning mr-2"></i> Official Banking Details</h5>
        </div>
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-6 mb-2"><span class="text-muted d-block small">Bank:</span><strong class="text-primary">{{ $bankSettings['bank'] ?? 'Standard Chartered Bank' }}</strong></div>
                        <div class="col-6 mb-2"><span class="text-muted d-block small">Branch:</span><strong>{{ $bankSettings['branch'] ?? 'Main Branch' }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block small">Account Number:</span><strong class="text-dark font-weight-bold">{{ $bankSettings['acc_number'] ?? '109283746501' }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block small">Account Name:</span><strong>{{ $bankSettings['acc_name'] ?? 'Invoice & Quotation System Ltd' }}</strong></div>
                    </div>
                </div>
                <div class="col-md-5 mt-3 mt-md-0 border-left pl-md-4">
                    <div class="alert alert-warning mb-0 p-2 text-center">
                        <i class="fas fa-exclamation-circle text-danger mr-1"></i> <strong>Mandatory Payment Reference:</strong><br>
                        When paying via bank transfer, you MUST use your <strong>Quotation Number</strong> (e.g. <code>QUO-2026-XXXXXX</code>) as your bank transfer reference.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Submit Proof Form -->
        <div class="col-lg-5 mb-4">
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-upload mr-2"></i> Submit Proof of Payment</h5>
                </div>
                <form action="{{ route('customer.payments.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Select Invoice *</label>
                            <select name="invoice_id" id="invoice_id" class="form-control" required>
                                @foreach($invoices as $inv)
                                    <option value="{{ $inv->id }}" data-quotation="{{ $inv->quotation->quotation_number ?? ($quotations->first()->quotation_number ?? 'QUO-2026-000000') }}">
                                        {{ $inv->invoice_number }} (Quotation: {{ $inv->quotation->quotation_number ?? 'N/A' }}) - Outstanding: ${{ number_format($inv->amount_outstanding, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Quotation Reference Number for Verification *</label>
                            <input type="text" name="quotation_number" id="quotation_number" class="form-control font-weight-bold text-primary" placeholder="QUO-2026-XXXXXX" value="{{ $invoices->first()->quotation->quotation_number ?? ($quotations->first()->quotation_number ?? '') }}" required>
                            <small class="form-text text-muted">Use the exact Quotation Number printed on your quotation document.</small>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Amount Paid ($) *</label>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="1000.00" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Bank Transfer">Bank Transfer / Telegraphic Transfer</option>
                                <option value="Ecocash">Ecocash / Mobile Money</option>
                                <option value="Credit Card">Credit / Debit Card</option>
                                <option value="Cash">Cash Deposit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Transaction Reference / Deposit Slip # *</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="REF-98765432 or Deposit Slip #" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Attach Proof File (PDF / Image)</label>
                            <input type="file" name="proof_file" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success font-weight-bold btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Payment Proof
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="col-lg-7 mb-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-history mr-2"></i> Payment Submissions History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Quotation #</th>
                                    <th>Invoice #</th>
                                    <th>Amount</th>
                                    <th>Method & Ref</th>
                                    <th>Verification Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $pay)
                                    <tr>
                                        <td>{{ optional($pay->payment_date)->format('d M Y') }}</td>
                                        <td><strong class="text-primary">{{ $pay->quotation_number ?? optional($pay->quotation)->quotation_number ?? 'QUO-2026-XXXXXX' }}</strong></td>
                                        <td><span class="badge badge-info">{{ $pay->invoice->invoice_number ?? 'INV-'.$pay->invoice_id }}</span></td>
                                        <td class="font-weight-bold text-success">${{ number_format($pay->amount, 2) }}</td>
                                        <td>
                                            <small class="d-block font-weight-bold">{{ $pay->payment_method }}</small>
                                            <small class="text-muted">{{ $pay->transaction_reference }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $pay->status === 'verified' ? 'success' : ($pay->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ strtoupper($pay->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No payments submitted yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
