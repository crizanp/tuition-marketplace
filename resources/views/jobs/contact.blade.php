<!-- this is contact page -->

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
            background: linear-gradient(135deg, #000000, #21201f);
            color: white;
            border-radius: 16px 16px 0 0 !important;
            padding: 20px 25px;
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

        /* Job Summary Card */
        .job-summary-card {
            background: rgba(255, 107, 53, 0.05);
            border: 2px solid rgba(255, 107, 53, 0.1);
            border-radius: 16px;
        }

        .job-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .hourly-rate-display {
            color: #ff6b35;
            font-weight: 700;
            font-size: 1.8rem;
        }

        /* Tutor Info Styling */
        .tutor-info-section {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .tutor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 107, 53, 0.3);
        }

        .tutor-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #000000ff, #232322ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .tutor-name {
            color: #000000ff;
            font-weight: 700;
            margin-bottom: 0;
        }

        .verified-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Form Styling */
        .form-control {
            border: 2px solid rgba(255, 107, 53, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
        }

        /* Alert Styling */
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid rgba(40, 167, 69, 0.2);
            color: #155724;
            border-radius: 12px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 2px solid rgba(220, 53, 69, 0.2);
            color: #721c24;
            border-radius: 12px;
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

        /* Sidebar Styling */
        .sidebar-card {
            /* position: sticky; */
            /* top: 20px; */
        }

        .tutor-card {
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(247, 147, 30, 0.05));
            border: 2px solid rgba(255, 107, 53, 0.1);
        }

        .tutor-card-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 107, 53, 0.3);
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .tutor-card-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #000000ff, #232322ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Progress Bar */
        .progress {
            background: rgba(255, 107, 53, 0.1);
            border-radius: 10px;
        }

        .progress-bar {
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 10px;
        }

        /* Stats */
        .stats-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-label {
            color: #6c757d;
            font-weight: 600;
        }

        .stats-value {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Tips Card */
        .tips-card {
            background: rgba(255, 107, 53, 0.03);
            border: 2px solid rgba(255, 107, 53, 0.1);
        }

        .tips-card ul li {
            margin-bottom: 8px;
            color: #2c3e50;
        }

        /* Form Check */
        .form-check-input:checked {
            background-color: #ff6b35;
            border-color: #ff6b35;
        }

        .form-check-input:focus {
            border-color: #ff6b35;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.25);
        }

        .form-check-label a {
            color: #ff6b35;
            font-weight: 600;
        }

        /* Location styling */
        .location-text {
            color: #6c757d;
            font-size: 14px;
        }

        .location-icon {
            color: #ff6b35;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                margin: -20px auto 0;
                padding: 0 15px;
            }

            .job-title {
                font-size: 1.3rem;
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
        }

        @media (max-width: 480px) {
            .custom-card-body {
                padding: 15px;
            }

            .job-title {
                font-size: 1.2rem;
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
                        <li class="breadcrumb-item"><a href="{{ $job->url }}">{{ Str::limit($job->title, 30) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="main-container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Job Summary -->
                    <div class="custom-card job-summary-card">
                        <div class="custom-card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="job-title">{{ $job->title }}</h5>
                                                                        <p class="text-muted mb-2" id="job-desc-short">
                                                                            {{ Str::limit($job->description, 100) }}
                                                                            @if(strlen($job->description) > 100)
                                                                                <a href="#" id="show-full-desc" style="color:#ff6b35; text-decoration:none; margin-left:8px;" title="Show full description">
                                                                                    <i class="fas fa-ellipsis-h"></i> more
                                                                                </a>
                                                                            @endif
                                                                        </p>
                                                                        <p class="text-muted mb-2 d-none" id="job-desc-full">
                                                                            {{ $job->description }}
                                                                            <a href="#" id="hide-full-desc" style="color:#ff6b35; text-decoration:none; margin-left:8px;" title="Show less">
                                                                                <i class="fas fa-chevron-up"></i> less
                                                                            </a>
                                                                        </p>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var showFullDesc = document.getElementById('show-full-desc');
    var hideFullDesc = document.getElementById('hide-full-desc');
    var shortDesc = document.getElementById('job-desc-short');
    var fullDesc = document.getElementById('job-desc-full');
    if (showFullDesc && shortDesc && fullDesc) {
        showFullDesc.addEventListener('click', function(e) {
            e.preventDefault();
            shortDesc.classList.add('d-none');
            fullDesc.classList.remove('d-none');
        });
    }
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'hide-full-desc') {
            e.preventDefault();
            fullDesc.classList.add('d-none');
            shortDesc.classList.remove('d-none');
        }
    });
});
</script>
@endpush
                                    
                                    <!-- Tutor Info -->
                                    <div class="tutor-info-section">
                                        @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                            <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                                 class="tutor-avatar me-2" 
                                                 alt="Tutor">
                                        @else
                                            <div class="tutor-placeholder me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <strong class="tutor-name">{{ $job->tutor->name }}</strong>
                                                @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                                    <span class="verified-badge">
                                                        <i class="fas fa-check-circle"></i> Verified
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="hourly-rate-display">Rs.{{ number_format((float)$job->hourly_rate, 2) }}</div>
                                    <small class="text-muted">per hour</small>
                                    <div class="mt-2">
                                        <small class="location-text">
                                            <i class="fas fa-map-marker-alt location-icon me-1"></i>
                                            {{ $job->place }}, {{ $job->district }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h5>
                                <i class="fas fa-envelope me-2"></i>Send Message to {{ $job->tutor->name }}
                            </h5>
                        </div>
                        <div class="custom-card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('jobs.inquiry', $job) }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', auth()->user()->name ?? '') }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="Optional">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="6" 
                                              required 
                                              placeholder="Hi {{ $job->tutor->name }}, I'm interested in your {{ $job->title }} job. Please let me know more details about the schedule and requirements.">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimum 10 characters required</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ $job->url }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Job
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Tips -->
                    <div class="custom-card tips-card">
                        <div class="custom-card-header">
                            <h6>
                                <i class="fas fa-lightbulb me-2"></i>Tips for a Better Response
                            </h6>
                        </div>
                        <div class="custom-card-body">
                            <ul class="mb-0">
                                <li>Be specific about your learning goals and requirements</li>
                                <li>Mention your current level and any prior experience</li>
                                <li>Ask about availability and preferred schedule</li>
                                <li>Be polite and professional in your communication</li>
                                <li>Include your budget range if different from the posted rate</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Tutor Info -->
                    <div class="custom-card tutor-card sidebar-card">
                        <div class="custom-card-header">
                            <h6>About the Tutor</h6>
                        </div>
                        <div class="custom-card-body text-center">
                            @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                     class="tutor-card-avatar mb-3" 
                                     alt="Tutor">
                            @else
                                <div class="tutor-card-placeholder mb-3 mx-auto">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            
                            <h6 class="tutor-name">{{ $job->tutor->name }}</h6>
                            @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                <span class="verified-badge mb-2 d-inline-block">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @endif
                            <p class="text-muted small">
                                Member since {{ $job->tutor->created_at->format('M Y') }}
                            </p>
                            
                            <!-- Response Rate -->
                            <div class="mt-3">
                                <small class="text-muted">Response Rate</small>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar" style="width: 90%"></div>
                                </div>
                                <small class="text-success">Usually responds within 24 hours</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            <h6>Quick Stats</h6>
                        </div>
                        <div class="custom-card-body">
                            <div class="stats-item">
                                <span class="stats-label">Job Views:</span>
                                <span class="stats-value">{{ $job->views }}</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Inquiries:</span>
                                <span class="stats-value">{{ $job->inquiries }}</span>
                            </div>
                            <div class="stats-item">
                                <span class="stats-label">Posted:</span>
                                <span class="stats-value">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush