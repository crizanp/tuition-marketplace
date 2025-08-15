@extends('admin.layouts.app')

@section('title', 'Vacancy Application Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt me-2"></i>Vacancy Application Details
        </h1>
        <div>
            <a href="{{ route('admin.vacancy-applications.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Applications
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Application Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Information</h6>
                </div>
                <div class="card-body">
                    <!-- Status and Basic Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            @if($application->status == 'pending')
                                <span class="badge badge-warning ms-2">Pending</span>
                            @elseif($application->status == 'approved')
                                <span class="badge badge-success ms-2">Approved</span>
                            @else
                                <span class="badge badge-danger ms-2">Rejected</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Applied:</strong> {{ $application->applied_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>

                    @if($application->reviewed_at)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <strong>Reviewed:</strong> {{ $application->reviewed_at->format('F j, Y \a\t g:i A') }}
                            </div>
                        </div>
                    @endif

                    <!-- Application Details -->
                    <div class="mb-4">
                        <h5>Cover Letter</h5>
                        <div class="border p-3 bg-dark rounded">
                            {{ $application->cover_letter }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Proposed Rate:</strong>
                            @if($application->proposed_rate)
                                Rs. {{ number_format($application->proposed_rate) }} per session
                            @else
                                <span class="text-muted">To be discussed</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Experience:</strong> {{ $application->experience_years }} years
                        </div>
                    </div>

                    @if($application->admin_notes)
                        <div class="mb-4">
                            <h5>Admin Notes</h5>
                            <div class="alert alert-info">
                                {{ $application->admin_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vacancy Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vacancy Details</h6>
                </div>
                <div class="card-body">
                    <h5>{{ $application->vacancy->title }}</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong> {{ $application->vacancy->subject }}<br>
                            <strong>Grade Level:</strong> {{ $application->vacancy->grade_level }}
                        </div>
                        <div class="col-md-6">
                            <strong>Budget:</strong> Rs. {{ number_format($application->vacancy->budget_min) }} - Rs. {{ number_format($application->vacancy->budget_max) }}<br>
                            <strong>Duration:</strong> {{ $application->vacancy->duration_hours }} hours/session
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="text-muted mt-2">{{ $application->vacancy->description }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Student:</strong>
                        @if($application->vacancy && $application->vacancy->student)
                            {{ $application->vacancy->student->name }}
                        @else
                            <span class="text-muted">Posted by Admin</span>
                        @endif
                    </div>

                    @if($application->vacancy)
                        <a href="{{ route('admin.vacancies.show', $application->vacancy->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye me-1"></i>View Full Vacancy
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tutor Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tutor Information</h6>
                </div>
                <div class="card-body">
                    @php $tutor = $application->tutor ?? null; @endphp
                    <div class="text-center mb-3">
                        @if($tutor && $tutor->kyc && $tutor->kyc->profile_photo)
                            <img src="{{ asset('storage/' . $tutor->kyc->profile_photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-center mb-3">
                        <h5>{{ $tutor->name ?? 'N/A' }}</h5>
                        <p class="text-muted">{{ $tutor->email ?? 'N/A' }}</p>
                        @if(!empty($tutor->phone))
                            <p class="text-muted">{{ $tutor->phone }}</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if($tutor && $tutor->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @elseif($tutor && $tutor->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">{{ ucfirst($tutor->status ?? 'N/A') }}</span>
                        @endif
                    </div>

                    @if($tutor && $tutor->kyc)
                        <div class="mb-3">
                            <strong>KYC Status:</strong>
                            @if($tutor->kyc->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($tutor->kyc->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($tutor->kyc->status) }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>Jobs Posted:</strong> {{ $tutor ? $tutor->jobs->count() : 0 }}<br>
                        <strong>Applications:</strong> {{ $tutor ? $tutor->vacancyApplications->count() : 0 }}
                    </div>

                    @if($tutor)
                        <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-info btn-sm w-100">
                            <i class="fas fa-user me-1"></i>View Tutor Profile
                        </a>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($application->status == 'pending')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vacancy-applications.approve', $application) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this application?')">
                                <i class="fas fa-check me-2"></i>Approve Application
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i>Reject Application
                        </button>
                    </div>
                </div>
            @endif

            <!-- Update Status -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vacancy-applications.status', $application->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Add notes about this application...">{{ $application->admin_notes }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($application->status == 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.vacancy-applications.reject', $application) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for rejection</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required 
                                  placeholder="Provide a detailed reason for rejecting this application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
