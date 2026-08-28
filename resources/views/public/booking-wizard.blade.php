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
        <h1 class="display-4 font-weight-bold"><i class="fas fa-cubes text-warning"></i> Exhibition Quotation Request</h1>
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
                <h4 class="card-title">3. Number of People Attending</h4>
                <div class="form-group text-center my-5">
                    <label class="font-weight-bold d-block" style="font-size: 1.5rem;">How many people will be attending this exhibition?</label>
                    <div class="d-inline-flex align-items-center mt-3">
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="updatePeople(-1)"><i class="fas fa-minus"></i></button>
                        <input type="number" name="people_count" id="people_count" class="form-control text-center mx-2" style="width: 100px; font-size: 1.5rem; height: 50px;" required min="1">
                        <button type="button" class="btn btn-outline-secondary btn-lg" onclick="updatePeople(1)"><i class="fas fa-plus"></i></button>
                    </div>
                    <small class="form-text text-muted mt-2">Please select a value greater than 0.</small>
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
                    <div class="col-md-4 form-group">
                        <label>Width (Metres) *</label>
                        <input type="number" step="0.5" min="1" name="width" id="width" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Length (Metres) *</label>
                        <input type="number" step="0.5" min="1" name="length" id="length" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Calculated Area</label>
                        <input type="text" id="calculated_area" class="form-control font-weight-bold bg-light" readonly>
                    </div>
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
                alert('Please select a valid number of people (minimum 1).');
                isValid = false;
            }
        } else if (step === 4) {
            if (!$('#event_space_id').val() || !$('#stand_type_id').val() || !$('#width').val() || !$('#length').val()) {
                alert('Please complete all space and stand configurations.');
                isValid = false;
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

    function updatePeople(change) {
        let input = $('#people_count');
        let val = parseInt(input.val() || 0);
        let newVal = val + change;
        if (newVal > 0) {
            input.val(newVal);
        }
    }
    
    // Dynamic loading based on Event ID
    $('#event_id').change(function() {
        let eventId = $(this).val();
        let ev = eventsData.find(e => e.id == eventId);
        if(!ev) return;
        
        // Populate Spaces
        let spacesHtml = '<option value="" selected disabled>-- Select Space --</option>';
        ev.spaces.forEach(s => {
            spacesHtml += `<option value="${s.id}">${s.name} ($${s.price_per_sqm}/m²)</option>`;
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
        let space = ev.spaces.find(s => s.id == spaceId);
        
        let posHtml = '<option value="" selected>No preference / Standard</option>';
        if(space && space.positions) {
            space.positions.forEach(p => {
                posHtml += `<option value="${p.id}">${p.name} (+$${p.premium_fee})</option>`;
            });
        }
        $('#space_position_id').html(posHtml);
    });
    
    // Auto calculate area
    $('#width, #length').on('input', function() {
        let w = parseFloat($('#width').val()) || 0;
        let l = parseFloat($('#length').val()) || 0;
        $('#calculated_area').val((w * l) + ' m²');
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
        
        $('#rev_people').text($('#people_count').val() + ' people');
        
        let spaceText = $('#event_space_id option:selected').text();
        let standText = $('#stand_type_id option:selected').text();
        $('#rev_space').html(spaceText + '<br>' + standText);
        $('#rev_dimensions').text($('#width').val() + 'm x ' + $('#length').val() + 'm (' + $('#calculated_area').val() + ')');
        
        let extras = '';
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
