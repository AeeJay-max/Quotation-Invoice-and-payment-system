@extends('layout')
@section('title', 'Speakers Directory')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Speaker Directory</h2>
            <p class="text-muted mb-0">Manage keynote speakers, panelists, and session moderators for MOSRAC conferences.</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createSpeakerModal">
            <i class="fas fa-user-plus mr-1"></i> Add Speaker Profile
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        @forelse($speakers as $speaker)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center p-4">
                        <div class="avatar bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 24px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark mb-1">{{ $speaker->full_name }}</h5>
                        <p class="text-primary small font-weight-bold mb-2">{{ $speaker->position ?? 'Guest Speaker' }}</p>
                        <p class="text-muted small mb-3">{{ $speaker->organization ?? 'Independent' }}</p>
                        
                        <div class="border-top pt-3 text-left small text-muted">
                            <div class="mb-1"><i class="fas fa-envelope mr-2 text-info"></i> {{ $speaker->email ?? 'No email on file' }}</div>
                            <div class="mb-1"><i class="fas fa-phone mr-2 text-success"></i> {{ $speaker->phone ?? 'No phone on file' }}</div>
                            <div><i class="fas fa-chalkboard-teacher mr-2 text-warning"></i> Assigned Sessions: {{ $speaker->sessions_count }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
                <i class="fas fa-microphone-alt fa-3x text-muted mb-3"></i>
                <h5>No Speakers Registered</h5>
                <p class="text-muted">Add speaker profiles to link them to conference sessions and panel discussions.</p>
            </div>
        @endforelse
    </div>

    {{ $speakers->links() }}
</div>

<!-- Modal for Creating Speaker -->
<div class="modal fade" id="createSpeakerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.speakers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Speaker Profile</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Dr. / Hon. / Prof.">
                        </div>
                        <div class="form-group col-md-5">
                            <label>First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Organization / Company</label>
                            <input type="text" name="organization" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Position / Designation</label>
                            <input type="text" name="position" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Biography</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief professional profile..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Speaker Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
