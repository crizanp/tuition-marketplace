<!-- this is index page -->

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
;
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
    padding: 30px;
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
    gap: 20px; /* Increased gap for better spacing */
    margin-bottom: 40px;
}

.job-card {
    background: url('/images/texturebg.avif') no-repeat center center;
    background-size: cover;
    background-color: rgba(255, 255, 255, 0.9); /* White overlay with low opacity */
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
    flex: 1; /* This makes the body take up available space */
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
    min-height: 2.6rem; /* Ensures consistent title height for 2 lines */
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
    flex-shrink: 0; /* Prevents badge from shrinking */
}

.tutor-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f8f9fa;
    min-height: 52px; /* Consistent height for tutor info */
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
}

.verified-icon {
    color: #000000ff;
    margin-left: 8px;
    flex-shrink: 0;
}

.subjects-container {
    margin-bottom: 15px;
    min-height: 32px; /* Consistent height for subjects */
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
    flex: 1; /* This pushes the footer to the bottom */
    min-height: 3rem; /* Ensures minimum space for description */
}

.job-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    margin-top: auto; /* This pushes meta info towards the bottom */
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
    margin-top: auto; /* Ensures footer stays at bottom */
    flex-shrink: 0; /* Prevents footer from shrinking */
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
            <h1>Find Your Perfect Tutors</h1>
            <p>Browse expertise tutors like you and connect with students who are looking for your skills.</p>
        </div>
    </div>

    <!-- Search and Filters Container -->
    <div class="container">
        <div class="search-filters-container">
            <!-- Filters Section -->
            <div class="filters-section">
                <form method="GET" action="{{ route('jobs.index') }}">
                    <div class="filter-row">
                        <!-- Subject Filter -->
                        <div class="filter-group">
                            <label for="subject">Subject</label>
                            <select class="filter-select" id="subject" name="subject">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}" {{ request('subject') === $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State Filter -->
                        <div class="filter-group">
                            <label for="state">State</label>
                            <input type="text" class="filter-input" id="state" name="state" 
                                   value="{{ request('state') }}" placeholder="Bagmati Province">
                        </div>

                        <!-- Rate Range -->
                        <div class="filter-group">
                            <label>Min Rate (NPR)</label>
                            <input type="number" class="filter-input" name="min_rate" 
                                   value="{{ request('min_rate') }}" placeholder="Min" min="0" step="0.01">
                        </div>

                        <div class="filter-group">
                            <label>Max Rate (NPR)</label>
                            <input type="number" class="filter-input" name="max_rate" 
                                   value="{{ request('max_rate') }}" placeholder="Max" min="0" step="0.01">
                        </div>

                        <!-- Gender Preference -->
                        <div class="filter-group">
                            <label for="gender_preference">Gender Preference</label>
                            <select class="filter-select" id="gender_preference" name="gender_preference">
                                <option value="">Any Gender</option>
                                <option value="male" {{ request('gender_preference') === 'male' ? 'selected' : '' }}>Male Students</option>
                                <option value="female" {{ request('gender_preference') === 'female' ? 'selected' : '' }}>Female Students</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-group">
                            <button type="submit" class="btn-filter w-100">Apply Filters</button>
                        </div>

                        <div class="filter-group">
                            <a href="{{ route('jobs.index') }}" class="btn-clear w-100 text-center text-decoration-none">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <!-- Section Header -->
       <div class="section-header">
    <!-- Main Sort Dropdown -->
    <select class="sort-dropdown" onchange="changeSorting(this.value, 'sort')">
        <option value="" {{ !request('sort') && !request('views_sort') ? 'selected' : '' }}>
            Sort By
        </option>
        <option value="created_at-desc" {{ request('sort') === 'created_at' && request('order') === 'desc' ? 'selected' : '' }}>
            Newest First
        </option>
        <option value="created_at-asc" {{ request('sort') === 'created_at' && request('order') === 'asc' ? 'selected' : '' }}>
            Oldest First
        </option>
        <option value="hourly_rate-asc" {{ request('sort') === 'hourly_rate' && request('order') === 'asc' ? 'selected' : '' }}>
            Price: Low to High
        </option>
        <option value="hourly_rate-desc" {{ request('sort') === 'hourly_rate' && request('order') === 'desc' ? 'selected' : '' }}>
            Price: High to Low
        </option>
        <option value="views-desc" {{ request('sort') === 'views' && request('order') === 'desc' ? 'selected' : '' }}>
            Most Popular
        </option>
    </select>

    <!-- Verification Status Filter -->
    <select class="sort-dropdown" onchange="changeSorting(this.value, 'verification_status')" style="margin-left: 20px;">
        <option value="">All Tutors</option>
        <option value="verified" {{ request('verification_status') === 'verified' ? 'selected' : '' }}>Verified Only</option>
        <option value="non_verified" {{ request('verification_status') === 'non_verified' ? 'selected' : '' }}>Non-Verified Only</option>
    </select>

    <!-- Views Sort Filter -->
    <select class="sort-dropdown" onchange="changeSorting(this.value, 'views_sort')" style="margin-left: 20px;">
        <option value="">View Count</option>
        <option value="highest" {{ request('views_sort') === 'highest' ? 'selected' : '' }}>Highest Views First</option>
        <option value="lowest" {{ request('views_sort') === 'lowest' ? 'selected' : '' }}>Lowest Views First</option>
    </select>

    <span style="margin-left: 20px; background: white; color: black; padding: 10px 15px; border-radius: 5px;">
        Not find your desired tutor? <a href="{{ url('/student/vacancies/create') }}">Apply for vacancy</a>
    </span>
</div>

        <!-- Jobs Grid -->
        @if($jobs->count() > 0)
            <div class="jobs-grid">
                @foreach($jobs as $job)
                    <div class="job-card">
                        <div class="job-card-body">
                            <div class="job-header">
                                <h6 class="job-title">{{ Str::limit($job->title, 50) }}</h6>
                                @if($job->is_featured)
                                    <span class="featured-badge">Featured</span>
                                @endif
                            </div>
                            
                            <!-- Tutor Info -->
                            <div class="tutor-info">
                                @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                    <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                         class="tutor-avatar"
                                         alt="Tutor">
                                @else
                                    <div class="tutor-placeholder">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <span class="tutor-name">{{ $job->tutor->name }}</span>
                                @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                    <i class="fas fa-check-circle verified-icon"></i>
                                @endif
                            </div>

                            <!-- Subjects -->
                            <div class="subjects-container">
                                @foreach(array_slice($job->subjects, 0, 2) as $subject)
                                    <span class="subject-badge">{{ $subject }}</span>
                                @endforeach
                                @if(count($job->subjects) > 2)
                                    <span class="more-subjects">+{{ count($job->subjects) - 2 }}</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="job-description">{{ Str::limit($job->description, 100) }}</p>

                            <!-- Location & Rate -->
                            <div class="job-meta">
                                <span class="location-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $job->place }}, {{ $job->district }}
                                </span>
                                <span class="hourly-rate">Rs.{{ number_format((float)$job->hourly_rate, 2) }}/hr</span>
                            </div>

                            <!-- Teaching Mode
                            <div class="teaching-mode">
                                <i class="fas fa-laptop"></i>
                                {{ $job->teaching_mode_label }}
                            </div> -->
                        </div>
                        
                        <div class="job-footer">
                            <span class="views-count">
                                <i class="fas fa-eye"></i>{{ $job->views }} views
                            </span>
                            <a href="{{ route('jobs.show', $job) }}" class="btn-view-details">View Details</a>
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
                <h5 class="empty-title">No jobs found</h5>
                <p class="empty-text">Try adjusting your filters or search criteria.</p>
                <a href="{{ route('jobs.index') }}" class="btn-view-all">View All Jobs</a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeSorting(value, paramName) {
    console.log('changeSorting called with:', value, paramName);
    
    try {
        const url = new URL(window.location.href);

        // Handle different parameter types
        if (paramName === 'sort') {
            // Clear existing sort and order parameters
            url.searchParams.delete('sort');
            url.searchParams.delete('order');
            
            if (value && value !== '') {
                const parts = value.split('-');
                if (parts.length === 2) {
                    const [sort, order] = parts;
                    url.searchParams.set('sort', sort);
                    url.searchParams.set('order', order);
                }
            }
            
            // Clear views_sort when using main sort
            url.searchParams.delete('views_sort');
            
        } else if (paramName === 'verification_status') {
            if (value && value !== '') {
                url.searchParams.set('verification_status', value);
            } else {
                url.searchParams.delete('verification_status');
            }
            
        } else if (paramName === 'views_sort') {
            if (value && value !== '') {
                url.searchParams.set('views_sort', value);
                // Clear main sort parameters when using views sort
                url.searchParams.delete('sort');
                url.searchParams.delete('order');
            } else {
                url.searchParams.delete('views_sort');
            }
        }

        // Reset to first page when filters change
        url.searchParams.delete('page');
        
        // Redirect to the updated URL
        window.location.href = url.toString();
        
    } catch (error) {
        console.error('Error in changeSorting:', error);
        // Fallback: reload page with basic parameters
        if (paramName === 'sort' && value) {
            const [sort, order] = value.split('-');
            window.location.href = `${window.location.pathname}?sort=${sort}&order=${order}`;
        } else if (paramName === 'verification_status' && value) {
            window.location.href = `${window.location.pathname}?verification_status=${value}`;
        } else if (paramName === 'views_sort' && value) {
            window.location.href = `${window.location.pathname}?views_sort=${value}`;
        }
    }
}

// Function to preserve current filters when applying new ones
function preserveFilters() {
    const urlParams = new URLSearchParams(window.location.search);
    const form = document.querySelector('form[action="{{ route('jobs.index') }}"]');
    
    if (form) {
        // Add hidden inputs for sort parameters if they exist
        ['sort', 'order', 'verification_status', 'views_sort'].forEach(param => {
            if (urlParams.has(param)) {
                // Remove existing hidden input if it exists
                const existingInput = form.querySelector(`input[name="${param}"]`);
                if (existingInput) {
                    existingInput.remove();
                }
                
                // Add new hidden input
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = param;
                input.value = urlParams.get(param);
                form.appendChild(input);
            }
        });
    }
}

// Initialize filter preservation when page loads
document.addEventListener('DOMContentLoaded', function() {
    preserveFilters();
    console.log('Filters loaded and preserved');
});
</script>
@endpush