@extends('public-layout')

@section('title', 'Exhibition Quotation Request')

@section('content')
<style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .hero-header { background: linear-gradient(135deg, #006B3F 0%, #004D2D 100%); color: #fff; padding: 40px 0; border-bottom: 4px solid #FFD200; }
    .wizard-card { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; }
    
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    
    .step-indicator { display: flex; justify-content: space-between; margin-bottom: 30px; position: relative; }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 4px;
        background: #e9ecef;
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
        border: 4px solid #f4f6f9;
    }
    .step-item.active .step-circle {
        background: #006B3F;
        color: #fff;
    }
    .step-item.completed .step-circle {
        background: #008C45;
        color: #fff;
    }
    .step-label { font-size: 0.85rem; font-weight: 600; color: #6c757d; }
    .step-item.active .step-label { color: #006B3F; }
    
    .card-title { color: #006B3F; font-weight: 700; border-bottom: 2px solid #FFD200; padding-bottom: 10px; margin-bottom: 20px; }
</style>

<div class="hero-header text-center">
    <div class="container">
        <h1 class="display-4 font-weight-bold">
            <img src="{{asset($global_settings['logo'] ?? '')}}" alt="Ministry Logo" style="height: 70px; margin-right: 15px; vertical-align: middle;">
            Exhibition Quotation Request
        </h1>
        <p class="lead mb-0">Step-by-step exhibition stand and space configuration.</p>
    </div>
</div>

<div class="container my-5">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card wizard-card p-4">
        <!-- Progress Indicator -->
        <div class="step-indicator">
            <div class="step-item active" id="indicator-1"><div class="step-circle">1</div><div class="step-label">Event</div></div>
            <div class="step-item" id="indicator-2"><div class="step-circle">2</div><div class="step-label">Company</div></div>
            <div class="step-item" id="indicator-3"><div class="step-circle">3</div><div class="step-label">People</div></div>
            <div class="step-item" id="indicator-4"><div class="step-circle">4</div><div class="step-label">Space</div></div>
            <div class="step-item" id="indicator-5"><div class="step-circle">5</div><div class="step-label">Furniture</div></div>
            <div class="step-item" id="indicator-6"><div class="step-circle">6</div><div class="step-label">Services</div></div>
            <div class="step-item" id="indicator-7"><div class="step-circle">7</div><div class="step-label">Review</div></div>
            <div class="step-item" id="indicator-8"><div class="step-circle">8</div><div class="step-label">Submit</div></div>
        </div>

        <form action="{{ route('public.booking.submit') }}" method="POST" id="quotationWizardForm">
            @csrf

            <!-- STEP 1 -->
            <div class="wizard-step active" id="step-1">
                <h4 class="card-title">1. Select Event / Exhibition</h4>
                <div class="form-group">
                    <label class="font-weight-bold">Available Events *</label>
                    <select name="event_id" id="event_id" class="form-control form-control-lg" required>
                        <option value="" selected disabled>-- Select an Event --</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt->id }}">
                                {{ $evt->name }} ({{ $evt->start_date->format('d M Y') }} - {{ $evt->venue }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="text-right mt-4">
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="wizard-step" id="step-2">
                <h4 class="card-title">2. Company & Exhibitor Information</h4>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Company Name *</label>
                        <input type="text" name="company_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Company Reg Number</label>
                        <input type="text" name="registration_number" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Contact Person *</label>
                        <input type="text" name="contact_person" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Designation / Position</label>
                        <input type="text" name="position" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Phone Number *</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Physical Address *</label>
                        <input type="text" name="physical_address" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Country *</label>
                        <input type="text" name="country" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Website</label>
                        <input type="url" name="website" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Business Category *</label>
                        <select name="business_category" class="form-control" required>
                            <option value="" selected disabled>-- Select Category --</option>
                            <option value="ICT">ICT & Technology</option>
                            <option value="Finance">Finance & Banking</option>
                            <option value="Manufacturing">Manufacturing & Trade</option>
                            <option value="Agriculture">Agriculture & Mining</option>
                            <option value="Health">Health & Pharmaceuticals</option>
                            <option value="Education">Education & Training</option>
                            <option value="Services">Professional Services</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                @guest
                <div class="alert alert-info mt-3">
                    <strong>Exhibitor Account Creation:</strong> An account will be automatically created so you can log in and track your quotation.
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Password *</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>
                @endguest

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="wizard-step" id="step-3">
                <h4 class="card-title">3. Attendees & Ticket Pass Selection</h4>
                <p class="text-muted">Select the number of people attending and specify pass categories for your delegation.</p>

                <div class="card bg-light p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-warning text-center h-100">
                                <div class="card-header bg-warning font-weight-bold text-dark">
                                    <i class="fas fa-crown mr-1"></i> VIP Passes
                                </div>
                                <div class="card-body">
                                    <h5 class="text-dark font-weight-bold mb-1" id="vip_price_label">$100.00 / pass</h5>
                                    <small class="text-muted d-block mb-3" id="vip_available_label">VIP Tickets Available</small>
                                    <input type="number" min="0" name="vip_tickets_count" id="vip_tickets_count" class="form-control form-control-lg text-center font-weight-bold" value="0" onchange="calculateTicketTotal()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-secondary text-center h-100">
                                <div class="card-header bg-secondary font-weight-bold text-white">
                                    <i class="fas fa-user-friends mr-1"></i> General Admission
                                </div>
                                <div class="card-body">
                                    <h5 class="text-dark font-weight-bold mb-1" id="general_price_label">$25.00 / pass</h5>
                                    <small class="text-muted d-block mb-3" id="general_available_label">General Tickets Available</small>
                                    <input type="number" min="0" name="general_tickets_count" id="general_tickets_count" class="form-control form-control-lg text-center font-weight-bold" value="1" onchange="calculateTicketTotal()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-info text-center h-100">
                                <div class="card-header bg-info font-weight-bold text-white">
                                    <i class="fas fa-id-badge mr-1"></i> Delegate Passes
                                </div>
                                <div class="card-body">
                                    <h5 class="text-dark font-weight-bold mb-1" id="delegate_price_label">$50.00 / pass</h5>
                                    <small class="text-muted d-block mb-3" id="delegate_available_label">Delegate Tickets Available</small>
                                    <input type="number" min="0" name="delegate_tickets_count" id="delegate_tickets_count" class="form-control form-control-lg text-center font-weight-bold" value="0" onchange="calculateTicketTotal()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 pt-3 border-top text-center">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-dark mb-0">Total Attendees: <span id="total_people_display" class="text-primary font-weight-bold">1</span> People</h5>
                            <input type="hidden" name="people_count" id="people_count" value="1">
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold text-dark mb-0">Ticket Total Price: <span id="ticket_total_price_display" class="text-success font-weight-bold">$25.00</span></h5>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 4 -->
            <div class="wizard-step" id="step-4">
                <h4 class="card-title">4. Exhibition Space & Stand Configuration</h4>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Exhibition Space / Hall *</label>
                        <select name="event_space_id" id="event_space_id" class="form-control" required>
                            <option value="" selected disabled>-- Select Space --</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Stand Package Type *</label>
                        <select name="stand_type_id" id="stand_type_id" class="form-control" required>
                            <option value="" selected disabled>-- Select Stand Type --</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold text-success" style="font-size: 1.1rem;"><i class="fas fa-ruler-combined mr-1"></i> Requested Area (Square Meters m²) *</label>
                        <div class="input-group">
                            <input type="number" step="0.5" min="1" id="custom_area" name="custom_area" class="form-control form-control-lg font-weight-bold text-success border-success" placeholder="e.g. 36" required>
                            <div class="input-group-append">
                                <span class="input-group-text bg-success text-white font-weight-bold">m²</span>
                            </div>
                        </div>
                        <small class="form-text text-muted font-weight-bold">Apply the total square meters (m²) your company requires.</small>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Width (Metres) <small class="text-muted">(Optional)</small></label>
                        <input type="number" step="0.5" min="0.5" name="width" id="width" class="form-control" placeholder="e.g. 6">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Length (Metres) <small class="text-muted">(Optional)</small></label>
                        <input type="number" step="0.5" min="0.5" name="length" id="length" class="form-control" placeholder="e.g. 6">
                    </div>
                    <input type="hidden" id="calculated_area" name="calculated_area">
                    <div class="col-md-12 form-group">
                        <label>Preferred Position / Stand Location</label>
                        <select name="space_position_id" id="space_position_id" class="form-control">
                            <option value="" selected>No preference / Standard</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 5 -->
            <div class="wizard-step" id="step-5">
                <h4 class="card-title">5. Furniture Rental Catalogue</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th style="width: 150px;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="furniture_list">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 6 -->
            <div class="wizard-step" id="step-6">
                <h4 class="card-title">6. Additional Utilities & Event Services</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Service Name</th>
                                <th>Category</th>
                                <th>Unit Price</th>
                                <th style="width: 150px;">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="services_list">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next" onclick="prepareReview()">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 7 -->
            <div class="wizard-step" id="step-7">
                <h4 class="card-title">7. Review Your Quotation</h4>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5>Event</h5>
                                <p id="rev_event"></p>
                                
                                <h5>Exhibitor</h5>
                                <p id="rev_company"></p>
                                <p id="rev_contact"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h5>People Attending</h5>
                                <p id="rev_people"></p>
                                
                                <h5>Exhibition Space</h5>
                                <p id="rev_space"></p>
                                <p id="rev_dimensions"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5>Furniture & Services</h5>
                        <ul id="rev_extras" class="list-unstyled"></ul>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary btn-lg px-5 btn-next">Next <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 8 -->
            <div class="wizard-step" id="step-8">
                <h4 class="card-title">8. Terms & Submission</h4>
                
                <div class="card border-warning mb-4">
                    <div class="card-header bg-warning text-dark font-weight-bold">
                        Official Exhibition Terms & Conditions
                    </div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto; background: #f8f9fa;">
                        <p id="event_terms">Select an event to view terms.</p>
                    </div>
                </div>

                <div class="form-check mb-4 custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="terms_accepted" id="terms_accepted" required>
                    <label class="custom-control-label font-weight-bold" for="terms_accepted">
                        I confirm that all entered details are correct and accept the official Exhibition Terms & Conditions.
                    </label>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary btn-lg px-4 btn-prev"><i class="fas fa-arrow-left"></i> Back</button>
                    <button type="submit" class="btn btn-success btn-lg px-5" id="btnSubmit" disabled>Submit Quotation Request</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const eventsData = @json($events);
    let currentStep = 1;
    const totalSteps = 8;

    function showStep(step) {
        $('.wizard-step').removeClass('active');
        $('#step-' + step).addClass('active');
        
        $('.step-item').removeClass('active completed');
        for(let i=1; i<step; i++) {
            $('#indicator-' + i).addClass('completed');
        }
        $('#indicator-' + step).addClass('active');
    }

    function validateStep(step) {
        let isValid = true;
        
        if (step === 1) {
            if (!$('#event_id').val()) {
                alert('Please select an event.');
                isValid = false;
            }
        } else if (step === 2) {
            $('#step-2 input[required], #step-2 select[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            @guest
            if ($('#password').val() !== $('#password_confirmation').val()) {
                alert("Passwords do not match.");
                isValid = false;
            }
            @endguest
            if(!isValid) alert("Please fill in all required company fields.");
        } else if (step === 3) {
            let pCount = parseInt($('#people_count').val());
            if (isNaN(pCount) || pCount < 1) {
                alert('Please select at least 1 attendee ticket pass.');
                isValid = false;
            }
        } else if (step === 4) {
            let reqArea = parseFloat($('#custom_area').val()) || (parseFloat($('#width').val()) * parseFloat($('#length').val()));
            if (!$('#event_space_id').val() || !$('#stand_type_id').val() || isNaN(reqArea) || reqArea <= 0) {
                alert('Please select an exhibition space, stand type, and specify your desired square meters (m²).');
                isValid = false;
            } else {
                let selectedOpt = $('#event_space_id option:selected');
                let remSqm = parseFloat(selectedOpt.data('rem-sqm'));
                if (!isNaN(remSqm) && reqArea > remSqm) {
                    alert(`Requested space (${reqArea} m²) exceeds the remaining available space (${remSqm} m²) in this hall/room.`);
                    isValid = false;
                }
            }
        }
        
        return isValid;
    }

    $('.btn-next').click(function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $('.btn-prev').click(function() {
        currentStep--;
        showStep(currentStep);
    });

    function calculateTicketTotal() {
        let vip = parseInt($('#vip_tickets_count').val()) || 0;
        let gen = parseInt($('#general_tickets_count').val()) || 0;
        let del = parseInt($('#delegate_tickets_count').val()) || 0;

        let totalPeople = vip + gen + del;
        if (totalPeople < 1) {
            totalPeople = 1;
            $('#general_tickets_count').val(1);
            gen = 1;
        }

        $('#people_count').val(totalPeople);
        $('#total_people_display').text(totalPeople);

        let spaceId = $('#event_space_id').val();
        let ev = eventsData.find(e => e.id == $('#event_id').val());
        let space = ev && ev.spaces ? ev.spaces.find(s => s.id == spaceId) : null;

        let vipPrice = space ? parseFloat(space.vip_ticket_price || 100.00) : 100.00;
        let genPrice = space ? parseFloat(space.general_ticket_price || 25.00) : 25.00;
        let delPrice = space ? parseFloat(space.delegate_ticket_price || 50.00) : 50.00;

        let totalPrice = (vip * vipPrice) + (gen * genPrice) + (del * delPrice);
        $('#ticket_total_price_display').text('$' + totalPrice.toFixed(2));
    }
    
    // Dynamic loading based on Event ID
    $('#event_id').change(function() {
        let eventId = $(this).val();
        let ev = eventsData.find(e => e.id == eventId);
        if(!ev) return;
        
        // Populate Spaces with remaining available m²
        let spacesHtml = '<option value="" selected disabled>-- Select Space / Hall --</option>';
        ev.spaces.forEach(s => {
            let remSqm = (s.available_area_sqm !== undefined && s.available_area_sqm !== null) ? s.available_area_sqm : (s.total_area_sqm || 500);
            let badgeText = remSqm <= 0 ? ' [FULL]' : ` [${remSqm} m² available]`;
            spacesHtml += `<option value="${s.id}" data-rem-sqm="${remSqm}" ${remSqm <= 0 ? 'disabled' : ''}>${s.name} - ${remSqm} m² available ($${s.price_per_sqm}/m²)</option>`;
        });
        $('#event_space_id').html(spacesHtml);
        
        // Populate Stands
        let standsHtml = '<option value="" selected disabled>-- Select Stand Type --</option>';
        ev.stand_types.forEach(s => {
            standsHtml += `<option value="${s.id}">${s.name} (Base: $${s.base_price})</option>`;
        });
        $('#stand_type_id').html(standsHtml);
        
        // Positions will be loaded when a space is selected
        $('#space_position_id').html('<option value="" selected>No preference / Standard</option>');
        
        // Populate Furniture
        let furnHtml = '';
        ev.furniture.forEach(f => {
            furnHtml += `<tr>
                <td>${f.name}</td>
                <td>${f.category}</td>
                <td>$${f.price}</td>
                <td><input type="number" name="furniture[${f.id}]" class="form-control text-center" value="" placeholder="0" min="0"></td>
            </tr>`;
        });
        $('#furniture_list').html(furnHtml);
        
        // Populate Services
        let servHtml = '';
        ev.services.forEach(s => {
            servHtml += `<tr>
                <td>${s.name}</td>
                <td>${s.category}</td>
                <td>$${s.price}</td>
                <td><input type="number" name="services[${s.id}]" class="form-control text-center" value="" placeholder="0" min="0"></td>
            </tr>`;
        });
        $('#services_list').html(servHtml);
        
        // Terms
        $('#event_terms').text(ev.terms_and_conditions || 'Standard exhibition terms apply.');
    });
    
    $('#event_space_id').change(function() {
        let spaceId = $(this).val();
        let ev = eventsData.find(e => e.id == $('#event_id').val());
        let space = ev ? ev.spaces.find(s => s.id == spaceId) : null;

        if (space) {
            let vipPrice = space.vip_ticket_price || 100.00;
            let genPrice = space.general_ticket_price || 25.00;
            let delPrice = space.delegate_ticket_price || 50.00;

            let vipAvail = space.vip_tickets_available !== undefined ? space.vip_tickets_available : (space.vip_tickets_quota || 50);
            let genAvail = space.general_tickets_available !== undefined ? space.general_tickets_available : (space.general_tickets_quota || 200);
            let delAvail = space.delegate_tickets_available !== undefined ? space.delegate_tickets_available : (space.delegate_tickets_quota || 100);

            $('#vip_price_label').text('$' + parseFloat(vipPrice).toFixed(2) + ' / pass');
            $('#general_price_label').text('$' + parseFloat(genPrice).toFixed(2) + ' / pass');
            $('#delegate_price_label').text('$' + parseFloat(delPrice).toFixed(2) + ' / pass');

            $('#vip_available_label').text(vipAvail + ' tickets left');
            $('#general_available_label').text(genAvail + ' tickets left');
            $('#delegate_available_label').text(delAvail + ' tickets left');

            calculateTicketTotal();
        }
        
        let posHtml = '<option value="" selected>No preference / Standard</option>';
        if(space && space.positions) {
            space.positions.forEach(p => {
                posHtml += `<option value="${p.id}">${p.name} (+$${p.premium_fee})</option>`;
            });
        }
        $('#space_position_id').html(posHtml);
    });
    
    // Auto calculate area from Width x Length
    $('#width, #length').on('input', function() {
        let w = parseFloat($('#width').val()) || 0;
        let l = parseFloat($('#length').val()) || 0;
        let area = w * l;
        if (area > 0) {
            $('#custom_area').val(area);
            $('#calculated_area').val(area);
        }
    });

    // Auto calculate Width x Length from Custom Square Meters input
    $('#custom_area').on('input', function() {
        let area = parseFloat($(this).val()) || 0;
        if (area > 0) {
            let side = Math.round(Math.sqrt(area) * 10) / 10;
            if (!$('#width').is(':focus')) $('#width').val(side);
            if (!$('#length').is(':focus')) $('#length').val(side);
            $('#calculated_area').val(area);
        }
    });

    // Accept terms toggle
    $('#terms_accepted').change(function() {
        $('#btnSubmit').prop('disabled', !$(this).is(':checked'));
    });

    function prepareReview() {
        let ev = eventsData.find(e => e.id == $('#event_id').val());
        $('#rev_event').text(ev ? ev.name : '');
        
        $('#rev_company').text($('input[name="company_name"]').val());
        $('#rev_contact').text($('input[name="contact_person"]').val() + ' (' + $('input[name="email"]').val() + ')');
        
        let vip = parseInt($('#vip_tickets_count').val()) || 0;
        let gen = parseInt($('#general_tickets_count').val()) || 0;
        let del = parseInt($('#delegate_tickets_count').val()) || 0;
        let ticketsText = $('#people_count').val() + ' people (';
        let parts = [];
        if (vip > 0) parts.push(vip + ' VIP');
        if (gen > 0) parts.push(gen + ' General');
        if (del > 0) parts.push(del + ' Delegate');
        ticketsText += parts.join(', ') + ')';
        $('#rev_people').text(ticketsText);
        
        let spaceText = $('#event_space_id option:selected').text();
        let standText = $('#stand_type_id option:selected').text();
        $('#rev_space').html(spaceText + '<br>' + standText);
        $('#rev_dimensions').text($('#width').val() + 'm x ' + $('#length').val() + 'm (' + $('#calculated_area').val() + ' m²)');
        
        let extras = '';
        if (vip > 0 || gen > 0 || del > 0) {
            extras += `<li><strong>Ticket Passes:</strong> ${ticketsText}</li>`;
        }
        $('#furniture_list input').each(function() {
            let q = parseInt($(this).val());
            if(q > 0) {
                extras += '<li>' + q + 'x ' + $(this).closest('tr').find('td:first').text() + '</li>';
            }
        });
        $('#services_list input').each(function() {
            let q = parseInt($(this).val());
            if(q > 0) {
                extras += '<li>' + q + 'x ' + $(this).closest('tr').find('td:first').text() + '</li>';
            }
        });
        if(extras === '') extras = '<li>None selected</li>';
        $('#rev_extras').html(extras);
    }
</script>
@endsection
