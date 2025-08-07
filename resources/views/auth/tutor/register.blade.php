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

        .bg-orange-500:hover,
        .bg-orange-500:focus {
            background-color: #e07c1e !important;
            color: #fff !important;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #000;
        }

        .input-with-icon input {
            padding-left: 32px !important;
            height: 38px;
        }
    </style>

    <div class="py-5 min-vh-100 d-flex align-items-center justify-content-center"
        style="background: linear-gradient(135deg, #6d86f6ff 0%, #6d86f6ff 100%);">
        <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 900px;">

            <!-- Left: Tutor Video -->
            <div
                class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center p-4 bg-white position-relative">
                <video class="w-100 rounded-4" style="max-width: 450px; min-height: 320px; object-fit: cover;"
                    src="/images/icon/teaching-animation.mp4" autoplay loop muted playsinline></video>
            </div>

            <!-- Right: Tutor Registration Form -->
            <div class="col-md-6 p-5 d-flex flex-column justify-content-center">
                <h3 class="text-center fw-bold text-black mb-3">Tutor Register</h3>
                <p class="text-muted text-center mb-4">Create your tutor profile and start teaching</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/tutor/register">
                    @csrf

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-control"
                            placeholder="Name">
                    </div>

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-control"
                            placeholder="Email">
                    </div>

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Phone">
                    </div>

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" required class="form-control" placeholder="Password">
                    </div>

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" required class="form-control"
                            placeholder="Confirm Password">
                    </div>

                    <div class="mb-3 input-with-icon">
                        <i class="fas fa-info-circle"></i>
                        <input type="text" name="bio" value="{{ old('bio') }}" class="form-control" placeholder="Short Bio">
                    </div>

                    <div class="mb-4 input-with-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <input type="number" name="hourly_rate" step="0.01" value="{{ old('hourly_rate') }}"
                            class="form-control" placeholder="Hourly Rate ($)">
                    </div>

                    <button type="submit" class="btn bg-orange-500 btn-lg w-100 shadow-sm">Register</button>
                </form>


                <div class="text-center mt-4">
                    <a href="/tutor/login" class="text-decoration-none text-orange-500 fw-semibold">Already have an account?
                        Login</a>
                </div>
            </div>
        </div>
    </div>
@endsection