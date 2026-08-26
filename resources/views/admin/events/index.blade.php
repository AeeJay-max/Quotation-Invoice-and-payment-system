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
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-outline card-primary elevation-2">
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
                            <th>Halls/Spaces</th>
                            <th>Bookings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td><span class="badge badge-secondary font-weight-bold">{{ $event->event_code }}</span></td>
                                <td class="font-weight-bold">{{ $event->name }}</td>
                                <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
                                <td>{{ $event->venue ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $event->status === 'registration_open' ? 'success' : 'info' }}">
                                        {{ str_replace('_', ' ', strtoupper($event->status)) }}
                                    </span>
                                </td>
                                <td><span class="badge badge-light border">{{ $event->spaces_count }} Spaces</span></td>
                                <td><span class="badge badge-primary">{{ $event->bookings_count }} Bookings</span></td>
                                <td>
                                    <a href="{{ route('admin.events.manage', $event->id) }}" class="btn btn-sm btn-info font-weight-bold">
                                        <i class="fas fa-cogs mr-1"></i> Configure & Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No events configured yet. Click "Create New Event" to get started.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
