@extends('layouts.app')

@section('title', 'Find Teaching Vacancies')

@section('content')
<div class="container-fluid py-4">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="bg-primary text-white rounded-lg p-4 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-6 fw-bold mb-2">Find Teaching Vacancies</h1>
                        <p class="lead mb-0">Browse approved student vacancies and apply for teaching opportunities that match your expertise.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <i class="fas fa-graduation-cap fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search vacancies...">
                        </div>
                        <div class="col-md-2">
                            <label for="subject" class="form-label">Subject</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select class="form-select" id="grade_level" name="grade_level">
                                <option value="">All Grades</option>
                                @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="location_type" class="form-label">Location</label>
                            <select class="form-select" id="location_type" name="location_type">
                                <option value="">All Types</option>
                                <option value="online" {{ request('location_type') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="home" {{ request('location_type') == 'home' ? 'selected' : '' }}>Student's Home</option>
                                <option value="tutor_place" {{ request('location_type') == 'tutor_place' ? 'selected' : '' }}>Tutor's Place</option>
                                <option value="flexible" {{ request('location_type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="urgency" class="form-label">Urgency</label>
                            <select class="form-select" id="urgency" name="urgency">
                                <option value="">All Urgency</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list-ul me-2"></i>
                    {{ $vacancies->total() }} Vacancies Found
                </h5>
                <div class="text-muted">
                    Showing {{ $vacancies->firstItem() }}-{{ $vacancies->lastItem() }} of {{ $vacancies->total() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Vacancy Cards -->
    <div class="row">
        @forelse($vacancies as $vacancy)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0 vacancy-card">
                    <div class="card-header bg-light border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="badge bg-{{ $vacancy->urgency === 'high' ? 'danger' : ($vacancy->urgency === 'medium' ? 'warning' : 'success') }}">
                                {{ ucfirst($vacancy->urgency) }} Priority
                            </span>
                            <small class="text-muted">{{ $vacancy->approved_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-2">{{ $vacancy->title }}</h5>
                        
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <i class="fas fa-book text-primary me-1"></i>
                                    <small class="text-muted">{{ $vacancy->subject }}</small>
                                </div>
                                <div class="col-6">
                                    <i class="fas fa-graduation-cap text-primary me-1"></i>
                                    <small class="text-muted">{{ $vacancy->grade_level }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <p class="card-text text-muted small">
                            {{ Str::limit($vacancy->description, 100) }}
                        </p>
                        
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <i class="fas fa-rupee-sign text-success me-1"></i>
                                    <span class="fw-bold text-success">Rs. {{ number_format($vacancy->budget_min) }} - Rs. {{ number_format($vacancy->budget_max) }}</span>
                                </div>
                                <div class="col-12 mb-2">
                                    <i class="fas fa-clock text-info me-1"></i>
                                    <small class="text-muted">{{ $vacancy->duration_hours }} hours/session</small>
                                </div>
                                <div class="col-12">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($vacancy->schedule_days && $vacancy->schedule_times)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ implode(', ', array_slice($vacancy->schedule_days, 0, 2)) }}{{ count($vacancy->schedule_days) > 2 ? '...' : '' }}
                                </small>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-user me-1"></i>
                                {{ $vacancy->student->name }}
                            </span>
                            @if($vacancy->applications_count > 0)
                                <span class="badge bg-info">
                                    {{ $vacancy->applications_count }} applications
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('vacancies.show', $vacancy->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye me-2"></i>View Details & Apply
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No vacancies found</h4>
                    <p class="text-muted">Try adjusting your search criteria or check back later for new opportunities.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($vacancies->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $vacancies->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.vacancy-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.vacancy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.card-header {
    background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)) !important;
}

.badge {
    font-size: 0.75em;
}

.text-primary {
    color: #667eea !important;
}
</style>
@endsection
