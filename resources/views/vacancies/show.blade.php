<!-- //vacancy show page -->

@extends('layouts.app')

@section('navbar')
    @include('partials.unified-navbar')
@endsection

@section('title', $vacancy->title . ' - Teaching Vacancy')

@section('content')
<style>
body {
    background: linear-gradient(135deg, #fff5f0 0%, #ffe8dd 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
}

.page-header {
    background: #fcccb4;
    color: #090909;
    padding: 60px 0;
    margin-bottom: 40px;
}

.back-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: #090909;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    margin-bottom: 20px;
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #090909;
    transform: translateY(-2px);
}

.vacancy-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
}

.vacancy-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #090909;
}

.urgency-badge {
    font-weight: 600;
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.urgency-high {
    background: black;
    color: white;
}

.urgency-medium {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #212529;
}

.urgency-low {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
}
.container{
    padding: 0 15px;
    margin: 0 auto;
    max-width: 1200px;
}
/* Main Content */
.content-section {
    max-width: 1200px;
    margin: 0 auto;
    margin-top: -60px;
    position: relative;
    z-index: 10;
}

.main-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.card-header {
    background: #f7931e;
    color: white;
    padding: 25px 30px;
}

.card-body {
    padding: 30px;
}

.quick-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.icon-subject {
    background:black;
    color: white;
}

.icon-budget {
    background: black;
    color: white;
}

.icon-duration {
    background: black;
    color: white;
}

.icon-location {
    background: black;
    color: white;
}

.info-content h6 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 14px;
}

.info-content .value {
    font-size: 16px;
    font-weight: 600;
    color: #495057;
}

.info-content .sub-text {
    font-size: 12px;
    color: #6c757d;
}

.section-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.section-title i {
    color: #ff6b35;
    margin-right: 10px;
}

.description-text {
    color: #495057;
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 30px;
}

.schedule-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
}

.schedule-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.schedule-item h6 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
}

.schedule-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.schedule-badge {
    background: #f7931e;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.time-badge {
    background: black;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.requirements-list {
    list-style: none;
    padding: 0;
}

.requirements-list li {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.requirements-list li:last-child {
    border-bottom: none;
}

.requirements-list li i {
    color: #28a745;
    margin-right: 12px;
    font-size: 16px;
}

.student-profile {
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(247, 147, 30, 0.1));
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
}

.student-info {
    display: flex;
    align-items: center;
}

.student-avatar {
    width: 70px;
    height: 70px;
    background: #f7931e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    flex-shrink: 0;
}

.student-details h5 {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.student-details .posted-time {
    color: #6c757d;
    font-size: 14px;
}

/* Sidebar */
.sidebar-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 30px;
}

.sidebar-card-header {
    padding: 20px 25px;
    color: white;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.sidebar-card-header i {
    margin-right: 10px;
}

.success-header {
    background: black;
}

.info-header {
    background: linear-gradient(135deg, #17a2b8, #138496);
}

.warning-header {
    background: #f7931e;
}

.sidebar-card-body {
    padding: 25px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.btn-submit {
    background: black;
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.btn-login {
    background: black;
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    margin-bottom: 15px;
}

.btn-login:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-register {
    background: transparent;
    border: 2px solid #667eea;
    color: #667eea;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.btn-register:hover {
    background: #667eea;
    color: white;
}

.alert {
    border-radius: 15px;
    border: none;
    padding: 20px;
}

.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(253, 126, 20, 0.1));
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-info {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(19, 132, 150, 0.1));
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.btn-kyc {
    background: #f7931e;
    border: none;
    color: #212529;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-kyc:hover {
    color: #212529;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 12px;
}

.status-approved {
    background: black;
    color: white;
}

.status-rejected {
    background: black;
    color: white;
}

.status-pending {
    background: #f7931e;
    color: #212529;
}

.applications-list {
    max-height: 300px;
    overflow-y: auto;
}

.application-item {
    display: flex;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.application-item:last-child {
    border-bottom: none;
}

.applicant-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #6c757d, #495057);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    color: white;
}

.applicant-info h6 {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
    font-size: 14px;
}

.applicant-info small {
    color: #6c757d;
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 40px 0;
    }

    .vacancy-title {
        font-size: 2rem;
    }

    .vacancy-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .content-section {
        margin-top: -40px;
        padding: 0 20px;
    }

    .quick-info {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px;
    }

    .schedule-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .sidebar-card-body {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .content-section {
        padding: 0 15px;
    }

    .vacancy-title {
        font-size: 1.8rem;
    }

    .card-header,
    .sidebar-card-header {
        padding: 20px;
    }

    .card-body,
    .sidebar-card-body {
        padding: 15px;
    }

    .info-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .info-icon {
        margin-right: 0;
    }
}
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <!-- Back Button -->
            <a href="{{ route('vacancies.index') }}" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back to Vacancies
            </a>

            <!-- Vacancy Header -->
            <div class="vacancy-header">
                <div>
                    <h1 class="vacancy-title">{{ $vacancy->title }}</h1>
                </div>
                <span class="urgency-badge urgency-{{ $vacancy->urgency }}">
                    {{ ucfirst($vacancy->urgency) }} Priority
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <div class="row">
            <!-- Main Vacancy Details -->
            <div class="col-lg-8">
                <div class="main-card">
                    <div class="card-header">
                        <h3 class="mb-0">Vacancy Details</h3>
                    </div>
                    
                    <div class="card-body">
                        <!-- Quick Info Grid -->
                        <div class="quick-info">
                            <div class="info-item">
                                <div class="info-icon icon-subject">
                                    <i class="fas fa-book fa-lg"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Subject & Grade</h6>
                                    <div class="value">{{ $vacancy->subject }}</div>
                                    <div class="sub-text">Grade {{ $vacancy->grade_level }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon icon-budget">
                                    <i class="fas fa-rupee-sign fa-lg"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Budget Range</h6>
                                    <div class="value">Rs. {{ number_format($vacancy->budget_min) }} - Rs. {{ number_format($vacancy->budget_max) }}</div>
                                    <div class="sub-text">Per session</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon icon-duration">
                                    <i class="fas fa-clock fa-lg"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Session Duration</h6>
                                    <div class="value">{{ $vacancy->duration_hours }} hours</div>
                                    <div class="sub-text">Per session</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon icon-location">
                                    <i class="fas fa-map-marker-alt fa-lg"></i>
                                </div>
                                <div class="info-content">
                                    <h6>Teaching Mode</h6>
                                    <div class="value">{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</div>
                                    @if($vacancy->address)
                                        <div class="sub-text">{{ Str::limit($vacancy->address, 40) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h4 class="section-title">
                                <i class="fas fa-info-circle"></i>Description
                            </h4>
                            <p class="description-text">{{ $vacancy->description }}</p>
                        </div>

                        <!-- Schedule -->
                        @if($vacancy->schedule_days && $vacancy->schedule_times)
                            <div class="schedule-section">
                                <h4 class="section-title">
                                    <i class="fas fa-calendar"></i>Schedule
                                </h4>
                                <div class="schedule-grid">
                                    <div class="schedule-item">
                                        <h6>Preferred Days:</h6>
                                        <div class="schedule-badges">
                                            @foreach($vacancy->schedule_days as $day)
                                                <span class="schedule-badge">{{ $day }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="schedule-item">
                                        <h6>Preferred Times:</h6>
                                        <div class="schedule-badges">
                                            @foreach($vacancy->schedule_times as $time)
                                                <span class="time-badge">{{ $time }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if($vacancy->requirements && count($vacancy->requirements) > 0)
                            <div class="mb-4">
                                <h4 class="section-title">
                                    <i class="fas fa-check-circle"></i>Requirements
                                </h4>
                                <ul class="requirements-list">
                                    @foreach($vacancy->requirements as $requirement)
                                        <li>
                                            <i class="fas fa-check-circle"></i>{{ $requirement }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Student Information -->
                        <div class="student-profile">
                            <h4 class="section-title">
                                <i class="fas fa-user"></i>Student Information
                            </h4>
                            <div class="student-info">
                                <div class="student-avatar">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                                <div class="student-details">
                                    <h5>{{ $vacancy->student->name }}</h5>
                                    <div class="posted-time">Posted {{ $vacancy->approved_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Application Form or Login -->
                @auth('tutor')
                    @if(!$hasApplied)
                        <div class="sidebar-card">
                            <div class="sidebar-card-header success-header">
                                <i class="fas fa-paper-plane"></i>Apply for this Vacancy
                            </div>
                            <div class="sidebar-card-body">
                                @if(Auth::guard('tutor')->user()->kyc && Auth::guard('tutor')->user()->kyc->status === 'approved')
                                    <form action="{{ route('vacancies.apply', $vacancy->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="cover_letter" class="form-label">Cover Letter <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5" 
                                                      required placeholder="Introduce yourself and explain why you're the perfect fit for this position...">{{ old('cover_letter') }}</textarea>
                                            <small class="text-muted">Minimum 50 characters</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="proposed_rate" class="form-label">Proposed Rate (Rs.)</label>
                                            <input type="number" class="form-control" id="proposed_rate" name="proposed_rate" 
                                                   min="0" step="0.01" value="{{ old('proposed_rate') }}" 
                                                   placeholder="Your proposed rate per session">
                                            <small class="text-muted">Leave blank to discuss</small>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="experience_years" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                                   min="0" max="50" required value="{{ old('experience_years', 0) }}">
                                        </div>
                                        
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>KYC Required
                                        </h6>
                                        <p class="mb-3">You must complete and have your KYC approved before applying for vacancies.</p>
                                        <a href="{{ route('tutor.kyc.show') }}" class="btn-kyc">
                                            <i class="fas fa-id-card me-1"></i>Complete KYC
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="sidebar-card">
                            <div class="sidebar-card-header info-header">
                                <i class="fas fa-check-circle"></i>Application Submitted
                            </div>
                            <div class="sidebar-card-body">
                                <div class="alert alert-info">
                                    <p class="mb-2">You have already applied for this vacancy.</p>
                                    <p class="mb-0">
                                        <strong>Status:</strong> 
                                        <span class="status-badge status-{{ $userApplication->status === 'approved' ? 'approved' : ($userApplication->status === 'rejected' ? 'rejected' : 'pending') }}">
                                            {{ ucfirst($userApplication->status) }}
                                        </span>
                                    </p>
                                    @if($userApplication->admin_notes)
                                        <hr>
                                        <p class="mb-0"><strong>Notes:</strong> {{ $userApplication->admin_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="sidebar-card">
                        <div class="sidebar-card-header warning-header">
                            <i class="fas fa-sign-in-alt"></i>Login Required
                        </div>
                        <div class="sidebar-card-body">
                            <p class="mb-3">Please login as a tutor to apply for this vacancy.</p>
                            <a href="{{ route('tutor.login') }}" class="btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login as Tutor
                            </a>
                            <a href="{{ route('tutor.register') }}" class="btn-register">
                                <i class="fas fa-user-plus me-2"></i>Register as Tutor
                            </a>
                        </div>
                    </div>
                @endauth

                <!-- Other Applications -->
                @if($vacancy->applications->count() > 0)
                    <div class="sidebar-card">
                        <div class="sidebar-card-header info-header">
                            <i class="fas fa-users"></i>Other Applications ({{ $vacancy->applications->count() }})
                        </div>
                        <div class="sidebar-card-body">
                            <div class="applications-list">
                                @foreach($vacancy->applications->take(8) as $application)
                                    <div class="application-item">
                                        <div class="applicant-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="applicant-info">
                                            <h6>{{ $application->tutor->name }}</h6>
                                            <small>{{ $application->experience_years }} years experience</small>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($vacancy->applications->count() > 8)
                                    <div class="text-center mt-3">
                                        <small class="text-muted">And {{ $vacancy->applications->count() - 8 }} more applications...</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection