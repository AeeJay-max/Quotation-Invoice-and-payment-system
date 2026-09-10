@extends('layout')
@section('title', 'Service Vendors Management')

@section('content')
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Service Vendors & Contractors</h2>
            <p class="text-muted mb-0">Track official event suppliers for security, catering, ICT, medical, sound, and lighting.</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#createVendorModal">
            <i class="fas fa-truck mr-1"></i> Register Vendor Contract
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
                            <th>Company Name</th>
                            <th>Event</th>
                            <th>Service Category</th>
                            <th>Contract Value</th>
                            <th>Payment Status</th>
                            <th>Contact Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td><span class="font-weight-bold text-dark">{{ $vendor->company_name }}</span></td>
                                <td>{{ optional($vendor->event)->name }}</td>
                                <td><span class="badge badge-info">{{ $vendor->service_category }}</span></td>
                                <td><span class="font-weight-bold">${{ number_format($vendor->contract_value, 2) }}</span></td>
                                <td><span class="badge badge-light border">{{ strtoupper($vendor->payment_status) }}</span></td>
                                <td><small class="text-muted">{{ $vendor->contact_person ?? 'N/A' }} ({{ $vendor->phone ?? '' }})</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-truck fa-2x d-block mb-2 text-muted"></i>
                                    No vendor contracts registered yet.<br>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" data-toggle="modal" data-target="#createVendorModal">
                                        <i class="fas fa-plus mr-1"></i> Register First Vendor Contract
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
        {{ $vendors->links() }}
    </div>
</div>
@endsection

@section('modals')
<!-- Modal for Registering Vendor -->
<div class="modal fade" id="createVendorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.vendors.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Register Service Vendor Contract</h5>
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
                            <label class="font-weight-bold">Company / Supplier Name *</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Security Services Zimbabwe" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Service Category *</label>
                            <select name="service_category" class="form-control" required>
                                <option value="Security">Security & Access Control</option>
                                <option value="Catering">Catering & Food Services</option>
                                <option value="Medical">Medical & First Aid</option>
                                <option value="Cleaning">Cleaning & Sanitation</option>
                                <option value="Sound & AV">Audio-Visual & Sound System</option>
                                <option value="Lighting">Stage Lighting & Rigging</option>
                                <option value="ICT & Wi-Fi">ICT, Network & Wi-Fi</option>
                                <option value="Decorations">Decorations & Displays</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Contract Value ($) *</label>
                            <input type="number" step="0.01" min="0" name="contract_value" class="form-control" placeholder="e.g. 5000.00" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact Person</label>
                            <input type="text" name="contact_person" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Save Vendor Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
