@extends('layout')

@section('title', 'Company Attendees Registration')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-plus text-primary mr-2"></i> Company Attendees & Staff Registration</h2>
            <p class="text-muted mb-0">Register representatives who will attend the trade event on behalf of your company.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger font-weight-bold">{{ session('error') }}</div>
    @endif

    @if($selectedBooking)
        <div class="row">
            <!-- Add Attendee Form -->
            <div class="col-lg-5 mb-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-user-circle mr-2"></i> Add New Attendee</h5>
                    </div>
                    <form action="{{ route('customer.attendees.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $selectedBooking->id }}">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required placeholder="John">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required placeholder="Smith">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Position / Title</label>
                                <input type="text" name="position" class="form-control" placeholder="Managing Director">
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="john@company.com">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="font-weight-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="+263 77 123 4567">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Pass / Ticket Type *</label>
                                <select name="attendee_type_id" class="form-control" required>
                                    @foreach($selectedBooking->event->attendeeTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }} (${{ number_format($type->price, 2) }}) - {{ $type->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">ID / Passport Number</label>
                                <input type="text" name="id_passport" class="form-control" placeholder="ID 63-123456A78">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary font-weight-bold btn-block">
                                <i class="fas fa-plus mr-1"></i> Add Attendee to List
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attendee List & Summary -->
            <div class="col-lg-7 mb-4">
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-list mr-2"></i> Attendee List ({{ $selectedBooking->attendees->count() }})
                        </h5>
                        <span class="badge badge-success font-weight-bold p-2">
                            Total Attendee Cost: ${{ number_format($selectedBooking->attendee_total, 2) }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Ticket Type</th>
                                        <th>Cost</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($selectedBooking->attendees as $att)
                                        <tr>
                                            <td class="font-weight-bold">{{ $att->full_name }}</td>
                                            <td>{{ $att->position ?? 'N/A' }}</td>
                                            <td><span class="badge badge-info">{{ optional($att->attendeeType)->name }}</span></td>
                                            <td class="font-weight-bold">${{ number_format(optional($att->attendeeType)->price ?? 0, 2) }}</td>
                                            <td>
                                                @if($selectedBooking->attendee_status !== 'submitted' && $selectedBooking->attendee_status !== 'approved')
                                                    <form action="{{ route('customer.attendees.destroy', $att->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                @else
                                                    <span class="badge badge-secondary">Locked</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No attendees added yet. Add people using the form on the left.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Status: <strong>{{ strtoupper($selectedBooking->attendee_status) }}</strong></span>
                        
                        @if($selectedBooking->attendees->count() > 0 && $selectedBooking->attendee_status !== 'submitted' && $selectedBooking->attendee_status !== 'approved')
                            <form action="{{ route('customer.attendees.submit', $selectedBooking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success font-weight-bold shadow">
                                    <i class="fas fa-paper-plane mr-1"></i> SUBMIT ATTENDEE LIST TO ADMIN
                                </button>
                            </form>
                        @elseif($selectedBooking->attendee_status === 'submitted')
                            <span class="btn btn-warning disabled font-weight-bold"><i class="fas fa-clock mr-1"></i> Submitted - Under Review</span>
                        @elseif($selectedBooking->attendee_status === 'approved')
                            <span class="btn btn-success disabled font-weight-bold"><i class="fas fa-check-circle mr-1"></i> List Approved</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">No active event booking found to register attendees for.</div>
    @endif
</div>
@endsection
