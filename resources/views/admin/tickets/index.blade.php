@extends('layout')
@section('title', 'Ticket Issuance')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Ticket Issuance Management</h2>
            <p class="text-muted mb-0">Manage general admission, VIP, media, speaker, and delegate event passes.</p>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#issueTicketModal">
            <i class="fas fa-ticket-alt mr-1"></i> Issue New Ticket
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Ticket Number</th>
                            <th>Event</th>
                            <th>Attendee / User</th>
                            <th>Ticket Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Issued At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td><span class="font-weight-bold text-primary">{{ $ticket->ticket_number }}</span></td>
                                <td>{{ optional($ticket->event)->name }}</td>
                                <td>{{ optional($ticket->attendee)->full_name ?? (optional($ticket->user)->name ?? 'General Holder') }}</td>
                                <td><span class="badge badge-info">{{ $ticket->ticket_type }}</span></td>
                                <td><span class="font-weight-bold">${{ number_format($ticket->price, 2) }}</span></td>
                                <td>
                                    @if($ticket->status === 'ISSUED')
                                        <span class="badge badge-success">VALID / ISSUED</span>
                                    @elseif($ticket->status === 'USED')
                                        <span class="badge badge-secondary">USED / SCANNED</span>
                                    @else
                                        <span class="badge badge-danger">{{ $ticket->status }}</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $ticket->issued_at ? $ticket->issued_at->format('Y-m-d H:i') : '' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No tickets issued yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
</div>
@endsection

@section('modals')
<!-- Modal for Ticket Issuance -->
<div class="modal fade" id="issueTicketModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Issue Event Pass / Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event *</label>
                        <select name="event_id" class="form-control" required>
                            @foreach($events as $evt)
                                <option value="{{ $evt->id }}">{{ $evt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ticket Type *</label>
                        <select name="ticket_type" class="form-control" required>
                            <option value="General Admission">General Admission</option>
                            <option value="VIP">VIP</option>
                            <option value="VVIP">VVIP</option>
                            <option value="Speaker Pass">Speaker Pass</option>
                            <option value="Media Pass">Media Pass</option>
                            <option value="Staff Pass">Staff Pass</option>
                            <option value="Delegate Pass">Delegate Pass</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="0.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Issue Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
