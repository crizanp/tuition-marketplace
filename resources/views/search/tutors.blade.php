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
    background: #fcccb4;
    color: #090909;
    padding: 60px 0;
    margin-bottom: 40px;
}

.page-header h1 {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Search and Filters Container */
.search-filters-container {
    background: white;
    border-radius: 20px;
    padding: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: -60px auto 40px;
    position: relative;
    z-index: 10;
    max-width: 1200px;
}

.filters-section {
    padding-top: 25px;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 14px;
}

.filter-input, .filter-select {
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.btn-filter {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 24px;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

.btn-clear {
    background: transparent;
    border: 2px solid #6c757d;
    color: #6c757d;
    padding: 10px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 24px;
}

.btn-clear:hover {
    background: #6c757d;
    color: white;
}

/* Main Content */
.content-section {
    max-width: 1200px;
    margin: 0 auto;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0;
}

.sort-dropdown {
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    background: white;
    font-size: 14px;
    min-width: 200px;
    appearance: none;
    background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns%3D%22http%3A//www.w3.org/2000/svg%22 viewBox%3D%220 0 4 5%22%3E%3Cpath fill%3D%22%23000%22 d%3D%22M2 0L0 2h4zM0 3l2 2 2-2z%22/%3E%3C/svg%3E');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 10px;
}

/* Job Cards - Updated for Equal Heights */
.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.job-card {
    background: url('/images/texturebg.avif') no-repeat center center;
    background-size: cover;
    background-color: rgb(255 255 255 / 98%);
    background-blend-mode: overlay;
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.job-card-body {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.job-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0;
    line-height: 1.3;
    flex: 1;
    min-height: 2.6rem;
}

.featured-badge {
    background: linear-gradient(135deg, #ffd700, #ffb347);
    color: #2c3e50;
    font-weight: 600;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 10px;
    flex-shrink: 0;
}

.tutor-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f8f9fa;
    min-height: 52px;
}

.tutor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    flex-shrink: 0;
}

.tutor-placeholder {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tutor-name {
    font-weight: 600;
    color: #495057;
    margin-left: 12px;
    font-size: 14px;
    padding-right: 5px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.tutor-name:hover {
    color: #ff6b35;
    text-decoration: none;
}

.verified-icon {
    color: #000000ff;
    margin-left: 8px;
    flex-shrink: 0;
}

.subjects-container {
    margin-bottom: 15px;
    min-height: 32px;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
}

.subject-badge {
    background: rgba(255, 107, 53, 0.1);
    color: #ff6b35;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-right: 8px;
    margin-bottom: 8px;
    display: inline-block;
}

.more-subjects {
    background: #f8f9fa;
    color: #6c757d;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.job-description {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 20px;
    flex: 1;
    min-height: 3rem;
}

.job-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    margin-top: auto;
}

.location-info {
    color: #6c757d;
    font-size: 13px;
}

.location-info i {
    color: #ff6b35;
    margin-right: 5px;
}

.hourly-rate {
    font-size: 1.2rem;
    font-weight: 700;
    color: #000000ff;
}

.teaching-mode {
    color: #ff6b35;
    font-size: 13px;
    font-weight: 600;
}

.teaching-mode i {
    margin-right: 5px;
}

.job-footer {
    background: #f8f9fa;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    flex-shrink: 0;
}

.views-count {
    color: #6c757d;
    font-size: 13px;
}

.views-count i {
    color: #ff6b35;
    margin-right: 5px;
}

.btn-view-details {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border: none;
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-details:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #e9ecef;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 10px;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 30px;
}

.btn-view-all {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border: none;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin: 40px 0;
}

/* Search filters specific styles */
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

.search-header {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    margin-bottom: 30px;
}

.search-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
}

.search-options {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 40px 0;
        text-align: center;
    }

    .page-header h1 {
        font-size: 2rem;
    }

    .search-filters-container {
        margin: -40px 20px 30px;
        padding: 20px;
    }

    .search-header {
        margin: 15px 20px 25px;
        padding: 20px;
    }

    .search-header .d-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .search-options {
        align-self: flex-end;
    }

    .filter-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .section-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .job-meta {
        flex-direction: column;
        gap: 10px;
        align-items: start;
    }

    .job-footer {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .content-section {
        padding: 0 15px;
    }

    .search-filters-container {
        margin: -40px 15px 25px;
        padding: 15px;
    }

    .job-card-body {
        padding: 20px;
    }

    .job-footer {
        padding: 15px 20px;
    }
}
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h1>Search Tutors</h1>
            <p>Find expert tutors matching your criteria and connect with the perfect learning partner.</p>
        </div>
    </div>

    <!-- Search and Filters Container -->
    <div class="container">
        <div class="search-filters-container">
            <!-- Search Bar Section -->
            <div class="search-section">
                @include('components.search-bar', [
                    'action' => route('search.tutors'),
                    'placeholder' => 'Search for tutors, subjects, or skills...',
                    'size' => 'normal'
                ])
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
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
            <div class="jobs-grid" id="jobsContainer">
                @foreach($jobs as $job)
                    <div class="job-card">
                        <div class="job-card-body">
                            <div class="job-header">
                                <h6 class="job-title">{{ Str::limit($job->title, 50) }}</h6>
                                @if($job->tutor->status === 'active')
                                    <span class="featured-badge">Active</span>
                                @endif
                            </div>
                            
                            <!-- Tutor Info -->
                            <div class="tutor-info">
                                @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                    <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                         class="tutor-avatar"
                                         alt="{{ $job->tutor->name }}">
                                @else
                                    <div class="tutor-placeholder">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="tutor-name">{{ $job->tutor->name }}</a>
                                @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                    <i class="fas fa-check-circle verified-icon" title="Verified Tutor"></i>
                                @endif
                                
                                <!-- Rating -->
                                @if($job->tutor->profile && $job->tutor->profile->rating)
                                    <div class="tutor-rating ms-2">
                                        
                                        <span class="rating-text my-auto" style="font-size: 12px; color: #6c757d; margin-left: 5px;">({{ number_format($job->tutor->profile->rating ?? 4, 1) }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Subjects -->
                            @if($job->subjects && count($job->subjects) > 0)
                                <div class="subjects-container">
                                    @foreach(array_slice($job->subjects, 0, 2) as $subject)
                                        <span class="subject-badge">{{ $subject }}</span>
                                    @endforeach
                                    @if(count($job->subjects) > 2)
                                        <span class="more-subjects">+{{ count($job->subjects) - 2 }}</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Description -->
                            @if($job->description)
                                <p class="job-description">{{ Str::limit($job->description, 100) }}</p>
                            @endif

                            <!-- Location & Rate -->
                            <div class="job-meta">
                                @php
                                    $location_parts = array_filter([
                                        $job->place, 
                                        $job->district, 
                                        $job->state
                                    ]);
                                @endphp
                                @if(count($location_parts) > 0)
                                    <span class="location-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ implode(', ', $location_parts) }}
                                    </span>
                                @endif
                                
                                @if($job->hourly_rate)
                                    <span class="hourly-rate">Rs.{{ number_format((float)$job->hourly_rate, 2) }}/hr</span>
                                @endif
                            </div>

                            <!-- Teaching Mode & Student Level -->
                            <div class="d-flex gap-2 mb-3">
                                @if($job->teaching_mode)
                                    <span class="teaching-mode">
                                        <i class="fas fa-{{ $job->teaching_mode === 'online' ? 'laptop' : 'home' }}"></i>
                                        {{ ucfirst($job->teaching_mode) }}
                                    </span>
                                @endif
                                
                                @if($job->student_level)
                                    <span class="teaching-mode">
                                        <i class="fas fa-graduation-cap"></i>
                                        {{ $job->student_level }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="job-footer p-0" style="display:flex;">
                            <a href="/jobs/{{ Str::slug($job->tutor->name) }}/{{ $job->id }}" class="btn-view-details" style="background:#000;border-radius:0;color:#fff;flex:1;text-align:center;padding:12px 0;">Detail</a>
                            <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="btn-view-details" style="background:#000;border-radius:0;color:#fff;flex:1;text-align:center;padding:12px 0;">Profile</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                {{ $jobs->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-search empty-icon"></i>
                <h5 class="empty-title">No tutors found</h5>
                <p class="empty-text">Try adjusting your search criteria or browse all available tutors.</p>
                <a href="{{ route('search.tutors') }}" class="btn-view-all">Clear Filters</a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    const jobsContainer = document.getElementById('jobsContainer');
    
    if (gridViewBtn && listViewBtn && jobsContainer) {
        gridViewBtn.addEventListener('change', function() {
            if (this.checked) {
                jobsContainer.classList.remove('list-view');
            }
        });
        
        listViewBtn.addEventListener('change', function() {
            if (this.checked) {
                jobsContainer.classList.add('list-view');
            }
        });
    }
});
</script>
@endsection