@extends('layouts.app')

@section('title', 'Search Vacancies')

@section('content')
<div class="container py-4">
    <!-- Search Results Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">
                <i class="fas fa-search text-primary me-2"></i>
                Search Results for Vacancies
            </h2>
            @if(request('q'))
                <p class="text-muted">
                    Showing results for: <strong>"{{ request('q') }}"</strong>
                </p>
            @endif
        </div>
    </div>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('vacancies.search') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-lg" name="q" 
                                       value="{{ request('q') }}" placeholder="Search for vacancies..."
                                       autofocus>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>Search Vacancies
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    {{ $vacancies->total() }} Results Found
                </h5>
                @if($vacancies->hasPages())
                    <div class="text-muted">
                        Showing {{ $vacancies->firstItem() }}-{{ $vacancies->lastItem() }} of {{ $vacancies->total() }}
                    </div>
                @endif
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
                        
                        <div class="mb-3">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-user me-1"></i>
                                {{ $vacancy->student->name }}
                            </span>
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
                    @if(request('q'))
                        <p class="text-muted">
                            No vacancies match your search criteria. Try different keywords or 
                            <a href="{{ route('vacancies.index') }}">browse all vacancies</a>.
                        </p>
                    @else
                        <p class="text-muted">
                            Enter a search term to find vacancies.
                        </p>
                    @endif
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

    <!-- Back to All Vacancies -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('vacancies.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to All Vacancies
            </a>
        </div>
    </div>
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
