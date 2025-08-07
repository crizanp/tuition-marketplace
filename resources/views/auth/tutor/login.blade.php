@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endpush

@section('content')
@php
    if (Auth::guard('tutor')->check()) {
        header('Location: ' . url('/tutor/dashboard'));
        exit;
    }
@endphp
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
<div class=" py-5 min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #6d86f6ff 0%, #6d86f6ff 100%);">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 900px;">
        
        <!-- Left Illustration -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center p-4 bg-white position-relative">
            <video class="w-100 rounded-4" style="max-width: 450px; min-height: 320px; object-fit: cover;" src="/images/icon/teaching-animation.mp4" autoplay loop muted playsinline></video>
            
        </div>

        <!-- Right: Login Form -->
        <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
            <h3 class="text-center fw-bold text-black mb-3">Tutor Login</h3>
            <p class="text-muted text-center mb-4">Use your email to sign into your account</p>

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

            <form method="POST" action="/tutor/login">
                @csrf
                <div class="mb-3 position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control ps-5 py-3" placeholder="Enter your email" required value="{{ old('email') }}">
                </div>

                <div class="mb-3 position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-3 text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control ps-5 py-3" placeholder="Enter your password" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn bg-orange-500 btn-lg w-100 shadow-sm">Log In</button>
            </form>

            <div class="text-center mt-3">
                <a href="/tutor/forgot-password" class="text-orange-500 text-decoration-none small">Forgot your password?</a>
            </div>

            <div class="text-center mt-4">
                <span class="text-secondary">Don't have an account?</span>
                <a href="/tutor/register" class="text-orange-500 fw-semibold text-decoration-none">Register here</a>
            </div>

            <div class="text-center mt-3 small text-muted">
                <a href="/student/login" class="text-decoration-none me-2">Student Login</a> |
                <a href="/admin/login" class="text-decoration-none ms-2">Admin Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
