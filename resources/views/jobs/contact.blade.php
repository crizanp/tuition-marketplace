@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobs.show', $job) }}">{{ Str::limit($job->title, 30) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Job Summary -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">{{ $job->title }}</h5>
                                <p class="text-muted mb-2">{{ Str::limit($job->description, 100) }}</p>
                                
                                <!-- Tutor Info -->
                                <div class="d-flex align-items-center">
                                    @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                        <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             alt="Tutor">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $job->tutor->name }}</strong>
                                        @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                            <i class="fas fa-check-circle text-success ms-1" title="Verified"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="h4 text-success">${{ number_format((float)$job->hourly_rate, 2) }}</div>
                                <small class="text-muted">per hour</small>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $job->place }}, {{ $job->district }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>Send Message to {{ $job->tutor->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
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
                                <a href="{{ route('jobs.show', $job) }}" class="btn btn-secondary">
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
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Tips for a Better Response
                        </h6>
                    </div>
                    <div class="card-body">
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">About the Tutor</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                            <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;"
                                 alt="Tutor">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto"
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        
                        <h6>{{ $job->tutor->name }}</h6>
                        @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                            <span class="badge bg-success mb-2">
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
                                <div class="progress-bar bg-success" style="width: 90%"></div>
                            </div>
                            <small class="text-success">Usually responds within 24 hours</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Job Views:</span>
                            <span class="fw-bold">{{ $job->views }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Inquiries:</span>
                            <span class="fw-bold">{{ $job->inquiries }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Posted:</span>
                            <span>{{ $job->created_at->diffForHumans() }}</span>
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
