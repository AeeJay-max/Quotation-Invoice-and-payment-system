@extends('layout')

@section('title', 'My Company Badges')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark"><i class="fas fa-id-card text-warning mr-2"></i> Company Event Badges</h2>
    </div>

    <div class="card card-outline card-warning elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Badge Code</th>
                            <th>Attendee Name</th>
                            <th>Position</th>
                            <th>Ticket Pass</th>
                            <th>Event</th>
                            <th>Badge Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($badges as $b)
                            <tr>
                                <td><span class="badge badge-dark font-weight-bold">{{ $b->badge_code }}</span></td>
                                <td class="font-weight-bold">{{ $b->attendee->full_name }}</td>
                                <td>{{ $b->attendee->position ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ optional($b->attendee->attendeeType)->name }}</span></td>
                                <td><small class="font-weight-bold">{{ $b->booking->event->name ?? 'N/A' }}</small></td>
                                <td>
                                    <span class="badge badge-{{ $b->status === 'printed' ? 'success' : 'warning' }}">
                                        {{ strtoupper($b->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No badges generated yet. Complete attendee registration to receive badges.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
