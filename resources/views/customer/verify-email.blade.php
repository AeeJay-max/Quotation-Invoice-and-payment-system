@extends('customer-layout')

@section('title', 'Verify Email Address')

@section('content')
<div class="content-wrapper p-4">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-envelope-open-text fa-3x mb-2"></i>
                        <h4 class="font-weight-bold mb-0">Verify Your Email Address</h4>
                        <p class="small text-white-50 mb-0">Account Activation Step 2 of 2</p>
                    </div>

                    <div class="card-body p-4 text-center">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show text-left" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('warning') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
                                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        <p class="lead text-dark font-weight-bold mb-2">
                            Welcome, {{ $user->name }}!
                        </p>
                        <p class="text-muted mb-4">
                            We have sent a verification link to your registered email address:<br>
                            <span class="badge badge-info p-2 font-weight-bold" style="font-size: 1rem;">{{ $user->email }}</span>
                        </p>

                        <div class="p-3 bg-light rounded border mb-4 text-left">
                            <h6 class="font-weight-bold text-secondary mb-2"><i class="fas fa-info-circle text-info mr-1"></i> What happens next?</h6>
                            <ul class="text-muted small mb-0 pl-3">
                                <li>Check your inbox for the verification email.</li>
                                <li>Click the link in the email to verify your ownership.</li>
                                <li>Once verified, your account will instantly unlock all Exhibitor features, invoices, stand applications, and badge management.</li>
                            </ul>
                        </div>

                        <form action="{{ route('customer.verify-email.send') }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-block btn-lg font-weight-bold">
                                <i class="fas fa-paper-plane mr-2"></i> Resend Email Verification Link
                            </button>
                        </form>

                        @php
                            $instantVerifyUrl = route('customer.verify-email.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
                        @endphp
                        
                        <div class="pt-2 border-top">
                            <p class="small text-muted mb-2">Or verify instantly right now:</p>
                            <a href="{{ $instantVerifyUrl }}" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm">
                                <i class="fas fa-check-double mr-2"></i> Complete Account Verification Now
                            </a>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <small class="text-muted">Registered through Admin Dashboard • MOSRAC Exhibitor Portal</small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
