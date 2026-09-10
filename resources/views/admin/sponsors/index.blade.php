@extends('layout')
@section('title', 'Sponsors Management')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Sponsorship Management</h2>
            <p class="text-muted mb-0">Track official event sponsors, packages, contracts, and financial contributions.</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#createSponsorModal">
            <i class="fas fa-handshake mr-1"></i> Register Sponsor
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Submission Error:</strong> Please check your form entries.
            <ul class="mb-0 mt-1 pl-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Sponsor / Company</th>
                            <th>Event</th>
                            <th>Package</th>
                            <th>Contribution Value</th>
                            <th>Payment Status</th>
                            <th>Contact Person</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $sponsor->name }}</div>
                                    <small class="text-muted">{{ $sponsor->company_name }}</small>
                                </td>
                                <td>{{ optional($sponsor->event)->name }}</td>
                                <td>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">{{ strtoupper($sponsor->sponsor_package) }}</span>
                                </td>
                                <td><span class="font-weight-bold text-success">${{ number_format($sponsor->contribution_amount, 2) }}</span></td>
                                <td>
                                    <span class="badge badge-light border">{{ strtoupper($sponsor->payment_status) }}</span>
                                </td>
                                <td><small class="text-muted">{{ $sponsor->contact_person ?? 'N/A' }} ({{ $sponsor->email ?? '' }})</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-handshake fa-2x d-block mb-2 text-muted"></i>
                                    No sponsors registered yet.<br>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-toggle="modal" data-target="#createSponsorModal">
                                        <i class="fas fa-plus mr-1"></i> Register First Sponsor
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $sponsors->links() }}
    </div>
</div>
@endsection

@section('modals')
<!-- Modal for Registering Sponsor -->
<div class="modal fade" id="createSponsorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.sponsors.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Register Event Sponsor</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Select Event *</label>
                        <select name="event_id" class="form-control" required>
                            @forelse($events as $evt)
                                <option value="{{ $evt->id }}" {{ session('selected_event_id') == $evt->id ? 'selected' : '' }}>
                                    {{ $evt->name }}
                                </option>
                            @empty
                                <option value="" disabled selected>No events created yet</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Sponsor Name / Brand *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Econet Wireless Zimbabwe" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Company Legal Name</label>
                            <input type="text" name="company_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Sponsorship Tier / Package *</label>
                            <select name="sponsor_package" class="form-control" required>
                                <option value="Platinum">Platinum Sponsor</option>
                                <option value="Gold">Gold Sponsor</option>
                                <option value="Silver">Silver Sponsor</option>
                                <option value="Bronze">Bronze Sponsor</option>
                                <option value="Official Partner">Official Partner</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Contribution Value ($) *</label>
                            <input type="number" step="0.01" min="0" name="contribution_amount" class="form-control" placeholder="10000.00" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Register Sponsor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
