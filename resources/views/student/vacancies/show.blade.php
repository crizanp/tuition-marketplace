@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header with Actions -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('student.vacancies.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Vacancies
                </a>
                
                <div class="d-flex gap-2">
                    @if(in_array($vacancy->status, ['pending', 'rejected']))
                        <a href="{{ route('student.vacancies.edit', $vacancy) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>
                            Edit Vacancy
                        </a>
                    @endif
                    
                    @if($vacancy->status !== 'approved')
                        <form action="{{ route('student.vacancies.destroy', $vacancy) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this vacancy?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>

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
                                    <h6 class="text-muted mb-2">Preferred Days</h6>
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
                                    <h6 class="text-muted mb-2">Preferred Time Slots</h6>
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
                        <small class="text-muted">
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
    </div>
</div>

<style>
.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #e9ecef;
    transition: transform 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
}

.info-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.info-label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.info-value {
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}

.schedule-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
}

.badge {
    font-size: 12px;
}
</style>
@endsection
