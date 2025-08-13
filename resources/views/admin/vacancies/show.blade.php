@extends('admin.layouts.app')

@section('title', 'Vacancy Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1">Vacancy Details</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.vacancies.index') }}">Vacancies</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.vacancies.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Vacancy Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>{{ $vacancy->title }}
                        </h5>
                        <div>
                            @if($vacancy->status === 'pending')
                                <span class="badge bg-warning">Pending Approval</span>
                            @elseif($vacancy->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                            
                            <span class="badge bg-{{ $vacancy->urgency === 'high' ? 'danger' : ($vacancy->urgency === 'medium' ? 'warning' : 'success') }} ms-2">
                                {{ ucfirst($vacancy->urgency) }} Priority
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted mb-2">Subject & Level</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-book text-primary me-2"></i>
                                <span>{{ $vacancy->subject }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>
                                <span>{{ $vacancy->grade_level }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted mb-2">Budget & Duration</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-rupee-sign text-success me-2"></i>
                                <span>Rs. {{ number_format($vacancy->budget_min) }} - Rs. {{ number_format($vacancy->budget_max) }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-info me-2"></i>
                                <span>{{ $vacancy->duration_hours }} hours per session</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">Description</h6>
                        <p class="text-muted">{{ $vacancy->description }}</p>
                    </div>

                    <!-- Location & Schedule -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted mb-2">Location</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <span>{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</span>
                            </div>
                            @if($vacancy->specific_location)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-location-dot text-danger me-2"></i>
                                    <span>{{ $vacancy->specific_location }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted mb-2">Schedule</h6>
                            @if($vacancy->schedule_days && count($vacancy->schedule_days) > 0)
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach($vacancy->schedule_days as $day)
                                        <span class="badge bg-secondary">{{ $day }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($vacancy->preferred_time)
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    <span>{{ $vacancy->preferred_time }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($vacancy->additional_requirements)
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">Additional Requirements</h6>
                            <p class="text-muted">{{ $vacancy->additional_requirements }}</p>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6 class="fw-bold text-muted mb-2">Posted</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <span>{{ $vacancy->created_at->format('M d, Y') }}</span>
                            </div>
                            <small class="text-muted">{{ $vacancy->created_at->diffForHumans() }}</small>
                        </div>
                        @if($vacancy->approved_at)
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Approved</h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>{{ $vacancy->approved_at->format('M d, Y') }}</span>
                                </div>
                                <small class="text-muted">{{ $vacancy->approved_at->diffForHumans() }}</small>
                            </div>
                        @endif
                        @if($vacancy->deadline_date)
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Deadline</h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-flag text-warning me-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($vacancy->deadline_date)->format('M d, Y') }}</span>
                                </div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($vacancy->deadline_date)->diffForHumans() }}</small>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if($vacancy->status === 'pending')
                    <div class="card-footer bg-light">
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.vacancies.approve', $vacancy->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this vacancy?')">
                                    <i class="fas fa-check me-2"></i>Approve Vacancy
                                </button>
                            </form>
                            <form action="{{ route('admin.vacancies.reject', $vacancy->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this vacancy?')">
                                    <i class="fas fa-times me-2"></i>Reject Vacancy
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Student Information Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-user text-primary me-2"></i>Student Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-primary text-white me-3">
                            {{ strtoupper(substr($vacancy->student->name, 0, 2)) }}
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $vacancy->student->name }}</h6>
                            <small class="text-muted">{{ $vacancy->student->email }}</small>
                        </div>
                    </div>
                    
                    @if($vacancy->student->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-success me-2"></i>
                            <span>{{ $vacancy->student->phone }}</span>
                        </div>
                    @endif
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-calendar text-info me-2"></i>
                        <span>Joined {{ $vacancy->student->created_at->format('M Y') }}</span>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope text-warning me-2"></i>
                        <span class="badge bg-{{ $vacancy->student->email_verified_at ? 'success' : 'warning' }}">
                            {{ $vacancy->student->email_verified_at ? 'Verified' : 'Unverified' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $vacancy->applications_count ?? 0 }}</h4>
                                <small class="text-muted">Applications</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-1">{{ $vacancy->views ?? 0 }}</h4>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs text-primary me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.vacancy-applications.index', ['vacancy' => $vacancy->id]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users me-2"></i>View Applications
                        </a>
                        <a href="mailto:{{ $vacancy->student->email }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-envelope me-2"></i>Contact Student
                        </a>
                        @if($vacancy->status === 'approved')
                            <button class="btn btn-outline-warning btn-sm" onclick="confirm('Are you sure you want to hide this vacancy?')">
                                <i class="fas fa-eye-slash me-2"></i>Hide Vacancy
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}

.badge {
    font-size: 0.75em;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.text-primary {
    color: #0d6efd !important;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}
</style>
@endsection
