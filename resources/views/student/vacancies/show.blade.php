@extends('layouts.app')

@section('content')
<div class="container py-5">
    <style>
    /* Color Variables */
    :root {
        --orange: #ff6a00;
        --black: #000000;
        --white: #ffffff;
        --light-gray: #f8f9fa;
        --border-light: #e9ecef;
        --text-muted: #6c757d;
    }

    /* Base Styling */
    body, .container {
        background: var(--white);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Main Card Styling */
    .card.shadow-sm {
        background: var(--white);
        color: var(--black);
        border: 1px solid var(--border-light);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /* Header Status Colors */
    .bg-warning { 
        background: var(--orange) !important; 
        color: var(--white) !important;
    }
    .bg-success { 
        background: #28a745 !important; 
        color: var(--white) !important;
    }
    .bg-danger { 
        background: #dc3545 !important; 
        color: var(--white) !important;
    }

    .card-header {
        border-bottom: 1px solid var(--border-light);
        padding: 1.5rem;
    }

    .card-header h4,
    .card-header small {
        color: var(--white);
        margin: 0;
    }

    .card-header h4 {
        font-weight: 600;
        font-size: 1.5rem;
        line-height: 1.3;
    }

    .card-header small {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    /* Status Badges in Header */
    .card-header .badge.bg-light {
        background: var(--white) !important;
        color: var(--black) !important;
        font-weight: 500;
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
        border-radius: 6px;
    }

    /* Card Body */
    .card-body {
        background: var(--white);
        color: var(--black);
        padding: 2rem;
    }

    /* Section Headings */
    .card-body h5 {
        color: var(--black);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .card-body h5 i {
        color: var(--orange);
        margin-right: 0.5rem;
    }

    .card-body h6 {
        color: var(--black);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }

    .card-body h6 i {
        color: var(--orange);
        margin-right: 0.5rem;
    }

    /* Description */
    .lead {
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--black);
        margin-bottom: 0;
    }

    /* Info Cards Grid */
    .info-card {
        background: var(--light-gray);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--orange);
    }

    .info-icon {
        font-size: 24px;
        margin-bottom: 0.75rem;
        color: var(--orange);
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        color: var(--black);
        font-weight: 600;
        line-height: 1.3;
    }

    /* Schedule Sections */
    .schedule-section {
        background: var(--light-gray);
        border: 1px solid var(--border-light);
        padding: 1.25rem;
        border-radius: 8px;
    }

    .schedule-section h6 {
        color: var(--black) !important;
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    /* Badges */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.4rem 0.7rem;
        border-radius: 6px;
        margin: 0.15rem;
    }

    .badge.bg-primary,
    .badge.bg-info,
    .badge.bg-warning {
        background: var(--orange) !important;
        color: var(--white);
    }

    .badge.bg-light {
        background: var(--white) !important;
        color: var(--black) !important;
        border: 1px solid var(--border-light);
    }

    .badge.bg-secondary {
        background: var(--text-muted) !important;
        color: var(--white);
    }

    .badge.bg-danger {
        background: #dc3545 !important;
        color: var(--white);
    }

    .badge.bg-success {
        background: #28a745 !important;
        color: var(--white);
    }

    /* Priority Badges */
    .badge.p-2 {
        padding: 0.6rem 1rem !important;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Lists */
    .list-group-item {
        background: transparent;
        color: var(--black);
        border: none;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item i {
        color: var(--orange);
        margin-right: 0.5rem;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        border: none;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert-heading {
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .alert hr {
        border-color: rgba(0, 0, 0, 0.1);
        margin: 0.75rem 0;
    }

    /* Card Footer */
    .card-footer.bg-light {
        background: var(--light-gray) !important;
        border-top: 1px solid var(--border-light);
        padding: 1.25rem 2rem;
    }

    .card-footer small {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    /* Buttons */
    .btn {
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border-width: 2px;
    }

    .btn-outline-success,
    .btn-outline-primary,
    .btn-outline-danger {
        border-color: var(--orange);
        color: var(--orange);
        background: transparent;
    }

    .btn-outline-success:hover,
    .btn-outline-primary:hover,
    .btn-outline-danger:hover {
        background: var(--orange);
        border-color: var(--orange);
        color: var(--white);
        transform: translateY(-1px);
    }

    .btn-warning {
        background: var(--orange);
        color: var(--white);
        border: 2px solid var(--orange);
    }

    .btn-warning:hover {
        background: #e55a00;
        border-color: #e55a00;
        color: var(--white);
        transform: translateY(-1px);
    }

    /* Text Colors */
    .text-primary,
    .text-success,
    .text-info {
        color: var(--orange) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .text-black {
        color: var(--black) !important;
    }

    /* Close Button */
    .btn-close {
        filter: none;
        background: none;
        opacity: 0.6;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Gap Utilities */
    .gap-2 {
        gap: 0.5rem !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }

        .card-footer.bg-light {
            padding: 1rem 1.5rem;
        }

        .card-header {
            padding: 1.25rem;
        }

        .info-card {
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .schedule-section {
            padding: 1rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch !important;
        }

        .card-header .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start !important;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-5 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
    }

    /* Professional Enhancements */
    .opacity-75 {
        opacity: 0.9 !important;
    }

    /* Focus States for Accessibility */
    .btn:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 106, 0, 0.25);
        outline: none;
    }

    /* Clean Typography */
    body {
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--black);
    }

    /* Professional Spacing */
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    /* Smooth Transitions */
    * {
        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
    }

    /* Clean Flex Wrapper */
    .d-flex.flex-wrap.gap-2 {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
    </style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Vacancy Card -->
    <div class="card shadow-sm">
        <!-- Status Header -->
        <div class="card-header d-flex justify-content-between align-items-center
            @if($vacancy->status === 'pending') bg-warning
            @elseif($vacancy->status === 'approved') bg-success
            @elseif($vacancy->status === 'rejected') bg-danger
            @endif text-white">
            <div>
                <h4 class="mb-0">{{ $vacancy->title }}</h4>
                <small class="opacity-75">Posted {{ $vacancy->created_at->format('F j, Y \a\t g:i A') }}</small>
            </div>
            <div>
                @if($vacancy->status === 'pending')
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-clock me-1"></i>
                        Pending Review
                    </span>
                @elseif($vacancy->status === 'approved')
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-check-circle me-1"></i>
                        Approved
                    </span>
                @elseif($vacancy->status === 'rejected')
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-times-circle me-1"></i>
                        Rejected
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body">
            <!-- Description -->
            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Description
                </h5>
                <p class="lead">{{ $vacancy->description }}</p>
            </div>

            <!-- Quick Info Grid -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="info-card text-center">
                        <div class="info-icon">
                            <i class="fas fa-book text-primary"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Subject</div>
                            <div class="info-value">{{ $vacancy->subject }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="info-card text-center">
                        <div class="info-icon">
                            <i class="fas fa-graduation-cap text-success"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Grade Level</div>
                            <div class="info-value">{{ $vacancy->grade_level }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="info-card text-center">
                        <div class="info-icon">
                            <i class="fas fa-money-bill-wave text-success"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Budget Range</div>
                            <div class="info-value">{{ $vacancy->budget_range }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="info-card text-center">
                        <div class="info-icon">
                            <i class="fas fa-clock text-info"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Duration</div>
                            <div class="info-value">{{ $vacancy->duration_hours }}h sessions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Details -->
            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    Schedule Preferences
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="schedule-section">
                            <h6 class="text-black mb-2">Preferred Days</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if($vacancy->schedule_days)
                                    @foreach($vacancy->schedule_days as $day)
                                        <span class="badge bg-primary">{{ $day }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="schedule-section">
                            <h6 class="text-black mb-2">Preferred Time Slots</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @if($vacancy->schedule_times)
                                    @foreach($vacancy->schedule_times as $time)
                                        <span class="badge bg-info">{{ $time }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Other Details -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Location Type
                    </h6>
                    <p class="mb-0">
                        <span class="badge bg-light text-dark p-2">
                            {{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}
                        </span>
                    </p>
                    @if($vacancy->address)
                        <p class="mt-2 mb-0">
                            <strong>Address:</strong> {{ $vacancy->address }}
                        </p>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Priority Level
                    </h6>
                    <p class="mb-0">
                        @if($vacancy->urgency === 'high')
                            <span class="badge bg-danger p-2">
                                <i class="fas fa-exclamation me-1"></i>
                                High Priority
                            </span>
                        @elseif($vacancy->urgency === 'medium')
                            <span class="badge bg-warning p-2">
                                <i class="fas fa-clock me-1"></i>
                                Medium Priority
                            </span>
                        @else
                            <span class="badge bg-secondary p-2">
                                <i class="fas fa-minus me-1"></i>
                                Low Priority
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Additional Requirements -->
            @if($vacancy->requirements && count($vacancy->requirements) > 0)
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-list-check text-primary me-2"></i>
                        Additional Requirements
                    </h5>
                    <ul class="list-group list-group-flush">
                        @foreach($vacancy->requirements as $requirement)
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i>
                                {{ $requirement }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Admin Notes (if rejected) -->
            @if($vacancy->status === 'rejected' && $vacancy->admin_notes)
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>
                        Admin Feedback
                    </h6>
                    <p class="mb-0">{{ $vacancy->admin_notes }}</p>
                    @if($vacancy->rejected_at)
                        <hr>
                        <small class="text-muted">
                            Rejected on {{ $vacancy->rejected_at->format('F j, Y \a\t g:i A') }}
                        </small>
                    @endif
                </div>
            @endif

            <!-- Approval Info -->
            @if($vacancy->status === 'approved' && $vacancy->approved_at)
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-check-circle me-2"></i>
                        Vacancy Approved
                    </h6>
                    <p class="mb-0">Your vacancy has been approved and is now visible to tutors.</p>
                    <hr>
                    <small class="text-muted">
                        Approved on {{ $vacancy->approved_at->format('F j, Y \a\t g:i A') }}
                    </small>
                </div>
            @endif
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-black">
                    Last updated {{ $vacancy->updated_at->diffForHumans() }}
                </small>
                
                @if($vacancy->status === 'approved')
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-eye me-2"></i>
                        View Public Listing
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection