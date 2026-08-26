@extends('layout')

@section('title', 'Badge Management & Printing')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-id-card text-warning mr-2"></i> Badge Management & Printing</h2>
            <p class="text-muted mb-0">Generate, preview, and batch print event badges for approved exhibitors and attendees.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif

    <div class="card card-outline card-warning elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Badge Code</th>
                            <th>Attendee Name</th>
                            <th>Company</th>
                            <th>Event</th>
                            <th>Ticket Type</th>
                            <th>Print Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($badges as $b)
                            <tr>
                                <td><span class="badge badge-dark font-weight-bold">{{ $b->badge_code }}</span></td>
                                <td class="font-weight-bold">{{ $b->attendee->full_name }}</td>
                                <td>{{ $b->attendee->company ?? ($b->booking->client->company_name ?? 'N/A') }}</td>
                                <td><small class="font-weight-bold">{{ $b->booking->event->name ?? 'N/A' }}</small></td>
                                <td><span class="badge badge-info">{{ optional($b->attendee->attendeeType)->name }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $b->status === 'printed' ? 'success' : 'warning' }}">
                                        {{ strtoupper($b->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.badges.print', $b->id) }}" class="btn btn-sm btn-primary font-weight-bold" target="_blank">
                                        <i class="fas fa-print mr-1"></i> Print Badge
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No badges generated yet. Approve attendees to generate badges.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $badges->links() }}
        </div>
    </div>
</div>
@endsection
