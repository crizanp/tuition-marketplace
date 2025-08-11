
@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="dashboard-container py-5">
    <div class="container">
        <!-- Search Section at Top -->
        <div class="dashboard-search mb-5">
            @include('components.search-bar', [
                'action' => route('search.tutors'),
                'placeholder' => 'Search for tutors, subjects, or skills...',
                'size' => 'normal'
            ])
        </div>
        
        <div class="row">
            <!-- Left Sidebar - Profile Information (1/3) -->
            <div class="col-lg-4 col-md-5">
                <div class="profile-sidebar">
                    <div class="welcome-header d-flex align-items-center justify-content-center" style="gap: 18px;">
                        @php
                            $student = Auth::user();
                            $pendingVacancies = $student->vacancies()->pending()->count();
                            $approvedVacancies = $student->vacancies()->approved()->count();
                        @endphp
                        
                        <div class="profile-picture-circle" style="flex-shrink:0;">
                            <div style="width:80px; height:80px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, #3498db, #2980b9); border-radius:50%; border:3px solid #3498db;">
                                <i class="fas fa-user-graduate" style="color:white; font-size:2rem;"></i>
                            </div>
                        </div>
                        
                        <div class="text-start">
                            <h5 class="text-primary mb-1 d-flex align-items-center" style="gap:6px;">
                                {{ $student->name }}
                                @if($student->email_verified_at)
                                    <i class="fas fa-check-circle" style="color:#27ae60; font-size:0.8em;" title="Verified"></i>
                                @endif
                            </h5>
                            <span class="badge badge-status badge-student">
                                Student
                            </span>
                        </div>
                    </div>

                    <div class="profile-card mt-4">
                        <h6 class="profile-title">Profile Information</h6>
                        
                        <div class="profile-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <label>Email Address</label>
                                <span>{{ $student->email }}</span>
                            </div>
                        </div>

                        <div class="profile-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <label>Phone Number</label>
                                <span>{{ $student->phone ?? 'Not provided' }}</span>
                            </div>
                        </div>

                        <div class="profile-item">
                            <i class="fas fa-graduation-cap"></i>
                            <div>
                                <label>Grade Level</label>
                                <span>{{ $student->grade_level ?? 'Not specified' }}</span>
                            </div>
                        </div>

                        @if($student->preferred_subjects && count($student->preferred_subjects) > 0)
                        <div class="profile-item">
                            <i class="fas fa-book"></i>
                            <div>
                                <label>Preferred Subjects</label>
                                <span class="subjects-text">{{ implode(', ', $student->preferred_subjects) }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Quick Stats -->
                        <div class="stats-section mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $pendingVacancies }}</span>
                                        <span class="stat-label">Pending</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $approvedVacancies }}</span>
                                        <span class="stat-label">Approved</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Dashboard Grid (2/3) -->
            <div class="col-lg-8 col-md-7">
                <div class="dashboard-grid">
                    <!-- First Row - Main Actions -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('student.profile.index') }}" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <h6>Profile</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="{{ route('student.vacancies.index') }}" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <h6>My Vacancies</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <h6>Settings</h6>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Second Row - Post Vacancy -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <a href="{{ route('student.vacancies.create') }}" class="dashboard-card-link">
                                <div class="dashboard-card post-vacancy-card">
                                    <div class="card-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <h5>POST A VACANCY NOW</h5>
                                    <p class="card-subtitle">Find the perfect tutor for your learning needs</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Third Row - Browse Features -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h6>Browse Tutors</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h6>My Tutors</h6>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Fourth Row - More Features -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <h6>Schedule</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card student-card">
                                    <div class="card-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h6>Progress</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    background-color: #ffffffff;
    min-height: 100vh;
}

/* Left Sidebar Styles */
.profile-sidebar {
    background: white;
    border-radius: 12px;
    padding: 30px;
    border: 1px solid #e9ecef;
    height: fit-content;
}

.welcome-header {
    text-align: center;
}

.welcome-header h4 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 5px;
}

.welcome-header h5 {
    color: #3498db;
    font-weight: 700;
}

.badge-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-student {
    background-color: #3498db;
    color: white;
}

.profile-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
}

.profile-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.profile-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 18px;
    padding: 12px 0;
}

.profile-item i {
    color: #3498db;
    width: 20px;
    margin-right: 15px;
    margin-top: 2px;
    font-size: 16px;
}

.profile-item div {
    flex: 1;
}

.profile-item label {
    display: block;
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.profile-item span {
    color: #2c3e50;
    font-weight: 500;
    display: block;
}

.subjects-text {
    font-size: 14px;
    line-height: 1.5;
}

/* Stats Section */
.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #3498db;
    line-height: 1;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
}

/* Dashboard Card Links */
.dashboard-card-link {
    text-decoration: none;
    display: block;
}

.dashboard-card-link:hover {
    text-decoration: none;
}

.dashboard-card {
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 6px 25px rgba(52, 152, 219, 0.2);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 35px rgba(52, 152, 219, 0.3);
}

.student-card {
    background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);
}

.card-icon {
    font-size: 50px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.dashboard-card h6 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 0;
    color: white;
}

.dashboard-card h5 {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 10px;
    color: white;
    letter-spacing: 1px;
}

.card-subtitle {
    font-size: 14px;
    margin-bottom: 0;
    opacity: 0.9;
}

/* Post Vacancy Card */
.post-vacancy-card {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    padding: 40px 30px;
    position: relative;
    overflow: hidden;
}

.post-vacancy-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.03),
        transparent,
        rgba(255, 255, 255, 0.03),
        transparent
    );
    background-size: 30px 30px;
    animation: textureMove 20s linear infinite;
    pointer-events: none;
}

.post-vacancy-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.08),
        transparent
    );
    animation: shimmer 3s ease-in-out infinite;
    pointer-events: none;
}

.post-vacancy-card .card-icon {
    font-size: 60px;
    position: relative;
    z-index: 2;
    animation: iconFloat 6s ease-in-out infinite;
}

.post-vacancy-card h5, .post-vacancy-card p {
    position: relative;
    z-index: 2;
}

@keyframes textureMove {
    0% {
        transform: translateX(-30px) translateY(-30px);
    }
    100% {
        transform: translateX(30px) translateY(30px);
    }
}

@keyframes shimmer {
    0% {
        left: -100%;
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        left: 100%;
        opacity: 0;
    }
}

@keyframes iconFloat {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-8px);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-grid {
        padding-left: 0;
        margin-top: 20px;
    }
    
    .profile-sidebar {
        margin-bottom: 20px;
    }
}

@media (max-width: 576px) {
    .dashboard-card {
        padding: 20px;
    }
    
    .card-icon {
        font-size: 30px;
    }
    
    .post-vacancy-card {
        padding: 25px;
    }
}

/* Dashboard Search Section */
.dashboard-search {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.dashboard-search .search-container {
    margin-bottom: 0;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
}
</style>
@endsection