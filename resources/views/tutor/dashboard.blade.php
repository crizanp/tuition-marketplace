@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="dashboard-container py-5">
    <div class="container">
        <div class="row">
            <!-- Left Sidebar - Profile Information (1/3) -->
            <div class="col-lg-4 col-md-5">
                <div class="profile-sidebar">
                    <div class="welcome-header d-flex align-items-center justify-content-center" style="gap: 18px;">
                        @php
                            $tutor = Auth::guard('tutor')->user();
                            $kyc = \App\Models\TutorKyc::where('tutor_id', $tutor->id)->where('status', 'approved')->first();
                        @endphp
                        @if($tutor->status === 'active' && $kyc && $kyc->profile_photo)
                            <div class="profile-picture-circle" style="flex-shrink:0;">
                                <img src="{{ Storage::url($kyc->profile_photo) }}" alt="Profile Photo" style="width:80px; height:80px; object-fit:cover; border-radius:50%; border:3px solid #e67e22;">
                            </div>
                        @endif
                        <div class="text-start">
                            <!-- <h4 class="mb-2">Welcome Back!</h4> -->
                            <h5 class="text-primary mb-1 d-flex align-items-center" style="gap:6px;">
                                {{ $tutor->name }}
                                @if($tutor->status === 'active' && $kyc)
                                    <i class="fas fa-check-circle" style="color:#2196f3; font-size:0.8em;" title="Verified"></i>
                                @endif
                            </h5>
                            <span class="badge badge-status badge-{{ strtolower($tutor->status) }}">
                                {{ ucfirst($tutor->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="profile-card mt-4">
                        <h6 class="profile-title">Profile Information</h6>
                        
                        <div class="profile-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <label>Email Address</label>
                                <span>{{ Auth::guard('tutor')->user()->email }}</span>
                            </div>
                        </div>

                        <div class="profile-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <label>Phone Number</label>
                                <span>{{ Auth::guard('tutor')->user()->phone ?? 'Not provided' }}</span>
                            </div>
                        </div>

                        <div class="profile-item">
                            <i class="fas fa-dollar-sign"></i>
                            <div>
                                <label>Hourly Rate</label>
                                <span>Rs. {{ Auth::guard('tutor')->user()->hourly_rate ?? 'Not set' }}</span>
                            </div>
                        </div>

                        <div class="profile-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <label>Bio</label>
                                <span class="bio-text">{{ Auth::guard('tutor')->user()->bio ?? 'Not provided' }}</span>
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
                            <a href="{{ route('tutor.profile.index') }}" class="dashboard-card-link">
                                <div class="dashboard-card">
                                    <div class="card-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <h6>Profile</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="{{ route('tutor.kyc.show') }}" class="dashboard-card-link">
                                <div class="dashboard-card">
                                    <div class="card-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h6>KYC</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card">
                                    <div class="card-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <h6>Settings</h6>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Second Row - Post Job -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card post-job-card">
                                    <div class="card-icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5>POST YOUR JOB NOW</h5>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Third Row - Additional Features -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card">
                                    <div class="card-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h6>My Students</h6>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 mb-3">
                            <a href="#" class="dashboard-card-link">
                                <div class="dashboard-card">
                                    <div class="card-icon">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                    <h6>Promote</h6>
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
    color: #e67e22;
    font-weight: 700;
}

.badge-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-active {
    background-color: #27ae60;
    color: white;
}

.badge-pending {
    background-color: #f39c12;
    color: white;
}

.badge-inactive {
    background-color: #e74c3c;
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
    color: #e67e22;
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

.bio-text {
    font-size: 14px;
    line-height: 1.5;
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
    background: linear-gradient(135deg, #d35400 0%, #e67e22 100%);
    color: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 6px 25px rgba(230, 126, 34, 0.3);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 35px rgba(230, 126, 34, 0.4);
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
    margin-bottom: 0;
    color: white;
    letter-spacing: 1px;
}

/* Post Job Card */
.post-job-card {
    padding: 40px 30px;
    position: relative;
    overflow: hidden;
}

.post-job-card::before {
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

.post-job-card::after {
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

.post-job-card .card-icon {
    font-size: 60px;
    position: relative;
    z-index: 2;
    animation: iconFloat 6s ease-in-out infinite;
}

.post-job-card h5 {
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
    
    .card-content-horizontal {
        flex-direction: column;
        text-align: center;
    }
    
    .card-icon-large {
        margin-right: 0;
        margin-bottom: 20px;
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
    
    .post-job-card {
        padding: 25px;
    }
}
</style>
@endsection