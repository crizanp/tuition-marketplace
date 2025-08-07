@extends('layouts.app')

@section('content')
<div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white">
        <!-- Left: Admin Illustration and Text -->
        <div class="col-lg-7 d-flex flex-column justify-content-center align-items-center p-5 border-end border-2 bg-primary bg-gradient text-white">
            <h2 class="display-5 fw-bold mb-5 text-center lh-sm">Powerful Dashboard<br>for Admin Management</h2>
            <div class="d-flex flex-column align-items-center justify-content-center gap-4 mb-5 fs-4 text-center">
                <div class="bg-white text-primary rounded-4 shadow px-4 py-3 fw-semibold" style="min-width: 240px;">
                    Manage Tutors & Students
                </div>
                <div class="bg-white text-primary rounded-4 shadow px-4 py-3 fw-semibold" style="min-width: 240px;">
                    Handle Inquiries & Reviews
                </div>
                <div class="bg-white text-primary rounded-4 shadow px-4 py-3 fw-semibold" style="min-width: 240px;">
                    Review & Approve Registrations
                </div>
            </div>
            <div class="d-flex justify-content-center mb-4 w-100">
                <img src="/images/admin-login.jpg" alt="Admin Panel" class="rounded-4 shadow-lg w-75" style="max-width: 350px;">
            </div>
            <div class="bg-white text-success fw-bold fs-4 text-center rounded-3 py-3 shadow w-100 mt-2">
                Secure Access to Full Platform Control
            </div>
        </div>

        <!-- Right: Admin Login Form -->
        <div class="col-lg-5 d-flex flex-column justify-content-center align-items-center px-4 py-5">
            <div class="w-100" style="max-width: 400px;">
                <h2 class="fw-bold text-primary mb-4 text-center">Admin Login</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/admin/login" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email:</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-control form-control-lg">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password:</label>
                        <input type="password" name="password" required class="form-control form-control-lg">
                    </div>
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberCheck">
                            <label class="form-check-label fw-medium" for="rememberCheck">Remember Me</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Login</button>
                </form>

                <div class="text-center mt-4">
                    <a href="/student/login" class="text-decoration-none text-secondary">Student Login</a> |
                    <a href="/tutor/login" class="text-decoration-none text-secondary">Tutor Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
