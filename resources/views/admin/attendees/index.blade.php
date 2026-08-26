@extends('layout')

@section('title', 'Attendee Management')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-users-cog text-info mr-2"></i> Attendee Registration & Processing</h2>
            <p class="text-muted mb-0">Review submitted exhibitor staff, delegates, VIPs, and process badge approvals.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif

    <div class="card card-outline card-info elevation-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Attendee Name</th>
                            <th>Company / Exhibitor</th>
                            <th>Event</th>
                            <th>Position / Title</th>
                            <th>Ticket Type</th>
                            <th>Status</th>
                            <th>Badge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendees as $a)
                            <tr>
                                <td>
                                    <strong class="d-block">{{ $a->full_name }}</strong>
                                    <small class="text-muted">{{ $a->email }}</small>
                                </td>
                                <td>{{ $a->booking->client->company_name ?? 'N/A' }}</td>
                                <td><small class="font-weight-bold">{{ $a->booking->event->name ?? 'N/A' }}</small></td>
                                <td>{{ $a->position ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ optional($a->attendeeType)->name ?? 'General' }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $a->status === 'approved' ? 'success' : ($a->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ strtoupper($a->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($a->badge)
                                        <span class="badge badge-success"><i class="fas fa-id-badge mr-1"></i> {{ $a->badge->badge_code }}</span>
                                    @else
                                        <span class="badge badge-light border">Not Generated</span>
                                    @endif
                                </td>
                                <td>
                                    @if($a->status !== 'approved')
                                        <form action="{{ route('admin.attendees.approve', $a->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success font-weight-bold"><i class="fas fa-check"></i> Approve</button>
                                        </form>
                                        <form action="{{ route('admin.attendees.reject', $a->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger font-weight-bold"><i class="fas fa-times"></i> Reject</button>
                                        </form>
                                    @else
                                        <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> Approved</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No attendees submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $attendees->links() }}
        </div>
    </div>
</div>
@endsection
