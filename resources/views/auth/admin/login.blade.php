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
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 900px;">
        
        <!-- Left: Logo Only -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center p-4 bg-white">
            <img src="/images/logo.png" alt="Admin Logo" class="img-fluid" style="max-width: 300px;">
        </div>

        <!-- Right: Admin Login Form -->
        <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
            <h3 class="text-center fw-bold text-black mb-3">Admin Login</h3>
            <p class="text-muted text-center mb-4">Use your email to access the admin panel</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{!! $error !!}</p>
                    @endforeach
                </div>
            @endif

            <!-- Hidden logout-all form -->
            @if(session('logout_all_form'))
                <form id="logout-all-form" action="{{ route('logout.all') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif

            <form method="POST" action="/admin/login">
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

            <div class="text-center mt-4 small text-muted">
                <a href="/student/login" class="text-decoration-none me-2">Student Login</a> |
                <a href="/tutor/login" class="text-decoration-none ms-2">Tutor Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
