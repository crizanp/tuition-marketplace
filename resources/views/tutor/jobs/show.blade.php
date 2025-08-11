@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $job->title }}</h4>
                            <span class="badge {{ $job->status_badge_class }} fs-6">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Basic Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">Hourly Rate</h6>
                                <h4 class="text-success mb-3">${{ number_format($job->hourly_rate, 2) }}/hour</h4>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Teaching Mode</h6>
                                <p class="mb-3">{{ $job->teaching_mode_label }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h6 class="text-primary">Description</h6>
                            <p class="text-muted">{{ $job->description }}</p>
                        </div>

                        <!-- Subjects -->
                        <div class="mb-4">
                            <h6 class="text-primary">Subjects</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($job->subjects as $subject)
                                    <span class="badge bg-secondary">{{ $subject }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <h6 class="text-primary">Location</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Address:</strong></p>
                                    <p class="text-muted mb-0">
                                        {{ $job->place }}, {{ $job->district }}<br>
                                        {{ $job->state }}, {{ $job->country }}
                                        @if($job->landmark)
                                            <br><small>Near {{ $job->landmark }}</small>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($job->ward_no || $job->postal_code)
                                        <p class="mb-1"><strong>Additional Details:</strong></p>
                                        @if($job->ward_no)
                                            <p class="text-muted mb-0">Ward No: {{ $job->ward_no }}</p>
                                        @endif
                                        @if($job->postal_code)
                                            <p class="text-muted mb-0">Postal Code: {{ $job->postal_code }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Teaching Preferences -->
                        <div class="mb-4">
                            <h6 class="text-primary">Teaching Preferences</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Gender Preference:</strong></p>
                                    <p class="text-muted">{{ ucfirst($job->gender_preference) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Session Type:</strong></p>
                                    <p class="text-muted">{{ ucfirst($job->session_type) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Max Students:</strong></p>
                                    <p class="text-muted">{{ $job->max_students }}</p>
                                </div>
                            </div>
                            @if($job->student_level)
                                <div class="row">
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Student Level:</strong></p>
                                        <p class="text-muted">{{ $job->student_level }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Preferred Times -->
                        @if($job->preferred_times && count($job->preferred_times) > 0)
                            <div class="mb-4">
                                <h6 class="text-primary">Preferred Teaching Times</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($job->preferred_times as $time)
                                        <span class="badge bg-info">{{ ucfirst($time) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if($job->requirements)
                            <div class="mb-4">
                                <h6 class="text-primary">Special Requirements</h6>
                                <p class="text-muted">{{ $job->requirements }}</p>
                            </div>
                        @endif

                        <!-- Gallery -->
                        @if($job->gallery && count($job->gallery) > 0)
                            <div class="mb-4">
                                <h6 class="text-primary">Gallery</h6>
                                <div class="row">
                                    @foreach($job->gallery as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ Storage::url($image) }}" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 alt="Gallery Image"
                                                 style="height: 200px; object-fit: cover; width: 100%;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Tutor Information -->
                        <div class="mb-4">
                            <h6 class="text-primary">Tutor Information</h6>
                            <div class="d-flex align-items-center">
                                @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                    <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;"
                                         alt="Tutor Photo">
                                @else
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $job->tutor->name }}</h6>
                                    <p class="text-muted mb-0">{{ $job->tutor->email }}</p>
                                    @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                        <span class="badge bg-success">Verified Tutor</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tutor.jobs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                            </a>
                            <div>
                                <a href="{{ route('tutor.jobs.edit', $job) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit me-2"></i>Edit Job
                                </a>
                                <button type="button" 
                                        class="btn btn-{{ $job->status === 'active' ? 'secondary' : 'success' }}"
                                        onclick="toggleJobStatus({{ $job->id }})">
                                    <i class="fas fa-{{ $job->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                    {{ $job->status === 'active' ? 'Pause' : 'Activate' }} Job
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Job Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary mb-1">{{ $job->views }}</h4>
                                <small class="text-muted">Views</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ $job->inquiries }}</h4>
                                <small class="text-muted">Inquiries</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-12">
                                <p class="mb-1"><strong>Posted:</strong></p>
                                <small class="text-muted">{{ $job->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        @if($job->expires_at)
                            <hr>
                            <div class="row text-center">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Expires:</strong></p>
                                    <small class="text-{{ $job->isExpired() ? 'danger' : 'muted' }}">
                                        {{ $job->expires_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="shareJob()">
                                <i class="fas fa-share me-2"></i>Share Job
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="copyJobLink()">
                                <i class="fas fa-link me-2"></i>Copy Link
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $job->id }})">
                                <i class="fas fa-trash me-2"></i>Delete Job
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleJobStatus(jobId) {
    fetch(`/tutor/jobs/${jobId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating job status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating job status');
    });
}

function confirmDelete(jobId) {
    const form = document.getElementById('deleteForm');
    form.action = `/tutor/jobs/${jobId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function shareJob() {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: '{{ $job->title }}',
            text: 'Check out this tutoring opportunity',
            url: url
        });
    } else {
        copyJobLink();
    }
}

function copyJobLink() {
    const url = window.location.href;
    
    navigator.clipboard.writeText(url).then(function() {
        alert('Job link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
        alert('Error copying link');
    });
}
</script>
@endpush
