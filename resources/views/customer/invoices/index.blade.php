@extends('customer-layout')

@section('title', 'My Invoices')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark"><i class="fas fa-file-invoice-dollar text-success mr-2"></i> My Invoices</h2>
    </div>

    <div class="card card-outline card-success elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Invoice #</th>
                            <th>Event</th>
                            <th>Date Issued</th>
                            <th>Paid</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td><strong class="text-primary">{{ $inv->invoice_number }}</strong></td>
                                <td>{{ $inv->event->name ?? 'N/A' }}</td>
                                <td>{{ optional($inv->create_date)->format('d M Y') }}</td>
                                <td class="font-weight-bold text-success">${{ number_format($inv->amount_paid, 2) }}</td>
                                <td class="font-weight-bold text-danger">${{ number_format($inv->amount_outstanding, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $inv->amount_outstanding <= 0 ? 'success' : 'warning' }}">
                                        {{ $inv->amount_outstanding <= 0 ? 'PAID' : 'PARTIALLY / UNPAID' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('customer.invoices.show', $inv->id) }}" class="btn btn-sm btn-info font-weight-bold">
                                        <i class="fas fa-eye mr-1"></i> View Invoice
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No invoices issued yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection

