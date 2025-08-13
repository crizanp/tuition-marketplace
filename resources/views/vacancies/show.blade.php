@extends('layouts.app')

@section('title', $vacancy->title . ' - Teaching Vacancy')

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('vacancies.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Vacancies
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Vacancy Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $vacancy->title }}</h2>
                        <span class="badge bg-{{ $vacancy->urgency === 'high' ? 'danger' : ($vacancy->urgency === 'medium' ? 'warning' : 'success') }} fs-6">
                            {{ ucfirst($vacancy->urgency) }} Priority
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Quick Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book text-primary me-3"></i>
                                <div>
                                    <strong>Subject:</strong> {{ $vacancy->subject }}<br>
                                    <small class="text-muted">Grade {{ $vacancy->grade_level }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-rupee-sign text-success me-3"></i>
                                <div>
                                    <strong>Budget:</strong> Rs. {{ number_format($vacancy->budget_min) }} - Rs. {{ number_format($vacancy->budget_max) }}<br>
                                    <small class="text-muted">Per session</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-info me-3"></i>
                                <div>
                                    <strong>Duration:</strong> {{ $vacancy->duration_hours }} hours<br>
                                    <small class="text-muted">Per session</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-danger me-3"></i>
                                <div>
                                    <strong>Location:</strong> {{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}<br>
                                    @if($vacancy->address)
                                        <small class="text-muted">{{ Str::limit($vacancy->address, 30) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5><i class="fas fa-info-circle text-primary me-2"></i>Description</h5>
                        <p class="text-muted">{{ $vacancy->description }}</p>
                    </div>

                    <!-- Schedule -->
                    @if($vacancy->schedule_days && $vacancy->schedule_times)
                        <div class="mb-4">
                            <h5><i class="fas fa-calendar text-primary me-2"></i>Schedule</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Days:</strong>
                                    <div class="mt-1">
                                        @foreach($vacancy->schedule_days as $day)
                                            <span class="badge bg-secondary me-1">{{ $day }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Times:</strong>
                                    <div class="mt-1">
                                        @foreach($vacancy->schedule_times as $time)
                                            <span class="badge bg-info me-1">{{ $time }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Requirements -->
                    @if($vacancy->requirements && count($vacancy->requirements) > 0)
                        <div class="mb-4">
                            <h5><i class="fas fa-check-circle text-primary me-2"></i>Requirements</h5>
                            <ul class="list-unstyled">
                                @foreach($vacancy->requirements as $requirement)
                                    <li class="mb-1">
                                        <i class="fas fa-check text-success me-2"></i>{{ $requirement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Student Info -->
                    <div class="mb-4">
                        <h5><i class="fas fa-user text-primary me-2"></i>Student Information</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                            </div>
                            <div>
                                <strong>{{ $vacancy->student->name }}</strong><br>
                                <small class="text-muted">Posted {{ $vacancy->approved_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Application Form -->
            @auth('tutor')
                @if(!$hasApplied)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Apply for this Vacancy</h5>
                        </div>
                        <div class="card-body">
                            @if(Auth::guard('tutor')->user()->kyc && Auth::guard('tutor')->user()->kyc->status === 'approved')
                                <form action="{{ route('vacancies.apply', $vacancy->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="cover_letter" class="form-label">Cover Letter <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="cover_letter" name="cover_letter" rows="4" 
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
                                    
                                    <div class="mb-3">
                                        <label for="experience_years" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="experience_years" name="experience_years" 
                                               min="0" max="50" required value="{{ old('experience_years', 0) }}">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Application
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>KYC Required</h6>
                                    <p class="mb-2">You must complete and have your KYC approved before applying for vacancies.</p>
                                    <a href="{{ route('tutor.kyc.show') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-id-card me-1"></i>Complete KYC
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Application Submitted</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <p class="mb-2">You have already applied for this vacancy.</p>
                                <p class="mb-0"><strong>Status:</strong> 
                                    <span class="badge bg-{{ $userApplication->status === 'approved' ? 'success' : ($userApplication->status === 'rejected' ? 'danger' : 'warning') }}">
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login Required</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Please login as a tutor to apply for this vacancy.</p>
                        <a href="{{ route('tutor.login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login as Tutor
                        </a>
                        <a href="{{ route('tutor.register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Register as Tutor
                        </a>
                    </div>
                </div>
            @endauth

            <!-- Other Applications -->
            @if($vacancy->applications->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Other Applications ({{ $vacancy->applications->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($vacancy->applications->take(5) as $application)
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="fas fa-user fa-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <strong>{{ $application->tutor->name }}</strong><br>
                                    <small class="text-muted">{{ $application->experience_years }} years experience</small>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($vacancy->applications->count() > 5)
                            <small class="text-muted">And {{ $vacancy->applications->count() - 5 }} more...</small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
