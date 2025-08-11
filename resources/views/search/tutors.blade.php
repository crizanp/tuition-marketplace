@extends('layouts.app')

@section('navbar')
    @if(Auth::guard('tutor')->check())
        @include('partials.tutor-navbar')
    @elseif(Auth::check())
        @include('partials.student-navbar')
    @else
        @include('partials.guest-navbar')
    @endif
@endsection

@section('content')
<div class="container py-4">
    <!-- Search Bar at Top -->
    <div class="search-section mb-4">
        @include('components.search-bar', [
            'action' => route('search.tutors'),
            'placeholder' => 'Search for tutors, subjects, or skills...',
            'size' => 'normal'
        ])
    </div>

    <!-- Search Results Header -->
    <div class="search-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">
                    <i class="fas fa-search me-2"></i>
                    Search Results
                </h2>
                <p class="text-muted mb-0">
                    Found {{ $jobs->total() }} jobs
                    @if($subject || $location || $keyword)
                        matching your criteria
                    @endif
                </p>
                
                @if($subject || $location || $keyword)
                    <div class="search-filters mt-2">
                        @if($keyword)
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-search me-1"></i>
                                "{{ $keyword }}"
                                <a href="{{ request()->fullUrlWithQuery(['keyword' => null]) }}" class="text-white ms-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        @if($subject)
                            <span class="badge bg-success me-2">
                                <i class="fas fa-book me-1"></i>
                                {{ $subject }}
                                <a href="{{ request()->fullUrlWithQuery(['subject' => null]) }}" class="text-white ms-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        @if($location)
                            <span class="badge bg-info me-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $location }}
                                <a href="{{ request()->fullUrlWithQuery(['location' => null]) }}" class="text-white ms-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="search-options">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="view" id="grid-view" checked>
                    <label class="btn btn-outline-primary" for="grid-view">
                        <i class="fas fa-th-large"></i>
                    </label>
                    
                    <input type="radio" class="btn-check" name="view" id="list-view">
                    <label class="btn btn-outline-primary" for="list-view">
                        <i class="fas fa-list"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>

    @if($jobs->count() > 0)
        <!-- Jobs Grid -->
        <div class="jobs-container" id="jobsContainer">
            <div class="row" id="jobsGrid">
                @foreach($jobs as $job)
                    <div class="col-lg-4 col-md-6 mb-4 job-card-wrapper">
                        <div class="job-card h-100">
                            <!-- Job Header -->
                            <div class="job-header">
                                <div class="tutor-avatar">
                                    @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                        <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" alt="{{ $job->tutor->name }}" class="avatar-img">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    @if($job->tutor->status === 'active')
                                        <div class="status-badge verified">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="job-info">
                                    <h5 class="job-title">{{ $job->title }}</h5>
                                    <p class="tutor-name">by {{ $job->tutor->name }}</p>
                                    <div class="tutor-rating">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= ($job->tutor->profile->rating ?? 4) ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-text">({{ number_format($job->tutor->profile->rating ?? 4, 1) }})</span>
                                    </div>
                                    @if($job->hourly_rate)
                                        <div class="job-rate">
                                            <span class="rate-amount">Rs. {{ number_format($job->hourly_rate) }}</span>
                                            <span class="rate-period">/hour</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Job Details -->
                            <div class="job-details">
                                @if($job->subjects && count($job->subjects) > 0)
                                    <div class="subjects-section mb-3">
                                        <h6 class="section-title">
                                            <i class="fas fa-book me-2"></i>
                                            Subjects
                                        </h6>
                                        <div class="subjects-tags">
                                            @foreach(array_slice($job->subjects, 0, 3) as $subject)
                                                <span class="subject-tag">{{ $subject }}</span>
                                            @endforeach
                                            @if(count($job->subjects) > 3)
                                                <span class="more-subjects">+{{ count($job->subjects) - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($job->description)
                                    <div class="description-section mb-3">
                                        <p class="job-description">{{ Str::limit($job->description, 100) }}</p>
                                    </div>
                                @endif

                                @php
                                    $location_parts = array_filter([
                                        $job->place, 
                                        $job->district, 
                                        $job->state
                                    ]);
                                @endphp

                                @if(count($location_parts) > 0)
                                    <div class="location-section mb-3">
                                        <span class="location-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ implode(', ', $location_parts) }}
                                        </span>
                                    </div>
                                @endif

                                @if($job->teaching_mode)
                                    <div class="mode-section mb-3">
                                        <span class="mode-badge">
                                            <i class="fas fa-{{ $job->teaching_mode === 'online' ? 'laptop' : 'home' }} me-1"></i>
                                            {{ ucfirst($job->teaching_mode) }}
                                        </span>
                                    </div>
                                @endif

                                @if($job->student_level)
                                    <div class="level-section mb-3">
                                        <span class="level-badge">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            {{ $job->student_level }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Job Actions -->
                            <div class="job-actions">
                                <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-eye me-2"></i>
                                    View Tutor Profile
                                </a>
                                <button class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-message"></i>
                                </button>
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $jobs->withQueryString()->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="no-results text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-3">No jobs found</h4>
            <p class="text-muted mb-4">Try adjusting your search criteria or browse all available jobs.</p>
            <a href="{{ route('search.tutors') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>
                Clear Filters
            </a>
        </div>
    @endif
</div>

<style>
.search-section {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.search-section .search-container {
    margin-bottom: 0;
}

.search-filters .badge {
    font-size: 12px;
    padding: 8px 12px;
}

.search-filters .badge a {
    text-decoration: none;
    opacity: 0.8;
}

.search-filters .badge a:hover {
    opacity: 1;
}

.tutor-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.job-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.tutor-card:hover, .job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.tutor-header, .job-header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
}

.tutor-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
}

.avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.status-badge {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #28a745;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
    border: 2px solid white;
}

.tutor-info {
    flex: 1;
}

.tutor-name {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.job-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.tutor-name {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 8px;
}

.job-description {
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
    margin: 0;
}

.mode-badge, .level-badge {
    background: #e8f4f8;
    color: #17a2b8;
    border: 1px solid #b8daff;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
}

.tutor-rating {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    color: #ddd;
    font-size: 14px;
}

.stars i.active {
    color: #ffc107;
}

.rating-text {
    font-size: 14px;
    color: #6c757d;
}

.tutor-rate {
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.rate-amount {
    font-size: 18px;
    font-weight: 700;
    color: #28a745;
}

.rate-period {
    font-size: 14px;
    color: #6c757d;
}

.tutor-details {
    flex: 1;
    margin-bottom: 20px;
}

.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.subjects-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.subject-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.more-subjects {
    color: #6c757d;
    font-size: 12px;
    font-style: italic;
}

.tutor-bio {
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
    margin: 0;
}

.experience-badge, .location-info, .jobs-badge {
    font-size: 13px;
    color: #495057;
    display: flex;
    align-items: center;
}

.jobs-badge {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 12px;
}

.location-info {
    color: #28a745;
    font-weight: 500;
}

.tutor-actions, .job-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.tutor-actions .btn {
    font-size: 14px;
    padding: 8px 12px;
}

.no-results {
    background: white;
    border-radius: 15px;
    padding: 60px 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* List View Styles */
.tutors-container.list-view .row {
    flex-direction: column;
}

.tutors-container.list-view .tutor-card-wrapper {
    width: 100%;
    max-width: none;
}

.tutors-container.list-view .tutor-card {
    flex-direction: row;
    padding: 20px;
}

.tutors-container.list-view .tutor-header {
    margin-bottom: 0;
    margin-right: 20px;
}

.tutors-container.list-view .tutor-details {
    flex: 1;
    margin-bottom: 0;
    margin-right: 20px;
}

.tutors-container.list-view .tutor-actions {
    flex-direction: column;
    align-self: center;
}

/* Responsive */
@media (max-width: 768px) {
    .search-header .d-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .search-options {
        align-self: flex-end;
    }
    
    .tutor-card {
        padding: 20px;
    }
    
    .tutor-header {
        gap: 12px;
    }
    
    .avatar-img, .avatar-placeholder {
        width: 50px;
        height: 50px;
    }
    
    .tutor-name {
        font-size: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const tutorsContainer = document.getElementById('tutorsContainer');
    
    if (gridViewBtn && listViewBtn && tutorsContainer) {
        gridViewBtn.addEventListener('change', function() {
            if (this.checked) {
                tutorsContainer.classList.remove('list-view');
            }
        });
        
        listViewBtn.addEventListener('change', function() {
            if (this.checked) {
                tutorsContainer.classList.add('list-view');
            }
        });
    }
});
</script>
@endsection
