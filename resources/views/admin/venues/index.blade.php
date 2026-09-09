@extends('layout')
@section('title', 'Venues & Exhibition Halls')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1">Venues & Exhibition Halls</h2>
            <p class="text-muted mb-0">Manage physical convention centers, halls, dimensions ($m^2$), and rental rates ($/m^2$).</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#createVenueModal">
            <i class="fas fa-plus mr-1"></i> Register New Venue
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Form Error:</strong> Please check your entries.
            <ul class="mb-0 mt-1 pl-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        @forelse($venues as $venue)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="card-title font-weight-bold text-primary mb-0">{{ $venue->name }}</h4>
                            <span class="badge badge-soft-success border border-success px-2 py-1">{{ $venue->city }}, {{ $venue->country }}</span>
                        </div>
                        <p class="text-muted small mb-3">{{ $venue->address ?? 'No physical address provided' }}</p>
                        
                        <div class="row text-center bg-light py-2 rounded mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Halls / Sections</small>
                                <span class="font-weight-bold h5 mb-0 text-dark">{{ $venue->halls_count }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Max Venue Capacity</small>
                                <span class="font-weight-bold h5 mb-0 text-dark">{{ number_format($venue->capacity ?? 0) }} People</span>
                            </div>
                        </div>

                        <h6 class="font-weight-bold mb-2 text-secondary">Exhibition Halls & Pricing Breakdown:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Hall / Room Name</th>
                                        <th class="text-center">Area ($m^2$) Total / Available</th>
                                        <th class="text-center">Tickets Quota & Prices</th>
                                        <th class="text-right">Price / $m^2$</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($venue->halls as $hall)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $hall->name }}</strong>
                                                @if($hall->building_name)
                                                    <small class="text-muted d-block">{{ $hall->building_name }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $hall->total_area_sqm ? number_format($hall->total_area_sqm, 1).' m²' : 'N/A' }}</span>
                                                <small class="d-block text-success font-weight-bold">
                                                    {{ number_format($hall->available_area_sqm ?? $hall->total_area_sqm ?? 0, 1) }} m² left
                                                </small>
                                            </td>
                                            <td class="text-center small">
                                                @if(($hall->vip_tickets_quota ?? 0) > 0)
                                                    <span class="badge badge-warning">VIP: {{ $hall->vip_tickets_available ?? $hall->vip_tickets_quota }}/{{ $hall->vip_tickets_quota }} (${{ number_format($hall->vip_ticket_price ?? 0, 2) }})</span><br>
                                                @endif
                                                @if(($hall->general_tickets_quota ?? 0) > 0)
                                                    <span class="badge badge-secondary">Gen: {{ $hall->general_tickets_available ?? $hall->general_tickets_quota }}/{{ $hall->general_tickets_quota }} (${{ number_format($hall->general_ticket_price ?? 0, 2) }})</span><br>
                                                @endif
                                                @if(($hall->delegate_tickets_quota ?? 0) > 0)
                                                    <span class="badge badge-primary">Del: {{ $hall->delegate_tickets_available ?? $hall->delegate_tickets_quota }}/{{ $hall->delegate_tickets_quota }} (${{ number_format($hall->delegate_ticket_price ?? 0, 2) }})</span>
                                                @endif
                                                @if(!($hall->vip_tickets_quota || $hall->general_tickets_quota || $hall->delegate_tickets_quota))
                                                    <span class="text-muted">Standard</span>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold text-success">
                                                ${{ number_format($hall->price_per_sqm ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-2 text-muted small">No halls configured yet for this venue.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addHallModal{{ $venue->id }}">
                            <i class="fas fa-plus-circle mr-1"></i> Add Hall / Room, Area ($m^2$), Tickets & Pricing
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal for Adding Hall -->
            <div class="modal fade" id="addHallModal{{ $venue->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('admin.venues.halls.store', $venue->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-light">
                                <h5 class="modal-title font-weight-bold">Add Hall / Room / Section to {{ $venue->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="font-weight-bold">Hall / Room Name *</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Main Exhibition Hall A" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Building / Complex Name</label>
                                        <input type="text" name="building_name" class="form-control" placeholder="Main Complex">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Floor Level</label>
                                        <input type="text" name="floor_level" class="form-control" placeholder="Ground Floor">
                                    </div>
                                </div>
                                <div class="form-row bg-light p-3 rounded mb-3 border">
                                    <div class="form-group col-md-6 mb-0">
                                        <label class="font-weight-bold text-dark"><i class="fas fa-ruler-combined text-info mr-1"></i> Total Area in Square Meters ($m^2$) *</label>
                                        <input type="number" step="0.01" name="total_area_sqm" class="form-control" placeholder="e.g. 2500.00" required>
                                        <small class="text-muted">Total available floor space for stands.</small>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label class="font-weight-bold text-dark"><i class="fas fa-tag text-success mr-1"></i> Space Price Per Square Meter ($\$/m^2$) *</label>
                                        <input type="number" step="0.01" name="price_per_sqm" class="form-control" placeholder="e.g. 15.00" required>
                                        <small class="text-muted">Rental rate charged per square meter.</small>
                                    </div>
                                </div>
                                
                                <h6 class="font-weight-bold text-primary mt-3"><i class="fas fa-ticket-alt mr-1"></i> Ticket Allocation Quotas & Ticket Pricing for Room/Hall:</h6>
                                <div class="form-row bg-white p-3 rounded mb-3 border">
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold text-warning">VIP Ticket Quota</label>
                                        <input type="number" name="vip_tickets_quota" class="form-control" placeholder="e.g. 50" value="50">
                                        <label class="small text-muted mt-1">VIP Ticket Price ($)</label>
                                        <input type="number" step="0.01" name="vip_ticket_price" class="form-control" placeholder="e.g. 100.00" value="100.00">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold text-secondary">General Ticket Quota</label>
                                        <input type="number" name="general_tickets_quota" class="form-control" placeholder="e.g. 200" value="200">
                                        <label class="small text-muted mt-1">General Ticket Price ($)</label>
                                        <input type="number" step="0.01" name="general_ticket_price" class="form-control" placeholder="e.g. 25.00" value="25.00">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold text-info">Delegate Ticket Quota</label>
                                        <input type="number" name="delegate_tickets_quota" class="form-control" placeholder="e.g. 100" value="100">
                                        <label class="small text-muted mt-1">Delegate Ticket Price ($)</label>
                                        <input type="number" step="0.01" name="delegate_ticket_price" class="form-control" placeholder="e.g. 50.00" value="50.00">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Max Hall Capacity (People)</label>
                                    <input type="number" name="capacity" class="form-control" placeholder="e.g. 3000">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">Save Hall, Tickets & Pricing</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5>No Venues Registered</h5>
                <p class="text-muted">Click the button above to register your first convention center or exhibition ground.</p>
            </div>
        @endforelse
    </div>

    {{ $venues->links() }}
</div>

<!-- Modal for Venue Creation -->
<div class="modal fade" id="createVenueModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.venues.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Register New Venue</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Venue Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Harare International Conference Centre (HICC)" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Venue Code</label>
                            <input type="text" name="code" class="form-control" placeholder="HICC-01">
                        </div>
                        <div class="form-group col-md-6">
                            <label>City *</label>
                            <input type="text" name="city" class="form-control" value="Harare" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Physical Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Pennefather Avenue, Harare">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Contact Email</label>
                            <input type="email" name="contact_email" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Create Venue</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
