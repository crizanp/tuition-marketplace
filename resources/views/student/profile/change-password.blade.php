@extends('layouts.app')

@section('content')
<div class="dashboard-container py-5">
    <div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm profile-card">
                <div class="card-header profile-card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Change Password
                    </h4>
                </div>
                <div class="card-body profile-card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.profile.update-password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label text-light">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" 
                                       name="current_password" required>
                                <button class="btn btn-outline-dark-primary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" 
                                       name="password" required minlength="8">
                                <button class="btn btn-outline-dark-primary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <small class="text-light">Password must be at least 8 characters long</small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-light">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required minlength="8">
                                <button class="btn btn-outline-dark-primary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Password
                            </button>
                            <a href="{{ route('student.profile.index') }}" class="btn btn-outline-dark-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow-sm mt-4 profile-card">
                <div class="card-header profile-card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Security Tips
                    </h6>
                </div>
                <div class="card-body profile-card-body">
                    <ul class="mb-0 text-light">
                        <li>Use a strong password with at least 8 characters</li>
                        <li>Include uppercase letters, lowercase letters, and numbers</li>
                        <li>Avoid using personal information in your password</li>
                        <li>Don't share your password with anyone</li>
                        <li>Change your password regularly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    background-color: #0b0b0b;
    min-height: 100vh;
    color: #e9ecef;
}
.container{
    max-width: 1230px;
    margin: 0 auto;
}
.profile-card {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);
    border: 1px solid #bbbbbb;
    border-radius: 8px;
    padding: 20px;
    color: #e6e6e6;
}
.profile-card-header {
    background: transparent;
    border-bottom: none;
    color: #ffffff;
}
.profile-card-body {
    background: transparent;
}
.form-control {
    background: #0d0d0d;
    color: #e6e6e6;
    border: 1px solid #222222;
}
.form-control::placeholder { color: #9a9a9a; opacity: 1; }
.btn-dark-primary {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);
    color: #fff;
    border: 1px solid #bbbbbb;
}
.btn-outline-dark-primary {
    background: transparent;
    color: #e6e6e6;
    border: 1px solid #bbbbbb;
}
.btn-outline-dark-primary:hover, .btn-dark-primary:hover { opacity: 0.95; }
</style>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endsection
