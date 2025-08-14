@extends('layouts.app')


@section('content')
<div class="dashboard-container py-5">
    <div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm profile-card">
                <div class="card-header profile-card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        My Profile
                    </h4>
                </div>
                <div class="card-body profile-card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-avatar mb-3">
                                <div style="width:120px; height:120px; margin:0 auto; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #000000, #111111); border-radius:50%; border:4px solid #111111; box-shadow:0 6px 25px rgba(0,0,0,0.7);">
                                    <i class="fas fa-user-graduate" style="color:white; font-size:3rem;"></i>
                                </div>
                            </div>
                            <h5 style="color:#ffffff">{{ $student->name }}</h5>
                            <span class="badge badge-student">Student</span>
                            @if($student->email_verified_at)
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Verified
                                        </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <div class="profile-info">
                                <div class="info-group mb-3">
                                    <label class="text-light small fw-bold">EMAIL ADDRESS</label>
                                    <div class="info-value">{{ $student->email }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="text-light small fw-bold">PHONE NUMBER</label>
                                    <div class="info-value">{{ $student->phone ?? 'Not provided' }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="text-light small fw-bold">GRADE LEVEL</label>
                                    <div class="info-value">{{ $student->grade_level ?? 'Not specified' }}</div>
                                </div>

                                @if($student->preferred_subjects && count($student->preferred_subjects) > 0)
                                <div class="info-group mb-3">
                                    <label class="text-light small fw-bold">PREFERRED SUBJECTS</label>
                                    <div class="info-value">
                                        @foreach($student->preferred_subjects as $subject)
                                            <span class="badge bg-light text-dark me-1 mb-1">{{ $subject }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="info-group mb-3">
                                    <label class="text-light small fw-bold">MEMBER SINCE</label>
                                    <div class="info-value">{{ $student->created_at->format('F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('student.profile.edit') }}" class="btn btn-dark-primary">
                            <i class="fas fa-edit me-2"></i>
                            Edit Profile
                        </a>
                        <a href="{{ route('student.profile.change-password') }}" class="btn btn-outline-dark-secondary">
                            <i class="fas fa-lock me-2"></i>
                            Change Password
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-dark-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Statistics -->
            <div class="card shadow-sm mt-4 profile-card">
                <div class="card-header profile-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body profile-card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number stat-total">{{ $student->vacancies()->count() }}</div>
                                <div class="stat-label">Total Vacancies</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ $student->vacancies()->pending()->count() }}</div>
                                <div class="stat-label">Pending Vacancies</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ $student->vacancies()->approved()->count() }}</div>
                                <div class="stat-label">Approved Vacancies</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-danger">{{ $student->vacancies()->rejected()->count() }}</div>
                                <div class="stat-label">Rejected Vacancies</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    background-color: #fffafaff;
    min-height: 100vh;
    color: #e9ecef;
}
.container{
    max-width: 1230px;
    margin: 0 auto;
}
.profile-avatar {
    position: relative;
}

.profile-card {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);;
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

.info-group {
    border-bottom: 1px solid #bbbbbb;
    padding-bottom: 10px;
}

.info-group:last-child {
    border-bottom: none;
}

.info-value {
    font-size: 16px;
    color: #e6e6e6;
    font-weight: 500;
}

.stat-item {
    padding: 20px;
    border-radius: 8px;
    background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
    box-shadow: 0 6px 20px rgba(0,0,0,0.6);
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    line-height: 1;
    display: block;
    color: #ffffff;
}

.stat-total {
    color: #ffffff;
}

.stat-label {
    font-size: 14px;
    color: #9a9a9a;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 5px;
}

.btn-dark-primary {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);
    color: #fff;
    border: 1px solid #bbbbbb;
}

.btn-dark-primary:hover {
    opacity: 0.95;
}

.btn-outline-dark-secondary {
    background: transparent;
    color: #e6e6e6;
    border: 1px solid #bbbbbb;
}

.btn-outline-dark-primary {
    background: transparent;
    color: #e6e6e6;
    border: 1px solid #bbbbbb;
}
</style>
@endsection
