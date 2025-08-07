@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endpush

@section('content')
<style>
    .text-orange-500 {
        color: #f59e42 !important;
    }
    .bg-orange-500 {
        background-color: #f59e42 !important;
        color: #fff !important;
        border: none;
    }
    .bg-orange-500:hover, .bg-orange-500:focus {
        background-color: #e07c1e !important;
        color: #fff !important;
    }
</style>

<div class="py-5 min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6d86f6ff 0%, #6d86f6ff 100%);">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 700px;">
        
        <!-- Email Verification Form -->
        <div class="col-12 p-5 d-flex flex-column justify-content-center text-center">
            <div class="mb-4">
                @if ($errors->any())
                    <i class="fa-solid fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                @else
                    <i class="fa-solid fa-envelope-circle-check text-orange-500" style="font-size: 4rem;"></i>
                @endif
            </div>
            
            <h3 class="fw-bold text-black mb-3">Verify Your Email Address</h3>
            
            @if ($errors->any())
                <p class="text-muted mb-4">
                    <i class="fa-solid fa-exclamation-triangle text-warning me-2"></i>
                    {{ $errors->first() }} Please use the button below to request a new verification email.
                </p>
            @else
                <p class="text-muted mb-4">We've sent a verification link to your email address. Please check your email and click the verification link to activate your tutor account.</p>
            @endif

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/tutor/email/verification-notification" class="mb-4">
                @csrf
                <button type="submit" class="btn bg-orange-500 btn-lg shadow-sm">
                    @if ($errors->any())
                        <i class="fa-solid fa-paper-plane me-2"></i>Send New Verification Email
                    @else
                        <i class="fa-solid fa-redo me-2"></i>Resend Verification Email
                    @endif
                </button>
            </form>

            <div>
                @if ($errors->any())
                    <p class="small text-muted">Click the button above to get a new verification link sent to your email address.</p>
                @else
                    <p class="small text-muted">Didn't receive the email? Check your spam folder or click the resend button above.</p>
                @endif
                <a href="/tutor/logout" class="text-orange-500 text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i>Use a different email address
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
