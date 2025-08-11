@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <!-- Filters Sidebar -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Filter Jobs</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('jobs.index') }}">
                            <!-- Subject Filter -->
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <select class="form-select" id="subject" name="subject">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject }}" {{ request('subject') === $subject ? 'selected' : '' }}>
                                            {{ $subject }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Teaching Mode Filter -->
                            <div class="mb-3">
                                <label for="teaching_mode" class="form-label">Teaching Mode</label>
                                <select class="form-select" id="teaching_mode" name="teaching_mode">
                                    <option value="">All Modes</option>
                                    <option value="home" {{ request('teaching_mode') === 'home' ? 'selected' : '' }}>Home Tuition</option>
                                    <option value="online" {{ request('teaching_mode') === 'online' ? 'selected' : '' }}>Online Classes</option>
                                    <option value="institute" {{ request('teaching_mode') === 'institute' ? 'selected' : '' }}>Institute Classes</option>
                                    <option value="any" {{ request('teaching_mode') === 'any' ? 'selected' : '' }}>Flexible Location</option>
                                </select>
                            </div>

                            <!-- Location Filter -->
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="{{ request('country') }}" placeholder="Nepal">
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="{{ request('state') }}" placeholder="Bagmati Province">
                            </div>

                            <!-- Rate Range -->
                            <div class="mb-3">
                                <label class="form-label">Hourly Rate (USD)</label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="min_rate" 
                                               value="{{ request('min_rate') }}" placeholder="Min" min="0" step="0.01">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="max_rate" 
                                               value="{{ request('max_rate') }}" placeholder="Max" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <!-- Gender Preference -->
                            <div class="mb-3">
                                <label for="gender_preference" class="form-label">Gender Preference</label>
                                <select class="form-select" id="gender_preference" name="gender_preference">
                                    <option value="">Any Gender</option>
                                    <option value="male" {{ request('gender_preference') === 'male' ? 'selected' : '' }}>Male Students</option>
                                    <option value="female" {{ request('gender_preference') === 'female' ? 'selected' : '' }}>Female Students</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Search and Sort -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Available Tutoring Jobs</h3>
                    <div class="d-flex gap-2">
                        <select class="form-select" style="width: auto;" onchange="changeSorting(this.value)">
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
                    </div>
                </div>

                <!-- Jobs Grid -->
                @if($jobs->count() > 0)
                    <div class="row">
                        @foreach($jobs as $job)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ Str::limit($job->title, 50) }}</h6>
                                            @if($job->is_featured)
                                                <span class="badge bg-warning">Featured</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Tutor Info -->
                                        <div class="d-flex align-items-center mb-2">
                                            @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                                <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 30px; height: 30px; object-fit: cover;"
                                                     alt="Tutor">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width: 30px; height: 30px;">
                                                    <i class="fas fa-user text-white fa-sm"></i>
                                                </div>
                                            @endif
                                            <small class="text-muted">{{ $job->tutor->name }}</small>
                                            @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                                <i class="fas fa-check-circle text-success ms-1" title="Verified"></i>
                                            @endif
                                        </div>

                                        <!-- Subjects -->
                                        <div class="mb-2">
                                            @foreach(array_slice($job->subjects, 0, 2) as $subject)
                                                <span class="badge bg-secondary me-1">{{ $subject }}</span>
                                            @endforeach
                                            @if(count($job->subjects) > 2)
                                                <span class="badge bg-light text-dark">+{{ count($job->subjects) - 2 }}</span>
                                            @endif
                                        </div>

                                        <!-- Description -->
                                        <p class="card-text text-muted small">{{ Str::limit($job->description, 100) }}</p>

                                        <!-- Location & Rate -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $job->place }}, {{ $job->district }}
                                            </small>
                                            <span class="text-success fw-bold">${{ number_format((float)$job->hourly_rate, 2) }}/hr</span>
                                        </div>

                                        <!-- Teaching Mode -->
                                        <div class="mt-2">
                                            <small class="text-primary">
                                                <i class="fas fa-laptop me-1"></i>
                                                {{ $job->teaching_mode_label }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i>{{ $job->views }} views
                                            </small>
                                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $jobs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No jobs found</h5>
                        <p class="text-muted">Try adjusting your filters or search criteria.</p>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary">View All Jobs</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeSorting(value) {
    const [sort, order] = value.split('-');
    const url = new URL(window.location);
    url.searchParams.set('sort', sort);
    url.searchParams.set('order', order);
    window.location.href = url.toString();
}
</script>
@endpush
