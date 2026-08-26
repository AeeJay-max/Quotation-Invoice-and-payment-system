@extends('layout')

@section('title', 'Create Event')

@section('content')
<div class="content-wrapper p-4">
    <div class="card card-primary card-outline elevation-2">
        <div class="card-header">
            <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i> Create New Event / Trade Exhibition</h3>
        </div>
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label class="font-weight-bold">Event Name *</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Technology & Innovation Expo 2026" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold">Status *</label>
                        <select name="status" class="form-control form-control-lg" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="registration_open" selected>Registration Open</option>
                            <option value="registration_closed">Registration Closed</option>
                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="font-weight-bold">Event Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Overview of the trade exhibition..."></textarea>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-bold">Start Date *</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-bold">End Date *</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-bold">Currency *</label>
                        <input type="text" name="currency" class="form-control" value="USD" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="font-weight-bold">VAT Rate (%) *</label>
                        <input type="number" step="0.01" name="vat_rate" class="form-control" value="15.00" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Venue Name</label>
                        <input type="text" name="venue" class="form-control" placeholder="International Exhibition Centre">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Country</label>
                        <input type="text" name="country" class="form-control" value="Zimbabwe">
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="font-weight-bold">Terms & Conditions</label>
                        <textarea name="terms_and_conditions" class="form-control" rows="3" placeholder="Exhibition booking terms..."></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Save Event & Configure Spaces</button>
            </div>
        </form>
    </div>
</div>
@endsection
