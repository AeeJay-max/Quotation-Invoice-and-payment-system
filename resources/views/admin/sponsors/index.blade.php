@extends('layout')
@section('title', 'Sponsors Management')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Sponsorship Management</h2>
            <p class="text-muted mb-0">Track official event sponsors, packages, contracts, and financial contributions.</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createSponsorModal">
            <i class="fas fa-handshake mr-1"></i> Register Sponsor
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
                                <td colspan="6" class="text-center py-4 text-muted">No sponsors registered yet.</td>
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

<!-- Modal for Registering Sponsor -->
<div class="modal fade" id="createSponsorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.sponsors.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Register Event Sponsor</h5>
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
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Sponsor Name / Brand *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Econet Wireless Zimbabwe" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Company Legal Name</label>
                            <input type="text" name="company_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Sponsorship Tier / Package *</label>
                            <select name="sponsor_package" class="form-control" required>
                                <option value="Platinum">Platinum Sponsor</option>
                                <option value="Gold">Gold Sponsor</option>
                                <option value="Silver">Silver Sponsor</option>
                                <option value="Bronze">Bronze Sponsor</option>
                                <option value="Official Partner">Official Partner</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Contribution Value ($) *</label>
                            <input type="number" step="0.01" name="contribution_amount" class="form-control" placeholder="10000.00" required>
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
                    <button type="submit" class="btn btn-primary">Register Sponsor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
