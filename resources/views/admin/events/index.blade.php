@extends('layout')

@section('title', 'Event Management')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="font-weight-bold text-dark"><i class="fas fa-calendar-alt text-primary mr-2"></i> Event Exhibitions Management</h2>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary font-weight-bold">
            <i class="fas fa-plus mr-1"></i> Create New Event
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- ══ ACTIVE EVENTS ══ --}}
    <div class="card card-outline card-primary elevation-2 mb-4">
        <div class="card-header">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-check mr-2"></i> Active Events
                <span class="badge badge-primary ml-2">{{ $events->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Event Code</th>
                            <th>Event Name</th>
                            <th>Dates</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th class="text-center">Spaces</th>
                            <th class="text-center">Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td><span class="badge badge-secondary font-weight-bold">{{ $event->event_code }}</span></td>
                                <td class="font-weight-bold">{{ $event->name }}</td>
                                <td>{{ $event->start_date->format('d M Y') }} – {{ $event->end_date->format('d M Y') }}</td>
                                <td>{{ $event->venue ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = match($event->status) {
                                            'registration_open' => 'success',
                                            'published'         => 'info',
                                            'completed'         => 'secondary',
                                            'cancelled'         => 'danger',
                                            'draft'             => 'warning',
                                            default             => 'light',
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ str_replace('_', ' ', strtoupper($event->status)) }}
                                    </span>
                                </td>
                                <td class="text-center"><span class="badge badge-light border">{{ $event->spaces_count }}</span></td>
                                <td class="text-center"><span class="badge badge-primary">{{ $event->bookings_count }}</span></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.events.manage', $event->id) }}"
                                           class="btn btn-sm btn-info font-weight-bold">
                                            <i class="fas fa-cogs mr-1"></i> Manage
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-danger font-weight-bold"
                                                onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->name) }}', {{ $event->bookings_count }})">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No events configured yet. Click <strong>Create New Event</strong> to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">{{ $events->links() }}</div>
    </div>

    {{-- ══ DELETED EVENTS HISTORY ══ --}}
    @if($deletedEvents->count() > 0)
    <div class="card card-outline card-secondary elevation-1">
        <div class="card-header" style="background:#f8f9fa;">
            <h5 class="mb-0 font-weight-bold text-secondary">
                <i class="fas fa-history mr-2"></i> Deleted Events — History
                <span class="badge badge-secondary ml-2">{{ $deletedEvents->count() }}</span>
            </h5>
            <small class="text-muted">These events have been removed from the public listing but are preserved for record-keeping. They are <strong>not visible</strong> to exhibitors applying for events.</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="background:#fafafa;">
                    <thead class="thead-light">
                        <tr>
                            <th>Event Code</th>
                            <th>Event Name</th>
                            <th>Dates</th>
                            <th>Venue</th>
                            <th class="text-center">Bookings</th>
                            <th>Deleted On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedEvents as $event)
                            <tr class="text-muted">
                                <td><span class="badge badge-light border">{{ $event->event_code }}</span></td>
                                <td class="font-weight-bold" style="text-decoration:line-through; color:#999;">{{ $event->name }}</td>
                                <td>{{ $event->start_date->format('d M Y') }} – {{ $event->end_date->format('d M Y') }}</td>
                                <td>{{ $event->venue ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-secondary">{{ $event->bookings_count }}</span>
                                </td>
                                <td>{{ $event->deleted_at->format('d M Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.events.restore', $event->id) }}" method="POST"
                                          onsubmit="return confirm('Restore \'{{ addslashes($event->name) }}\' back to active events?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success font-weight-bold">
                                            <i class="fas fa-undo mr-1"></i> Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Delete confirmation modal --}}
<div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash mr-2"></i> Delete Event</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>You are about to delete the event: <strong id="deleteEventName" class="text-danger"></strong></p>
                <div id="activeBookingsWarning" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Warning:</strong> This event has active bookings. The system will block deletion to protect booking records.
                    Change all bookings to <em>cancelled</em> first, or change the event status to <em>cancelled</em>.
                </div>
                <p class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i>
                    The event will be <strong>saved in the Deleted Events history</strong> and can be restored at any time.
                    It will <strong>no longer appear</strong> in the public event listing or application forms.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteEventForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger font-weight-bold">
                        <i class="fas fa-trash mr-1"></i> Yes, Delete &amp; Save to History
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id, name, bookingsCount) {
    document.getElementById('deleteEventName').textContent = name;
    document.getElementById('deleteEventForm').action = '/admin/events/' + id;

    const warning = document.getElementById('activeBookingsWarning');
    if (bookingsCount > 0) {
        warning.classList.remove('d-none');
        warning.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> <strong>Warning:</strong> This event has <strong>' + bookingsCount + ' booking(s)</strong>. Only events with ALL bookings cancelled can be deleted.';
    } else {
        warning.classList.add('d-none');
    }

    $('#deleteEventModal').modal('show');
}
</script>
@endpush
@endsection
