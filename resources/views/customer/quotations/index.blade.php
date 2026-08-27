@extends('customer-layout')

@section('title', 'My Quotations')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark"><i class="fas fa-file-invoice text-info mr-2"></i> My Quotations</h2>
    </div>

    <div class="card card-outline card-info elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Quotation #</th>
                            <th>Event</th>
                            <th>Date Created</th>
                            <th>Grand Total</th>
                            <th>Paid %</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotations as $q)
                            @php
                                $inv = $q->invoice ?? ($q->booking ? $q->booking->invoice : null);
                                $pPaid = 0;
                                if ($inv && $inv->total > 0) {
                                    $pPaid = min(100, round(($inv->amount_paid / $inv->total) * 100));
                                }
                            @endphp
                            <tr>
                                <td><strong class="text-primary">{{ $q->quotation_number ?? 'QUO-'.$q->id }}</strong></td>
                                <td>{{ $q->event->name ?? 'N/A' }}</td>
                                <td>{{ optional($q->create_date)->format('d M Y') }}</td>
                                <td class="font-weight-bold text-success">${{ number_format($q->total, 2) }}</td>
                                <td>
                                    <div class="progress progress-xs" style="height: 14px;">
                                        <div class="progress-bar bg-{{ $pPaid == 100 ? 'success' : ($pPaid > 0 ? 'info' : 'warning') }} font-weight-bold" role="progressbar" style="width: {{ $pPaid }}%;">
                                            {{ $pPaid }}%
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-success">{{ strtoupper($q->status) }}</span></td>
                                <td>
                                    <a href="{{ route('public.quotation.view', $q->id) }}" class="btn btn-sm btn-info font-weight-bold" target="_blank">
                                        <i class="fas fa-eye mr-1"></i> View Quotation
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No quotations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $quotations->links() }}
        </div>
    </div>
</div>
@endsection

