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
                            <th>Booking</th>
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
                                    @if ($inv->payment_status == 3)
                                        <span class="badge badge-secondary">CANCELLED</span>
                                    @elseif ($inv->payment_status == 1 || $inv->amount_outstanding <= 0)
                                        <span class="badge badge-success">PAID</span>
                                    @elseif ($inv->payment_status == 4 || ($inv->amount_paid > 0 && $inv->amount_outstanding > 0))
                                        <span class="badge badge-warning">PARTIALLY PAID</span>
                                    @else
                                        <span class="badge badge-danger">UNPAID</span>
                                    @endif
                                </td>
                                <td>
                                    @if($inv->is_confirmed)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmed
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Pending Confirmation</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.invoices.show', $inv->id) }}" class="btn btn-sm btn-info font-weight-bold">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    @if(!$inv->is_confirmed && $inv->amount_outstanding > 0)
                                        <form method="POST" action="{{ route('customer.invoices.confirm', $inv->id) }}" class="d-inline" onsubmit="return confirm('Confirm this booking?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning font-weight-bold">
                                                <i class="fas fa-check mr-1"></i> Confirm
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No invoices issued yet.</td>
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


