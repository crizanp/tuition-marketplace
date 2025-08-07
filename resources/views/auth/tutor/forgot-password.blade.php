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
        
        <!-- Forgot Password Form -->
        <div class="col-12 p-5 d-flex flex-column justify-content-center">
            <div class="text-center mb-4">
                <i class="fa-solid fa-lock text-orange-500" style="font-size: 3rem;"></i>
            </div>
            
            <h3 class="text-center fw-bold text-black mb-3">Forgot Your Password?</h3>
            <p class="text-muted text-center mb-4">No worries! Enter your email address and we'll send you a link to reset your password.</p>

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

            <form method="POST" action="/tutor/forgot-password">
                @csrf
                <div class="mb-4 position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control ps-5 py-3" placeholder="Enter your email address" required value="{{ old('email') }}">
                </div>

                <button type="submit" class="btn bg-orange-500 btn-lg w-100 shadow-sm mb-3">Send Reset Link</button>
            </form>

            <div class="text-center">
                <a href="/tutor/login" class="text-orange-500 text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
