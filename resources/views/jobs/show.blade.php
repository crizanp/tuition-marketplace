@extends('layouts.app')

@section('content')
<div class="py-4">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobs.index') }}">Jobs</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($job->title, 50) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Job Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h1 class="h3 mb-2">{{ $job->title }}</h1>
                                @if($job->is_featured)
                                    <span class="badge bg-warning me-2">
                                        <i class="fas fa-star"></i> Featured
                                    </span>
                                @endif
                                <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            <div class="text-end">
                                <div class="h4 text-success mb-0">${{ number_format((float)$job->hourly_rate, 2) }}</div>
                                <small class="text-muted">per hour</small>
                            </div>
                        </div>

                        <!-- Tutor Info -->
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="Tutor">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $job->tutor->name }}</h6>
                                <div class="d-flex align-items-center">
                                    @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                        <span class="badge bg-success me-2">
                                            <i class="fas fa-check-circle"></i> Verified Tutor
                                        </span>
                                    @endif
                                    <small class="text-muted">
                                        Member since {{ $job->tutor->created_at->format('M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Job Stats -->
                        <div class="row text-center mb-3">
                            <div class="col">
                                <div class="border-end">
                                    <div class="h5 mb-0">{{ $job->views }}</div>
                                    <small class="text-muted">Views</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border-end">
                                    <div class="h5 mb-0">{{ $job->inquiries }}</div>
                                    <small class="text-muted">Inquiries</small>
                                </div>
                            </div>
                            <div class="col">
                                <div class="h5 mb-0">{{ $job->created_at->diffForHumans() }}</div>
                                <small class="text-muted">Posted</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Job Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $job->description }}</p>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Job Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Subjects:</strong>
                                <div class="mt-1">
                                    @foreach($job->subjects as $subject)
                                        <span class="badge bg-secondary me-1">{{ $subject }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Teaching Mode:</strong>
                                <div class="mt-1">
                                    <span class="badge bg-primary">{{ $job->getTeachingModeLabel() }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Student Level:</strong>
                                <div class="mt-1">{{ $job->student_level }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Session Type:</strong>
                                <div class="mt-1">{{ ucfirst($job->session_type) }} (Max {{ $job->max_students }} students)</div>
                            </div>
                            @if($job->preferred_times && count($job->preferred_times) > 0)
                            <div class="col-md-6 mb-3">
                                <strong>Preferred Times:</strong>
                                <div class="mt-1">
                                    @foreach($job->preferred_times as $time)
                                        <span class="badge bg-info me-1">{{ ucfirst($time) }}</span>
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Location
                        </h5>
                    </div>
                    <div class="card-body">
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Requirements</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $job->requirements }}</p>
                    </div>
                </div>
                @endif

                @if($job->gallery && count($job->gallery) > 0)
                <!-- Gallery -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($job->gallery as $image)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ Storage::url($image) }}" class="img-fluid rounded" alt="Job Gallery">
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
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>Contact Tutor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="h4 text-success">${{ number_format((float)$job->hourly_rate, 2) }}/hour</div>
                        </div>
                        
                        @guest
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Please <a href="{{ route('student.login') }}">login</a> to contact this tutor.
                            </div>
                        @else
                            <a href="{{ route('jobs.contact', $job) }}" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-envelope me-2"></i>Send Message
                            </a>
                        @endguest

                        <div class="text-center">
                            <small class="text-muted">Response rate: Usually within 24 hours</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Quick Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Job ID:</span>
                            <span class="fw-bold">#{{ $job->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Posted:</span>
                            <span>{{ $job->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Views:</span>
                            <span>{{ $job->views }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Share -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Share this Job</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="copyJobLink()">
                                <i class="fas fa-link me-2"></i>Copy Link
                            </button>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook me-2"></i>Share on Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($job->title) }}" 
                               target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter me-2"></i>Share on Twitter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($relatedJobs->count() > 0)
        <!-- Related Jobs -->
        <div class="mt-5">
            <h4 class="mb-4">More Jobs from {{ $job->tutor->name }}</h4>
            <div class="row">
                @foreach($relatedJobs as $relatedJob)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($relatedJob->title, 40) }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($relatedJob->description, 80) }}</p>
                                
                                <!-- Subjects -->
                                <div class="mb-2">
                                    @foreach(array_slice($relatedJob->subjects, 0, 2) as $subject)
                                        <span class="badge bg-secondary me-1">{{ $subject }}</span>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success fw-bold">${{ number_format((float)$relatedJob->hourly_rate, 2) }}/hr</span>
                                    <small class="text-muted">{{ $relatedJob->views }} views</small>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('jobs.show', $relatedJob) }}" class="btn btn-outline-primary btn-sm w-100">
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
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-primary');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush

@push('head')
<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
