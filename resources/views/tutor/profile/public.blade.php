@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{ $tutor->name }} - Tutor Profile">
    <meta property="og:description" content="{{ $tutor->bio ?? 'Professional tutor offering quality education' }}">
    <meta property="og:image"
        content="{{ $tutor->kyc && $tutor->kyc->profile_photo ? Storage::url($tutor->kyc->profile_photo) : asset('images/default-avatar.png') }}">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #fff5f0 0%, #ffe8dd 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .page-header {
            background: #ffffffff;
            color: #090909;
            padding: 40px 0;
            margin-bottom: 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 20px auto 0;
            position: relative;
            z-index: 10;
        }

        /* Card Styling */
        .custom-card {
            background: url('/images/texturebg.avif') no-repeat center center;
            background-size: cover;
            background-color: rgba(255, 255, 255, 0.95);
            background-blend-mode: overlay;
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 25px;
        }

        .custom-card:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
        }

        .custom-card-header {
            background: linear-gradient(2deg, #4b76f2, #7c91df);
            color: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 15px 20px;
            border: none;
        }

        .custom-card-header h5,
        .custom-card-header h6 {
            margin-bottom: 0;
            font-weight: 700;
        }

        .custom-card-body {
            padding: 25px;
            background: white;
        }

        /* Profile Header Styling */
        .profile-header-card {
            background: white;
        }

        .profile-name {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ff6b35;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #000000ff, #232322ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            border: 4px solid #ff6b35;
        }

        .rating-display i {
            font-size: 18px;
        }

        .verified-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .availability-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .availability-badge.available {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .availability-badge.busy {
            background: linear-gradient(135deg, #ffc107, #ffb347);
            color: #2c3e50;
        }

        .availability-badge.unavailable {
            background: #6c757d;
            color: white;
        }

        /* Stats Section */
        .stats-section {
            background: rgba(255, 107, 53, 0.03);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 600;
        }

        .hourly-rate-display {
            color: #ff6b35;
            font-weight: 700;
            font-size: 2rem;
        }

        /* Subject and Skills */
        .subject-badge {
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-right: 10px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .language-badge {
            background: linear-gradient(135deg, #6f42c1, #8e44ad);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-right: 10px;
            margin-bottom: 8px;
            display: inline-block;
        }

        /* Portfolio */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .portfolio-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .portfolio-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .portfolio-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .portfolio-info {
            padding: 15px;
        }

        /* Certifications */
        .certification-item {
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .certification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Education */
        .education-item {
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.1);
            border-radius: 12px;
            padding: 20px;
        }

        .education-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        /* Sidebar Styling */
        .sidebar-card {
            position: sticky;
            top: 20px;
        }

        .contact-card {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(247, 147, 30, 0.1));
        }

        .contact-rate {
            color: #ff6b35;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .btn-contact {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-contact:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-outline-contact {
            background: transparent;
            border: 2px solid #ff6b35;
            color: #ff6b35;
            padding: 15px 25px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .btn-outline-contact:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
        }

        /* Contact Info */
        .contact-info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .contact-info-item:last-child {
            border-bottom: none;
        }

        .contact-info-item i {
            color: #ff6b35;
            width: 20px;
            margin-right: 15px;
        }

        /* Availability Schedule */
        .schedule-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .schedule-item:last-child {
            border-bottom: none;
        }

        .schedule-day {
            font-weight: 600;
            color: #2c3e50;
        }

        .schedule-time {
            color: #6c757d;
        }

        /* Share Buttons */
        .share-btn {
            background: rgba(255, 107, 53, 0.1);
            border: 2px solid rgba(255, 107, 53, 0.2);
            color: #ff6b35;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }

        .share-btn:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
        }

        /* Jobs Section */
        .tutor-job-card {
            background: url('/images/texturebg.avif') no-repeat center center;
            background-size: cover;
            background-color: rgba(255, 255, 255, 0.9);
            background-blend-mode: overlay;
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .tutor-job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .job-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .job-rate {
            color: #ff6b35;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .btn-view-job {
            background: linear-gradient(135deg, #000000, #21201f);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view-job:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 25px;
        }

        .breadcrumb-item a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumb-item.active {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Video */
        .intro-video {
            width: 100%;
            max-height: 300px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                margin: -20px auto 0;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .hourly-rate-display {
                font-size: 1.5rem;
            }

            .custom-card-body {
                padding: 20px;
            }

            .sidebar-card {
                position: static;
                margin-top: 20px;
            }

            .profile-avatar,
            .avatar-placeholder {
                width: 80px;
                height: 80px;
            }

            .portfolio-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .custom-card-body {
                padding: 15px;
            }

            .profile-name {
                font-size: 1.3rem;
            }
        }
    </style>

    <div class="">
        <!-- Page Header -->
        <div class="page-header">
            <div class="main-container">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('search.tutors') }}">Tutors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $tutor->name }}</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center">
                    <h1 class="profile-name mb-0 d-flex align-items-center">
                        {{ Str::title($tutor->name) }}
                        @if($tutor->status === 'active')
                            <i class="fas fa-check-circle text-success ms-2" title="Verified Tutor"
                                style="font-size:1.1rem;"></i>
                        @endif
                    </h1>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Profile Header -->
                    <div class="custom-card profile-header-card">
                        <div class="custom-card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-4">
                                    @if($tutor->kyc && $tutor->kyc->profile_photo)
                                        <img src="{{ Storage::url($tutor->kyc->profile_photo) }}" alt="Profile Photo"
                                            class="profile-avatar">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-0">
                                        <p class="mb-0">{{ $tutor->bio }}</p>
                                        @if($tutor->profile)
                                            <div class="mb-3">
                                                @if($tutor->profile->availability_status === 'available')
                                                    <span class="availability-badge /hour">
                                                        <i class="fas fa-circle me-1"></i>Available for Tutoring
                                                    </span>
                                                @elseif($tutor->profile->availability_status === 'busy')
                                                    <span class="availability-badge busy">
                                                        <i class="fas fa-circle me-1"></i>Currently Busy
                                                    </span>
                                                @else
                                                    <span class="availability-badge unavailable">
                                                        <i class="fas fa-circle me-1"></i>Unavailable
                                                        @if($tutor->profile->unavailable_until)
                                                            until {{ $tutor->profile->unavailable_until->format('M d, Y') }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
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
                                    </div>

                                    <!-- Availability Status -->


                                    <div class="hourly-rate-display">
                                        Rs. {{ $tutor->hourly_rate * 30 ?? 'Contact for rate' }}/month
                                    </div>
                                </div>
                            </div>

                            <!-- Tutor Stats -->
                            <div class="stats-section">
                                <div class="row text-center">
                                    <div class="col-md-3 col-6">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                {{ $tutor->profile ? $tutor->profile->total_students : 0 }}</div>
                                            <div class="stat-label">Students Taught</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                {{ $tutor->profile ? $tutor->profile->total_hours : 0 }}</div>
                                            <div class="stat-label">Hours Completed</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                {{ $tutor->profile ? $tutor->profile->profile_views : 0 }}</div>
                                            <div class="stat-label">Profile Views</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $tutor->created_at->diffForHumans() }}</div>
                                            <div class="stat-label">Member Since</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Section -->


                    <!-- Subjects & Skills -->
                    @if($tutor->kyc && $tutor->kyc->subjects_expertise)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Subjects I Teach</h5>
                            </div>
                            <div class="custom-card-body">
                                @foreach($tutor->kyc->subjects_expertise as $subject)
                                    <span class="subject-badge">{{ $subject }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Education -->
                    @if($tutor->kyc && $tutor->kyc->qualification)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Education</h5>
                            </div>
                            <div class="custom-card-body">
                                <div class="education-item">
                                    <div class="education-title">{{ $tutor->kyc->qualification }}</div>
                                    <div class="text-muted">Highest Qualification</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Languages -->
                    @if($tutor->profile && $tutor->profile->languages)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Languages</h5>
                            </div>
                            <div class="custom-card-body">
                                @foreach($tutor->profile->languages as $language)
                                    <span class="language-badge">
                                        {{ $language['name'] }} <small>({{ $language['level'] }})</small>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Introduction Video -->
                    @if($tutor->profile && $tutor->profile->introduction_video)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Introduction Video</h5>
                            </div>
                            <div class="custom-card-body">
                                <video class="intro-video" controls>
                                    <source src="{{ Storage::url($tutor->profile->introduction_video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    @endif

                    <!-- Portfolio -->
                    @if($tutor->profile && $tutor->profile->portfolio_items)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Portfolio</h5>
                            </div>
                            <div class="custom-card-body">
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
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Additional Certifications</h5>
                            </div>
                            <div class="custom-card-body">
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
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($cert['date'])->format('M Y') }}</small>
                                                @endif
                                            </div>
                                            @if(isset($cert['file']))
                                                <a href="{{ Storage::url($cert['file']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Contact Card -->
                    <div class="custom-card contact-card sidebar-card">
                        <div class="custom-card-header">
                            <h5>
                                <i class="fas fa-envelope me-2"></i>Contact {{ $tutor->name }}
                            </h5>
                        </div>
                        <div class="custom-card-body text-center">
                            <div class="contact-rate">Rs. {{ $tutor->hourly_rate ?? 'Contact for rate' }}/hour</div>

                            @guest
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Please <a href="/login">login</a> to contact this tutor.
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="/login" class="btn-contact">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login to Contact
                                    </a>
                                    <a href="/login" class="btn-outline-contact">
                                        <i class="fas fa-calendar me-2"></i>Login for Trial Lesson
                                    </a>
                                </div>
                            @else
                                <div class="d-grid gap-2">
                                    <button class="btn-contact" onclick="contactTutor()">
                                        <i class="fas fa-comments me-2"></i>Contact Now
                                    </button>
                                    <button class="btn-outline-contact" onclick="bookTrial()">
                                        <i class="fas fa-calendar me-2"></i>Book Trial Lesson
                                    </button>
                                </div>
                            @endguest

                            <div class="contact-info-section mt-4">
                                @if($tutor->kyc && $tutor->kyc->exact_location)
                                    <div class="contact-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $tutor->kyc->exact_location }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    @if($tutor->profile && $tutor->profile->availability_schedule)
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>
                                    <i class="fas fa-clock me-2"></i>Availability
                                </h5>
                            </div>
                            <div class="custom-card-body">
                                @foreach($tutor->profile->getFormattedSchedule() as $day)
                                    <div class="schedule-item">
                                        <span class="schedule-day">{{ $day['day'] }}</span>
                                        @if($day['off'])
                                            <span class="schedule-time text-muted">Unavailable</span>
                                        @else
                                            <span class="schedule-time">{{ $day['start'] ?? 'N/A' }} - {{ $day['end'] ?? 'N/A' }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h6>Quick Actions</h6>
                        </div>
                        <div class="custom-card-body">
                            @if(isset($tutorJobs) && $tutorJobs->count() > 0)
                                <a href="{{ route('jobs.index', ['tutor' => $tutor->id]) }}" class="share-btn mb-2">
                                    <i class="fas fa-briefcase me-2"></i>View All Jobs ({{ $tutorJobs->count() }})
                                </a>
                            @endif
                            <button class="share-btn" onclick="shareProfile()">
                                <i class="fas fa-share-alt me-2"></i>Share Profile
                            </button>
                            <button class="share-btn" onclick="reportProfile()">
                                <i class="fas fa-flag me-2"></i>Report Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutor's Jobs Section -->
            @if(isset($tutorJobs) && $tutorJobs->count() > 0)
                <div class="mt-5">
                    <h4 class="mb-4" style="color: #2c3e50; font-weight: 700;">Available Jobs by {{ $tutor->name }}</h4>
                    <div class="row">
                        @foreach($tutorJobs as $job)
                            <div class="col-md-4 mb-4">
                                <div class="tutor-job-card">
                                    <div class="custom-card-body">
                                        <h6 class="job-title">{{ Str::limit($job->title, 50) }}</h6>
                                        <p class="text-muted small mb-3">{{ Str::limit($job->description, 100) }}</p>

                                        <!-- Job Status -->
                                        <div class="mb-2">
                                            @if($job->is_featured)
                                                <span class="badge"
                                                    style="background: linear-gradient(135deg, #ffd700, #ffb347); color: #2c3e50; font-weight: 600; padding: 4px 10px; border-radius: 15px; font-size: 11px; margin-right: 5px;">
                                                    <i class="fas fa-star"></i> Featured
                                                </span>
                                            @endif
                                            @if($job->status === 'active')
                                                <span class="badge bg-success"
                                                    style="padding: 4px 10px; border-radius: 15px; font-size: 11px;">Active</span>
                                            @else
                                                <span class="badge bg-secondary"
                                                    style="padding: 4px 10px; border-radius: 15px; font-size: 11px;">{{ ucfirst($job->status) }}</span>
                                            @endif
                                        </div>

                                        <!-- Subjects -->
                                        <div class="mb-3">
                                            @foreach(array_slice($job->subjects, 0, 3) as $subject)
                                                <span class="subject-badge"
                                                    style="font-size: 11px; padding: 4px 8px;">{{ $subject }}</span>
                                            @endforeach
                                            @if(count($job->subjects) > 3)
                                                <span class="text-muted small">+{{ count($job->subjects) - 3 }} more</span>
                                            @endif
                                        </div>

                                        <!-- Job Details -->
                                        <div class="mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $job->place }}, {{ $job->district }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-users me-1"></i>{{ ucfirst($job->session_type) }} • Max
                                                {{ $job->max_students }} students
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-graduation-cap me-1"></i>{{ $job->student_level }}
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="job-rate">Rs. {{ number_format((float) $job->hourly_rate, 2) }}/hr</span>
                                            <div class="text-end">
                                                <small class="text-muted d-block">{{ $job->views }} views</small>
                                                <small class="text-muted">{{ $job->inquiries }} inquiries</small>
                                            </div>
                                        </div>

                                        <a href="{{ $job->url }}" class="btn-view-job w-100 text-center">
                                            <i class="fas fa-eye me-2"></i>View Job Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Show more jobs if there are more than 6 -->
                    @if($tutorJobs->count() > 6)
                        <div class="text-center mt-4">
                            <a href="{{ route('jobs.index', ['tutor' => $tutor->id]) }}" class="btn-contact"
                                style="display: inline-block; width: auto; padding: 12px 30px;">
                                <i class="fas fa-briefcase me-2"></i>View All Jobs ({{ $tutorJobs->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-5">
                    <div class="custom-card">
                        <div class="custom-card-body text-center py-5">
                            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Active Jobs</h5>
                            <p class="text-muted">{{ $tutor->name }} doesn't have any active job postings at the moment.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function contactTutor() {
            // Check if there are any active jobs from this tutor
            @if(isset($tutorJobs) && $tutorJobs->count() > 0)
                // Redirect to the first job's contact page
                window.location.href = '/jobs/{{ $tutorJobs->first()->id }}/contact';
            @else
                alert('This tutor currently has no active jobs to contact about. Please check back later.');
            @endif
        }

        function bookTrial() {
            // Redirect to contact for trial booking
            @if(isset($tutorJobs) && $tutorJobs->count() > 0)
                window.location.href = '/jobs/{{ $tutorJobs->first()->id }}/contact?type=trial';
            @else
                alert('This tutor currently has no active jobs for trial lessons. Please check back later.');
            @endif
        }

        function shareProfile() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $tutor->name }} - Tutor Profile',
                    text: 'Check out this amazing tutor profile',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(function () {
                    // Show success message
                    const button = event.target.closest('button');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check me-2"></i>Link Copied!';
                    button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                    button.style.color = 'white';

                    setTimeout(function () {
                        button.innerHTML = originalText;
                        button.style.background = '';
                        button.style.color = '';
                    }, 2000);
                }).catch(function (err) {
                    console.error('Could not copy text: ', err);
                    alert('Could not copy link. Please copy manually.');
                });
            }
        }

        function reportProfile() {
            // Implement report functionality
            alert('Report functionality will be implemented');
        }
    </script>

@endsection