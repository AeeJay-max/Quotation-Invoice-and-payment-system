@extends('layout')
@section('title', 'Check-In Logs')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Check-In Activity Log</h2>
            <p class="text-muted mb-0">Real-time attendance record for event entrance check-ins.</p>
        </div>
        <a href="{{ route('admin.checkin.scanner') }}" class="btn btn-success">
            <i class="fas fa-qrcode mr-1"></i> Open Live QR Scanner
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Time</th>
                            <th>Participant Name</th>
                            <th>Event</th>
                            <th>Pass / Ticket Type</th>
                            <th>Gate Station</th>
                            <th>Checked In By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkins as $checkin)
                            <tr>
                                <td><span class="badge badge-light border">{{ $checkin->checkin_time->format('Y-m-d H:i:s') }}</span></td>
                                <td class="font-weight-bold">
                                    {{ optional($checkin->attendee)->full_name ?? (optional($checkin->ticket)->ticket_type ?? 'Participant') }}
                                </td>
                                <td>{{ optional($checkin->event)->name }}</td>
                                <td>
                                    @if($checkin->ticket)
                                        <span class="badge badge-info"><i class="fas fa-ticket-alt mr-1"></i> {{ $checkin->ticket->ticket_number }}</span>
                                    @elseif($checkin->badge)
                                        <span class="badge badge-primary"><i class="fas fa-id-card mr-1"></i> {{ $checkin->badge->badge_code }}</span>
                                    @else
                                        <span class="badge badge-secondary">Pass</span>
                                    @endif
                                </td>
                                <td><span class="text-muted">{{ $checkin->station_name }}</span></td>
                                <td><small class="text-muted">{{ optional($checkin->checkedInBy)->name ?? 'System' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No check-in records logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $checkins->links() }}
    </div>
</div>
@endsection
