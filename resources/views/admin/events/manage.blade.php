@extends('layout')

@section('title', 'Manage Event Configuration')

@section('content')
<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-cogs text-primary mr-2"></i> {{ $event->name }}</h2>
            <p class="text-muted mb-0">Code: <strong>{{ $event->event_code }}</strong> | Dates: {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary font-weight-bold">
            <i class="fas fa-arrow-left mr-1"></i> Back to Events
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success font-weight-bold">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Exhibition Spaces / Halls -->
        <div class="col-md-6 mb-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-warehouse mr-2"></i> Exhibition Halls & Spaces</h5>
                    <button class="btn btn-sm btn-primary font-weight-bold" data-toggle="modal" data-target="#modalAddSpace">+ Add Space</button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($event->spaces as $space)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="font-weight-bold mb-1">{{ $space->name }} ({{ $space->code }})</h6>
                                        <p class="small text-muted mb-0">${{ number_format($space->price_per_sqm, 2) }}/m² | Max: {{ $space->max_size }}m²</p>
                                    </div>
                                    <button class="btn btn-xs btn-outline-info" data-toggle="modal" data-target="#modalAddPosition{{ $space->id }}">+ Position</button>
                                </div>

                                <!-- Position List -->
                                @if($space->positions->count() > 0)
                                    <div class="mt-2 pl-3 border-left">
                                        <small class="font-weight-bold text-secondary">Configured Positions:</small>
                                        <div class="d-flex flex-wrap mt-1">
                                            @foreach($space->positions as $pos)
                                                <span class="badge badge-light border mr-1 mb-1 p-1">
                                                    {{ $pos->position_number }} ({{ $pos->position_type }} +${{ number_format($pos->additional_fee, 2) }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No exhibition spaces added yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Stand Types -->
        <div class="col-md-6 mb-4">
            <div class="card card-outline card-success shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-store mr-2"></i> Stand Package Types</h5>
                    <button class="btn btn-sm btn-success font-weight-bold" data-toggle="modal" data-target="#modalAddStand">+ Add Stand Type</button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($event->standTypes as $stand)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-bold mb-1">{{ $stand->name }}</h6>
                                    <p class="small text-muted mb-0">{{ $stand->description }}</p>
                                </div>
                                <span class="badge badge-success font-weight-bold">Base: ${{ number_format($stand->base_price, 2) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No stand packages configured.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Furniture Catalogue -->
        <div class="col-md-6 mb-4">
            <div class="card card-outline card-warning shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-chair mr-2"></i> Furniture Rental Catalogue</h5>
                    <button class="btn btn-sm btn-warning font-weight-bold text-dark" data-toggle="modal" data-target="#modalAddFurniture">+ Add Item</button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($event->furniture as $f)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-bold mb-0">{{ $f->name }}</h6>
                                    <small class="text-muted">Category: {{ $f->category }} | Qty: {{ $f->available_quantity }}</small>
                                </div>
                                <span class="badge badge-warning font-weight-bold text-dark">${{ number_format($f->unit_price, 2) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No furniture items in catalogue.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Event Services -->
        <div class="col-md-6 mb-4">
            <div class="card card-outline card-info shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-plug mr-2"></i> Additional Event Services</h5>
                    <button class="btn btn-sm btn-info font-weight-bold" data-toggle="modal" data-target="#modalAddService">+ Add Service</button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($event->services as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-bold mb-0">{{ $s->name }}</h6>
                                    <small class="text-muted">Category: {{ $s->category }}</small>
                                </div>
                                <span class="badge badge-info font-weight-bold">${{ number_format($s->unit_price, 2) }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No additional services configured.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Attendee Ticket Types -->
        <div class="col-md-12 mb-4">
            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-ticket-alt mr-2"></i> Attendee Pass & Ticket Types</h5>
                    <button class="btn btn-sm btn-secondary font-weight-bold" data-toggle="modal" data-target="#modalAddAttendeeType">+ Add Ticket Type</button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Pass / Ticket Name</th>
                                <th>Description</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($event->attendeeTypes as $t)
                                <tr>
                                    <td class="font-weight-bold">{{ $t->name }}</td>
                                    <td>{{ $t->description }}</td>
                                    <td><span class="badge badge-success font-weight-bold">${{ number_format($t->price, 2) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No ticket types configured yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Space -->
<div class="modal fade" id="modalAddSpace" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.events.space.store', $event->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Exhibition Space / Hall</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Hall / Space Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Hall A" required>
                    </div>
                    <div class="form-group">
                        <label>Space Code</label>
                        <input type="text" name="code" class="form-control" placeholder="HALL-A">
                    </div>
                    <div class="form-group">
                        <label>Price Per Sq. Metre ($) *</label>
                        <input type="number" step="0.01" name="price_per_sqm" class="form-control" value="50.00" required>
                    </div>
                    <div class="form-row">
                        <div class="col"><label>Min Size (m²)</label><input type="number" name="min_size" class="form-control" value="9"></div>
                        <div class="col"><label>Max Size (m²)</label><input type="number" name="max_size" class="form-control" value="500"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary font-weight-bold">Save Space</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Stand -->
<div class="modal fade" id="modalAddStand" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.events.stand.store', $event->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Stand Type</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Stand Package Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Premium Stand" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Base Price ($) *</label>
                        <input type="number" step="0.01" name="base_price" class="form-control" value="200.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success font-weight-bold">Save Stand Type</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Furniture -->
<div class="modal fade" id="modalAddFurniture" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.events.furniture.store', $event->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Furniture Item</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Item Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Executive Chair" required>
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <input type="text" name="category" class="form-control" value="Seating" required>
                    </div>
                    <div class="form-group">
                        <label>Unit Price ($) *</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" value="10.00" required>
                    </div>
                    <div class="form-group">
                        <label>Available Quantity *</label>
                        <input type="number" name="available_quantity" class="form-control" value="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark">Save Furniture</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Service -->
<div class="modal fade" id="modalAddService" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.events.service.store', $event->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Event Service</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Service Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Dedicated High-Speed WiFi" required>
                    </div>
                    <div class="form-group">
                        <label>Category *</label>
                        <input type="text" name="category" class="form-control" value="Internet" required>
                    </div>
                    <div class="form-group">
                        <label>Unit Price ($) *</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" value="150.00" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info font-weight-bold">Save Service</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Attendee Type -->
<div class="modal fade" id="modalAddAttendeeType" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.events.attendee-type.store', $event->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Add Attendee Ticket Type</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pass / Ticket Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="VIP Pass" required>
                    </div>
                    <div class="form-group">
                        <label>Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="50.00" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary font-weight-bold">Save Ticket Type</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Position Modals per Space -->
@foreach($event->spaces as $space)
    <div class="modal fade" id="modalAddPosition{{ $space->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.spaces.position.store', $space->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Add Position in {{ $space->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Position Number *</label>
                            <input type="text" name="position_number" class="form-control" placeholder="A12" required>
                        </div>
                        <div class="form-group">
                            <label>Position Type *</label>
                            <select name="position_type" class="form-control" required>
                                <option value="Corner">Corner</option>
                                <option value="Entrance">Entrance</option>
                                <option value="Central">Central</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Additional Fee ($) *</label>
                            <input type="number" step="0.01" name="additional_fee" class="form-control" value="50.00" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info font-weight-bold">Save Position</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
