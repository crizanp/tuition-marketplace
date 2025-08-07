@extends('layouts.app')

@section('content')
<div class="preview-container py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="preview-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2><i class="fas fa-eye me-2"></i>Profile Preview</h2>
                        <button class="btn btn-secondary" onclick="window.close()">
                            <i class="fas fa-times me-1"></i>Close Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content (Same as public profile) -->
        <div class="row">
            <!-- Profile Header -->
            <div class="col-12">
                <div class="profile-header-card mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar me-3">
                            @php
                                $kyc = \App\Models\TutorKyc::where('tutor_id', $tutor->id)->where('status', 'approved')->first();
                            @endphp
                            @if($kyc && $kyc->profile_photo)
                                <img src="{{ Storage::url($kyc->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="mb-1">{{ $tutor->name }}</h3>
                            <p class="text-muted mb-1">{{ $tutor->email }}</p>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-{{ $tutor->status === 'active' ? 'success' : 'warning' }} me-2">
                                    {{ ucfirst($tutor->status) }}
                                </span>
                                @if($tutor->status === 'active' && $kyc)
                                    <i class="fas fa-check-circle text-primary" title="Verified"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- About Section -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-info-circle me-2"></i>About</h5>
                    <div class="section-content">
                        <div class="info-display">
                            {{ $tutor->bio ?? 'No description provided' }}
                        </div>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-skills me-2"></i>Skills & Subjects</h5>
                    <div class="section-content">
                        <div class="skills-display">
                            @if($kyc && $kyc->subjects_expertise)
                                @foreach($kyc->subjects_expertise as $subject)
                                    <span class="skill-tag">{{ $subject }}</span>
                                @endforeach
                            @else
                                <div class="text-muted">No skills added yet</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Education Section -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-graduation-cap me-2"></i>Education</h5>
                    <div class="section-content">
                        <div class="education-item">
                            @if($kyc && $kyc->qualification)
                                <div class="education-title">{{ $kyc->qualification }}</div>
                                <div class="text-muted">Highest Qualification</div>
                            @else
                                <div class="text-muted">No education information added yet</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Languages Section -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-language me-2"></i>Languages</h5>
                    <div class="section-content">
                        <div class="languages-display">
                            <span class="language-tag">English <small class="text-muted">(Fluent)</small></span>
                            <span class="language-tag">Nepali <small class="text-muted">(Native)</small></span>
                        </div>
                    </div>
                </div>

                <!-- Introduction Video Section -->
                @if($tutor->intro_video)
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-video me-2"></i>Introduction Video</h5>
                    <div class="section-content">
                        <video controls class="w-100" style="max-height: 400px;">
                            <source src="{{ Storage::url($tutor->intro_video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                @endif

                <!-- Certifications Section -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-certificate me-2"></i>Certifications</h5>
                    <div class="section-content">
                        <div class="certifications-list">
                            @if($kyc && $kyc->certificate_file)
                                <div class="certification-item">
                                    <i class="fas fa-certificate me-2"></i>
                                    <span>Teaching Certificate</span>
                                    <a href="{{ Storage::url($kyc->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-auto">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @else
                                <div class="text-muted">No certifications added yet</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Information -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-address-card me-2"></i>Contact Information</h5>
                    <div class="section-content">
                        <div class="contact-item mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <span>{{ $tutor->email }}</span>
                        </div>
                        @if($tutor->phone)
                        <div class="contact-item mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            <span>{{ $tutor->phone }}</span>
                        </div>
                        @endif
                        @if($tutor->hourly_rate)
                        <div class="contact-item mb-2">
                            <i class="fas fa-dollar-sign me-2 text-primary"></i>
                            <span>Rs. {{ $tutor->hourly_rate }}/hour</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Availability -->
                <div class="profile-section-card mb-4">
                    <h5><i class="fas fa-clock me-2"></i>Availability</h5>
                    <div class="section-content">
                        <div class="availability-status mb-3">
                            <div class="d-flex align-items-center">
                                <div class="status-indicator available me-2"></div>
                                <span class="status-text">Available Now</span>
                            </div>
                        </div>
                        <div class="availability-schedule">
                            <div class="schedule-item">
                                <span class="day">Monday - Friday</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="schedule-item">
                                <span class="day">Saturday</span>
                                <span class="time">10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="schedule-item">
                                <span class="day">Sunday</span>
                                <span class="time">Unavailable</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="profile-section-card">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg">
                            <i class="fas fa-envelope me-2"></i>Contact Tutor
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-calendar me-2"></i>Schedule Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.preview-container {
    background-color: #ffffffff;
    min-height: 100vh;
}

.preview-header {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.profile-header-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.profile-avatar {
    position: relative;
}

.avatar-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e67e22;
}

.avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #6c757d;
}

.profile-section-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.profile-section-card h5 {
    margin-bottom: 20px;
    color: #2c3e50;
    font-weight: 600;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.info-display {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #2c3e50;
    font-weight: 500;
}

.skill-tag {
    display: inline-block;
    background: #e67e22;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    margin: 4px;
}

.language-tag {
    display: inline-block;
    background: #3498db;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    margin: 4px;
}

.certification-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.contact-item {
    display: flex;
    align-items: center;
    padding: 5px 0;
}

.availability-status {
    text-align: center;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.available {
    background: #27ae60;
}

.schedule-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.schedule-item:last-child {
    border-bottom: none;
}

.education-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.education-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}
</style>
@endsection
