@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta property="og:title" content="{{ $tutor->name }} - Tutor Profile">
<meta property="og:description" content="{{ $tutor->bio ?? 'Professional tutor offering quality education' }}">
<meta property="og:image" content="{{ $tutor->kyc && $tutor->kyc->profile_photo ? Storage::url($tutor->kyc->profile_photo) : asset('images/default-avatar.png') }}">
@endsection

@section('content')
<div class="public-profile-container py-5">
    <div class="container">
        <div class="row">
            <!-- Main Profile Content -->
            <div class="col-lg-8">
                <!-- Profile Header -->
                <div class="profile-header-card mb-4">
                    <div class="d-flex align-items-start">
                        <div class="profile-avatar me-4">
                            @if($tutor->kyc && $tutor->kyc->profile_photo)
                                <img src="{{ Storage::url($tutor->kyc->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="mb-2">{{ $tutor->name }}</h2>
                            <div class="d-flex align-items-center mb-2">
                                <div class="rating-display me-3">
                                    @php
                                        $rating = $tutor->profile ? $tutor->profile->rating : 0;
                                        $totalRatings = $tutor->profile ? $tutor->profile->total_ratings : 0;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $totalRatings }} reviews)</span>
                                </div>
                                @if($tutor->status === 'active')
                                    <span class="badge bg-success">Verified Tutor</span>
                                @endif
                            </div>
                            <div class="tutor-stats mb-3">
                                <span class="stat-item me-4">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $tutor->profile ? $tutor->profile->total_students : 0 }} Students Taught
                                </span>
                                <span class="stat-item me-4">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $tutor->profile ? $tutor->profile->total_hours : 0 }} Hours Completed
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ $tutor->profile ? $tutor->profile->profile_views : 0 }} Profile Views
                                </span>
                            </div>
                            <div class="hourly-rate">
                                <span class="rate-label">Hourly Rate:</span>
                                <span class="rate-amount">Rs. {{ $tutor->hourly_rate ?? 'Contact for rate' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                @if($tutor->bio)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">About {{ $tutor->name }}</h5>
                    <div class="section-content">
                        <p>{{ $tutor->bio }}</p>
                    </div>
                </div>
                @endif

                <!-- Subjects & Skills -->
                @if($tutor->kyc && $tutor->kyc->subjects_expertise)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Subjects I Teach</h5>
                    <div class="section-content">
                        <div class="subjects-display">
                            @foreach($tutor->kyc->subjects_expertise as $subject)
                                <span class="subject-tag">{{ $subject }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Education -->
                @if($tutor->kyc && $tutor->kyc->qualification)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Education</h5>
                    <div class="section-content">
                        <div class="education-item">
                            <div class="education-title">{{ $tutor->kyc->qualification }}</div>
                            <div class="text-muted">Highest Qualification</div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Languages -->
                @if($tutor->profile && $tutor->profile->languages)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Languages</h5>
                    <div class="section-content">
                        <div class="languages-display">
                            @foreach($tutor->profile->languages as $language)
                                <span class="language-tag">
                                    {{ $language['name'] }} <small class="text-muted">({{ $language['level'] }})</small>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Introduction Video -->
                @if($tutor->profile && $tutor->profile->introduction_video)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Introduction Video</h5>
                    <div class="section-content">
                        <video class="w-100" controls style="max-height: 300px; border-radius: 8px;">
                            <source src="{{ Storage::url($tutor->profile->introduction_video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                @endif

                <!-- Portfolio -->
                @if($tutor->profile && $tutor->profile->portfolio_items)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Portfolio</h5>
                    <div class="section-content">
                        <div class="portfolio-grid">
                            @foreach($tutor->profile->portfolio_items as $item)
                                <div class="portfolio-item">
                                    @if(isset($item['image']))
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['title'] }}">
                                    @endif
                                    <div class="portfolio-info">
                                        <h6>{{ $item['title'] }}</h6>
                                        @if(isset($item['description']))
                                            <p class="text-muted small">{{ $item['description'] }}</p>
                                        @endif
                                        @if(isset($item['url']))
                                            <a href="{{ $item['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Certifications -->
                @if($tutor->profile && $tutor->profile->additional_certifications)
                <div class="profile-section-card mb-4">
                    <h5 class="section-title">Additional Certifications</h5>
                    <div class="section-content">
                        <div class="certifications-list">
                            @foreach($tutor->profile->additional_certifications as $cert)
                                <div class="certification-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-certificate me-3 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $cert['name'] }}</h6>
                                            @if(isset($cert['issuer']))
                                                <p class="text-muted mb-0">{{ $cert['issuer'] }}</p>
                                            @endif
                                            @if(isset($cert['date']))
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($cert['date'])->format('M Y') }}</small>
                                            @endif
                                        </div>
                                        @if(isset($cert['file']))
                                            <a href="{{ Storage::url($cert['file']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Card -->
                <div class="contact-card mb-4">
                    <h5>Contact {{ $tutor->name }}</h5>
                    <div class="contact-info mb-3">
                        @if($tutor->phone)
                            <div class="contact-item">
                                <i class="fas fa-phone me-2"></i>
                                <span>{{ $tutor->phone }}</span>
                            </div>
                        @endif
                        <div class="contact-item">
                            <i class="fas fa-envelope me-2"></i>
                            <span>{{ $tutor->email }}</span>
                        </div>
                        @if($tutor->kyc && $tutor->kyc->exact_location)
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>{{ $tutor->kyc->exact_location }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" onclick="contactTutor()">
                            <i class="fas fa-comments me-2"></i>Contact Now
                        </button>
                        <button class="btn btn-outline-primary" onclick="bookTrial()">
                            <i class="fas fa-calendar me-2"></i>Book Trial Lesson
                        </button>
                    </div>
                </div>

                <!-- Availability -->
                @if($tutor->profile && $tutor->profile->availability_schedule)
                <div class="availability-card mb-4">
                    <h5>Availability</h5>
                    <div class="availability-status mb-3">
                        @if($tutor->profile->availability_status === 'available')
                            <div class="d-flex align-items-center">
                                <div class="status-indicator available me-2"></div>
                                <span class="status-text">Available Now</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center">
                                <div class="status-indicator unavailable me-2"></div>
                                <span class="status-text">Currently Unavailable</span>
                                @if($tutor->profile->unavailable_until)
                                    <small class="text-muted d-block">
                                        Available from {{ $tutor->profile->unavailable_until->format('M d, Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="schedule-display">
                        @foreach($tutor->profile->getFormattedSchedule() as $day)
                            <div class="schedule-item">
                                <span class="day">{{ $day['day'] }}</span>
                                @if($day['off'])
                                    <span class="time text-muted">Unavailable</span>
                                @else
                                    <span class="time">{{ $day['start'] ?? 'N/A' }} - {{ $day['end'] ?? 'N/A' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="quick-actions-card">
                    <h5>Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-secondary" onclick="shareProfile()">
                            <i class="fas fa-share-alt me-2"></i>Share Profile
                        </button>
                        <button class="btn btn-outline-secondary" onclick="reportProfile()">
                            <i class="fas fa-flag me-2"></i>Report Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.public-profile-container {
    background-color: #ffffffff;
    min-height: 100vh;
}

.profile-header-card, .profile-section-card, .contact-card, .availability-card, .quick-actions-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
}

.profile-avatar {
    position: relative;
}

.avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #e67e22;
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #6c757d;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e67e22;
}

.rating-display i {
    font-size: 18px;
}

.stat-item {
    color: #6c757d;
    font-size: 14px;
}

.rate-label {
    color: #6c757d;
    font-weight: 500;
}

.rate-amount {
    color: #e67e22;
    font-size: 20px;
    font-weight: 700;
}

.subject-tag {
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

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.portfolio-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.portfolio-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.portfolio-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.portfolio-info {
    padding: 15px;
}

.certification-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.contact-item {
    margin-bottom: 10px;
    color: #6c757d;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.available {
    background: #27ae60;
}

.status-indicator.unavailable {
    background: #e74c3c;
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

/* Responsive Design */
@media (max-width: 768px) {
    .profile-header-card, .profile-section-card, .contact-card, .availability-card, .quick-actions-card {
        padding: 20px;
    }
    
    .avatar-img, .avatar-placeholder {
        width: 80px;
        height: 80px;
    }
    
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function contactTutor() {
    // Implement contact functionality
    alert('Contact functionality will be implemented');
}

function bookTrial() {
    // Implement booking functionality
    alert('Trial booking functionality will be implemented');
}

function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $tutor->name }} - Tutor Profile',
            text: 'Check out this amazing tutor profile',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Profile URL copied to clipboard!');
    }
}

function reportProfile() {
    // Implement report functionality
    alert('Report functionality will be implemented');
}
</script>
@endsection
