@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>
                        My Profile
                    </h4>
                </div>
                <div class="card-body">
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
                                <div style="width:120px; height:120px; margin:0 auto; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #3498db, #2980b9); border-radius:50%; border:4px solid #fff; box-shadow:0 4px 15px rgba(52,152,219,0.3);">
                                    <i class="fas fa-user-graduate" style="color:white; font-size:3rem;"></i>
                                </div>
                            </div>
                            <h5>{{ $student->name }}</h5>
                            <span class="badge bg-primary">Student</span>
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
                                    <label class="text-muted small fw-bold">EMAIL ADDRESS</label>
                                    <div class="info-value">{{ $student->email }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="text-muted small fw-bold">PHONE NUMBER</label>
                                    <div class="info-value">{{ $student->phone ?? 'Not provided' }}</div>
                                </div>

                                <div class="info-group mb-3">
                                    <label class="text-muted small fw-bold">GRADE LEVEL</label>
                                    <div class="info-value">{{ $student->grade_level ?? 'Not specified' }}</div>
                                </div>

                                @if($student->preferred_subjects && count($student->preferred_subjects) > 0)
                                <div class="info-group mb-3">
                                    <label class="text-muted small fw-bold">PREFERRED SUBJECTS</label>
                                    <div class="info-value">
                                        @foreach($student->preferred_subjects as $subject)
                                            <span class="badge bg-light text-dark me-1 mb-1">{{ $subject }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="info-group mb-3">
                                    <label class="text-muted small fw-bold">MEMBER SINCE</label>
                                    <div class="info-value">{{ $student->created_at->format('F Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('student.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Edit Profile
                        </a>
                        <a href="{{ route('student.profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-lock me-2"></i>
                            Change Password
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Statistics -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-primary">{{ $student->vacancies()->count() }}</div>
                                <div class="stat-label">Total Vacancies</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-warning">{{ $student->vacancies()->pending()->count() }}</div>
                                <div class="stat-label">Pending</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-success">{{ $student->vacancies()->approved()->count() }}</div>
                                <div class="stat-label">Approved</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="stat-item">
                                <div class="stat-number text-danger">{{ $student->vacancies()->rejected()->count() }}</div>
                                <div class="stat-label">Rejected</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    position: relative;
}

.info-group {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}

.info-group:last-child {
    border-bottom: none;
}

.info-value {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 500;
}

.stat-item {
    padding: 20px;
    border-radius: 8px;
    background: #f8f9fa;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    line-height: 1;
    display: block;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 5px;
}
</style>
@endsection
