@extends('layout')
@section('title', 'Programme & Sessions Agenda')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Programme & Sessions Agenda</h2>
            <p class="text-muted mb-0">Schedule event sessions, workshops, panel discussions, and keynotes.</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createSessionModal">
            <i class="fas fa-plus mr-1"></i> Schedule Session
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
                            <th>Date & Time</th>
                            <th>Session Title</th>
                            <th>Event</th>
                            <th>Type</th>
                            <th>Venue Room</th>
                            <th>Speakers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $session->session_date ? $session->session_date->format('M d, Y') : '' }}</div>
                                    <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $session->start_time }} - {{ $session->end_time }}</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary">{{ $session->title }}</div>
                                    <small class="text-muted">{{ Str::limit($session->description, 60) }}</small>
                                </td>
                                <td>{{ optional($session->event)->name }}</td>
                                <td><span class="badge badge-info">{{ strtoupper($session->session_type) }}</span></td>
                                <td><span class="text-secondary"><i class="fas fa-map-marker-alt mr-1"></i> {{ $session->venue_room ?? 'Main Stage' }}</span></td>
                                <td>
                                    @foreach($session->speakers as $spk)
                                        <span class="badge badge-light border text-dark mb-1">{{ $spk->full_name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No agenda sessions scheduled yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $sessions->links() }}
    </div>
</div>

<!-- Modal for Creating Session -->
<div class="modal fade" id="createSessionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.programme.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Schedule Agenda Session</h5>
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
                        <label>Session Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Ministerial Opening Address & Keynote" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Date *</label>
                            <input type="date" name="session_date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Start Time *</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>End Time *</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Venue Room / Hall Stage</label>
                            <input type="text" name="venue_room" class="form-control" placeholder="Main Auditorium / Hall A Stage">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Session Type</label>
                            <select name="session_type" class="form-control">
                                <option value="keynote">Keynote Address</option>
                                <option value="presentation">Presentation</option>
                                <option value="panel">Panel Discussion</option>
                                <option value="workshop">Workshop / Seminar</option>
                                <option value="ceremony">Ceremony</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Assign Speakers</label>
                        <select name="speakers[]" class="form-control" multiple style="height: 120px;">
                            @foreach($speakers as $spk)
                                <option value="{{ $spk->id }}">{{ $spk->full_name }} ({{ $spk->organization ?? 'Guest' }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl / Cmd to select multiple speakers.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Session</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
