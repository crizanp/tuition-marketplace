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
        
        <!-- Verification Error -->
        <div class="col-12 p-5 d-flex flex-column justify-content-center text-center">
            <div class="mb-4">
                <i class="fa-solid fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
            </div>
            
            <h3 class="fw-bold text-black mb-3">Verification Link Invalid</h3>
            <p class="text-muted mb-4">The verification link you clicked is invalid or has expired. Please log in to your student account to request a new verification email.</p>

            <div class="d-grid gap-2 mb-4">
                <a href="/student/login" class="btn bg-orange-500 btn-lg shadow-sm">
                    <i class="fa-solid fa-sign-in-alt me-2"></i>Login to Your Account
                </a>
            </div>

            <div class="text-center">
                <p class="small text-muted mb-2">Don't have an account yet?</p>
                <a href="/student/register" class="text-orange-500 text-decoration-none">
                    <i class="fa-solid fa-user-plus me-2"></i>Create New Student Account
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
