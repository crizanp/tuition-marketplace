<!-- this is show page -->

@extends('layouts.app')

@section('navbar')
    @include('partials.unified-navbar')
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
            padding: 0 0px;
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
            padding: 10px 15px;
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

        /* Job Header Styling */
        .job-header-card {
            background: white;
        }

        .job-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .badge-featured {
            background: linear-gradient(135deg, #ffd700, #ffb347);
            color: #2c3e50;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 20px;
        }

        .badge-status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
        }

        .badge-status-secondary {
            background: #6c757d;
            padding: 8px 15px;
            border-radius: 20px;
        }

        .hourly-rate-display {
            color: #ff6b35;
            font-weight: 700;
            font-size: 2rem;
        }

        /* Tutor Info Styling */
        .tutor-info-section {
            background: rgba(255, 107, 53, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid rgba(255, 107, 53, 0.1);
        }

        .tutor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 107, 53, 0.3);
        }

        .tutor-placeholder {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #000000ff, #232322ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .tutor-name {
            color: #000000ff;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .verified-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Job Stats */
        .stats-section {
            background: rgba(255, 107, 53, 0.03);
            border-radius: 12px;
            padding: 20px;
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

        /* Subject Badges */
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

        .teaching-mode-badge {
            background: linear-gradient(135deg, #6f42c1, #8e44ad);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        /* Location Section */
        .location-card {
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.1);
        }

        .location-icon {
            color: #ff6b35;
            font-size: 1.2rem;
            margin-right: 10px;
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
        }

        .btn-contact:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Quick Info */
        .quick-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .quick-info-item:last-child {
            border-bottom: none;
        }

        .quick-info-label {
            color: #6c757d;
            font-weight: 600;
        }

        .quick-info-value {
            color: #2c3e50;
            font-weight: 700;
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
        }

        .share-btn:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
        }

        /* Rating Section */
        .rating-section {
            background: rgba(255, 107, 53, 0.02);
            border-radius: 12px;
            padding: 20px;
        }

        .rating-stars i {
            margin-right: 2px;
        }

        .rating-input {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 2px solid #f1f1f1;
        }

        .rating-star {
            background: none;
            border: none;
            color: #ddd;
            margin-right: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .rating-star:hover {
            color: #ffc107;
            transform: scale(1.1);
        }

        .rating-star.active {
            color: #ffc107;
        }

        .rating-star.hover {
            color: #ffc107;
        }

        #ratingDisplay {
            font-weight: 600;
            color: #6c757d;
        }

        #submitRating:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Related Jobs */
        .related-job-card {
            background: url('/images/texturebg.avif') no-repeat center center;
            background-size: cover;
            background-color: rgba(255, 255, 255, 0.9);
            background-blend-mode: overlay;
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .related-job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .related-job-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .related-job-rate {
            color: #ff6b35;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-view-details {
            background: linear-gradient(135deg, #000000, #21201f);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-view-details:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        /* Alert Styling */
        .alert-info {
            background: rgba(255, 107, 53, 0.1);
            border: 2px solid rgba(255, 107, 53, 0.2);
            color: #ff6b35;
            border-radius: 12px;
        }

        .alert-info a {
            color: #ff6b35;
            font-weight: 700;
            text-decoration: underline;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 25px;
            padding: 12px 20px;
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

        /* Gallery */
        .gallery-image {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                margin: -20px auto 0;
                padding: 0 15px;
            }

            .job-title {
                font-size: 1.5rem;
            }

            .hourly-rate-display {
                font-size: 1.5rem;
            }

            .custom-card-body {
                padding: 20px;
                background: white;
            }

            .sidebar-card {
                position: static;
                margin-top: 20px;
            }
        }

        @media (max-width: 480px) {
            .custom-card-body {
                padding: 15px;
                background: white;

            }

            .job-title {
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
                        <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($job->title, 50) }}</li>
                    </ol>
                </nav>
                <div>
                    <h1 class="job-title">{{ $job->title }}</h1>
                    @if($job->is_featured)
                        <span class="badge-featured me-2">
                            <i class="fas fa-star"></i> Featured
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Job Header -->
                    <div class="custom-card job-header-card">
                        <div class="custom-card-body">


                            <!-- Tutor Info -->
                            <div class="tutor-info-section">
                                <div class="d-flex align-items-center">
                                    @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                        <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" class="tutor-avatar me-3"
                                            alt="Tutor">
                                    @else
                                        <div class="tutor-placeholder me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="tutor-name text-decoration-none">{{ $job->tutor->name }}</a>
                                                <div class="d-flex align-items-center mt-1">
                                                    @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                                        <span class="verified-badge me-2">
                                                            <i class="fas fa-check-circle"></i> Verified Tutor
                                                        </span>
                                                    @endif
                                                    <!-- Availability Status -->
                                                    @if($job->tutor->profile)
                                                        @if($job->tutor->profile->availability_status === 'available')
                                                            <span class="badge bg-success me-2">
                                                                <i class="fas fa-circle"></i> Available
                                                            </span>
                                                        @elseif($job->tutor->profile->availability_status === 'busy')
                                                            <span class="badge bg-warning me-2">
                                                                <i class="fas fa-circle"></i> Busy
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary me-2">
                                                                <i class="fas fa-circle"></i> Unavailable
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <small class="text-muted">
                                                    Member since {{ $job->tutor->created_at->format('M Y') }}
                                                    @if($job->tutor->profile && $job->tutor->profile->profile_views > 0)
                                                        • {{ number_format($job->tutor->profile->profile_views) }} profile views
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="btn btn-outline-primary btn-sm">
                                                    View Profile
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Stats -->
                            <div class="stats-section mt-3">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $job->views }}</div>
                                            <div class="stat-label">Views</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $job->inquiries }}</div>
                                            <div class="stat-label">Inquiries</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ $job->created_at->diffForHumans() }}</div>
                                            <div class="stat-label">Posted</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h5>Job Description</h5>
                        </div>
                        <div class="custom-card-body">
                            <p class="mb-0">{{ $job->description }}</p>
                        </div>
                    </div>

                    <!-- Job Details -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h5>Job Details</h5>
                        </div>
                        <div class="custom-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Subjects:</strong>
                                    <div class="mt-2">
                                        @foreach($job->subjects as $subject)
                                            <span class="subject-badge">{{ $subject }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Teaching Mode:</strong>
                                    <div class="mt-2">
                                        <span class="teaching-mode-badge">{{ $job->getTeachingModeLabel() }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Student Level:</strong>
                                    <div class="mt-1">{{ $job->student_level }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Session Type:</strong>
                                    <div class="mt-1">{{ ucfirst($job->session_type) }} (Max {{ $job->max_students }}
                                        students)</div>
                                </div>
                                @if($job->preferred_times && count($job->preferred_times) > 0)
                                    <div class="col-md-6 mb-3">
                                        <strong>Preferred Times:</strong>
                                        <div class="mt-2">
                                            @foreach($job->preferred_times as $time)
                                                <span class="subject-badge">{{ ucfirst($time) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <strong>Gender Preference:</strong>
                                    <div class="mt-1">{{ ucfirst($job->gender_preference) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="custom-card location-card">
                        <div class="custom-card-header">
                            <h5>
                                <i class="fas fa-map-marker-alt me-2"></i>Location
                            </h5>
                        </div>
                        <div class="custom-card-body">
                            <address class="mb-0">
                                @if($job->landmark)
                                    {{ $job->landmark }}<br>
                                @endif
                                {{ $job->place }}, {{ $job->district }}<br>
                                {{ $job->state }}, {{ $job->country }}
                                @if($job->postal_code)
                                    <br>{{ $job->postal_code }}
                                @endif
                            </address>
                        </div>
                    </div>

                    @if($job->requirements)
                        <!-- Requirements -->
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Requirements</h5>
                            </div>
                            <div class="custom-card-body">
                                <p class="mb-0">{{ $job->requirements }}</p>
                            </div>
                        </div>
                    @endif

                    @if($job->gallery && count($job->gallery) > 0)
                        <!-- Gallery -->
                        <div class="custom-card">
                            <div class="custom-card-header">
                                <h5>Gallery</h5>
                            </div>
                            <div class="custom-card-body">
                                <div class="row">
                                    @foreach($job->gallery as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ Storage::url($image) }}" class="img-fluid gallery-image" alt="Job Gallery">
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
                    <div class="custom-card contact-card sidebar-card">
                        <div class="custom-card-header">
                            <h5>
                                <i class="fas fa-envelope me-2"></i>Contact Tutor
                            </h5>
                        </div>
                        <div class="custom-card-body text-center">
                            <div class="contact-rate">Rs.{{ number_format((float) $job->hourly_rate, 2) }}/hour</div>

                            @guest
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Please <a href="{{ route('student.login') }}">login</a> to contact this tutor.
                                </div>
                            @else
                                <a href="{{ route('jobs.contact', $job) }}" class="btn-contact w-100 mb-3">
                                    <i class="fas fa-envelope me-2"></i>Send Message
                                </a>
                            @endguest

                            <div class="text-center" style="margin-top: 15px;">
                                <small class="text-muted">Response rate: Usually within 24 hours</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h6>Quick Info</h6>
                        </div>
                        <div class="custom-card-body">
                            <div class="quick-info-item">
                                <span class="quick-info-label">Job ID:</span>
                                <span class="quick-info-value">#{{ $job->id }}</span>
                            </div>
                            <div class="quick-info-item">
                                <span class="quick-info-label">Posted:</span>
                                <span class="quick-info-value">{{ $job->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="quick-info-item">
                                <span class="quick-info-label">Views:</span>
                                <span class="quick-info-value">{{ $job->views }}</span>
                            </div>
                            <div class="quick-info-item">
                                <span class="quick-info-label">Status:</span>
                                <span class="badge-status-{{ $job->status === 'active' ? 'active' : 'secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h6>Share this Job</h6>
                        </div>
                        <div class="custom-card-body">
                            <div class="d-grid gap-2">
                                <button class="share-btn" onclick="copyJobLink()">
                                    <i class="fas fa-link me-2"></i>Copy Link
                                </button>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank" class="share-btn text-center">
                                    <i class="fab fa-facebook me-2"></i>Share on Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($job->title) }}"
                                    target="_blank" class="share-btn text-center">
                                    <i class="fab fa-twitter me-2"></i>Share on Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Section -->
            <div class="mt-5">
                <div class="custom-card">
                    <div class="custom-card-header">
                        <h5><i class="fas fa-star me-2"></i>Rate this Tutor</h5>
                    </div>
                    <div class="custom-card-body">
                        @auth
                            <div class="rating-section">
                                <div class="current-rating mb-4">
                                    <h6>Current Rating</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="rating-stars me-3">
                                            @php
                                                $rating = $job->tutor->profile ? $job->tutor->profile->rating : 0;
                                                $totalRatings = $job->tutor->profile ? $job->tutor->profile->total_ratings : 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}" style="font-size: 1.2rem;"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-text">{{ number_format($rating, 1) }} out of 5 ({{ $totalRatings }} reviews)</span>
                                    </div>
                                </div>
                                
                                <div class="add-rating">
                                    <h6>Your Rating</h6>
                                    <form id="ratingForm" action="/tutor/{{ $job->tutor->id }}/rate" method="POST">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        
                                        <div class="rating-input mb-3">
                                            <div class="d-flex align-items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" class="rating-star" data-rating="{{ $i }}">
                                                        <i class="fas fa-star" style="font-size: 1.5rem;"></i>
                                                    </button>
                                                @endfor
                                                <span class="ms-3" id="ratingDisplay">Click to rate</span>
                                            </div>
                                            <input type="hidden" name="rating" id="ratingValue" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="review" class="form-label">Review (Optional)</label>
                                            <textarea class="form-control" id="review" name="review" rows="3" 
                                                      placeholder="Share your experience with this tutor..."></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary" id="submitRating" disabled>
                                            <i class="fas fa-star me-2"></i>Submit Rating
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h6>Login to Rate this Tutor</h6>
                                <p class="text-muted mb-3">Share your experience and help other students find the best tutors.</p>
                                <a href="/login" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Rate
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            @if($relatedJobs->count() > 0)
                <!-- Related Jobs -->
                <div class="mt-5">
                    <h4 class="mb-4" style="color: #2c3e50; font-weight: 700;">More Jobs from {{ $job->tutor->name }}</h4>
                    <div class="row">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="col-md-4 mb-4">
                                <div class="related-job-card">
                                    <div class="custom-card-body">
                                        <h6 class="related-job-title">{{ Str::limit($relatedJob->title, 40) }}</h6>
                                        <p class="text-muted small mb-3">{{ Str::limit($relatedJob->description, 80) }}</p>

                                        <!-- Subjects -->
                                        <div class="mb-3">
                                            @foreach(array_slice($relatedJob->subjects, 0, 2) as $subject)
                                                <span class="subject-badge">{{ $subject }}</span>
                                            @endforeach
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span
                                                class="related-job-rate">Rs.{{ number_format((float) $relatedJob->hourly_rate, 2) }}/hr</span>
                                            <small class="text-muted">{{ $relatedJob->views }} views</small>
                                        </div>

                                        <a href="{{ $relatedJob->url }}" class="btn-view-details w-100 text-center">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyJobLink() {
            navigator.clipboard.writeText(window.location.href).then(function () {
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
                button.classList.remove('share-btn');
                button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                button.style.color = 'white';

                setTimeout(function () {
                    button.innerHTML = originalText;
                    button.classList.add('share-btn');
                    button.style.background = '';
                    button.style.color = '';
                }, 2000);
            }).catch(function (err) {
                console.error('Could not copy text: ', err);
            });
        }

        // Rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const ratingStars = document.querySelectorAll('.rating-star');
            const ratingValue = document.getElementById('ratingValue');
            const ratingDisplay = document.getElementById('ratingDisplay');
            const submitButton = document.getElementById('submitRating');
            const ratingForm = document.getElementById('ratingForm');

            if (ratingStars.length > 0) {
                ratingStars.forEach((star, index) => {
                    // Hover effect
                    star.addEventListener('mouseenter', function() {
                        highlightStars(index + 1);
                    });

                    // Click effect
                    star.addEventListener('click', function() {
                        const rating = index + 1;
                        selectRating(rating);
                    });
                });

                // Reset hover effect when leaving the rating area
                document.querySelector('.rating-input').addEventListener('mouseleave', function() {
                    const currentRating = parseInt(ratingValue.value) || 0;
                    highlightStars(currentRating);
                });
            }

            function highlightStars(count) {
                ratingStars.forEach((star, index) => {
                    if (index < count) {
                        star.classList.add('hover');
                    } else {
                        star.classList.remove('hover');
                    }
                });
            }

            function selectRating(rating) {
                ratingValue.value = rating;
                
                // Update visual state
                ratingStars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                        star.classList.remove('hover');
                    } else {
                        star.classList.remove('active', 'hover');
                    }
                });

                // Update display text
                const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
                ratingDisplay.textContent = `${rating} star${rating > 1 ? 's' : ''} - ${ratingTexts[rating - 1]}`;
                
                // Enable submit button
                submitButton.disabled = false;
            }

            // Handle form submission
            if (ratingForm) {
                ratingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (!ratingValue.value) {
                        alert('Please select a rating before submitting.');
                        return;
                    }

                    // Submit form via AJAX
                    const formData = new FormData(ratingForm);
                    
                    fetch(ratingForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Thank you for your rating!');
                            // Optionally refresh the page to show updated rating
                            location.reload();
                        } else {
                            alert('Error submitting rating: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error submitting rating. Please try again.');
                    });
                });
            }
        });
    </script>
@endpush

@push('head')
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush