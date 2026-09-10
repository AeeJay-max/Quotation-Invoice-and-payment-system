@extends('customer-layout')

@section('title', 'Change Password Required')

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-warning text-dark text-center py-4">
                        <i class="fas fa-key fa-3x mb-2"></i>
                        <h4 class="font-weight-bold mb-0">Password Update Required</h4>
                        <p class="small text-dark mb-0 opacity-75">Your account was registered by an Administrator with a default password.</p>
                    </div>

                    <div class="card-body p-4">
                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('warning') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> <strong>Validation Errors:</strong>
                                <ul class="mb-0 mt-1 pl-3">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        <p class="text-muted small">
                            For security purposes, you must create a new secure password before accessing your Exhibitor Portal, viewing invoices, or submitting applications.
                        </p>

                        <form action="{{ route('customer.must-change-password.update') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark"><i class="fas fa-lock text-muted mr-1"></i> New Password *</label>
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter new password (min. 6 characters)" required minlength="6">
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark"><i class="fas fa-check-double text-muted mr-1"></i> Confirm New Password *</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="Confirm your new password" required minlength="6">
                            </div>

                            <button type="submit" class="btn btn-warning btn-block btn-lg font-weight-bold text-dark shadow-sm">
                                <i class="fas fa-shield-alt mr-2"></i> Update Password & Proceed
                            </button>
                        </form>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted">Need help? Contact MOSRAC Secretariat at <strong>+263 772 394036</strong></small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
