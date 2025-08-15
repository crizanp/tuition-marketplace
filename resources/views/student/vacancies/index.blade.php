@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <!-- <i class="fas fa-list-alt me-2"></i> -->
            My Vacancies
        </h2>
        <a href="{{ route('student.vacancies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Post New Vacancy
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills" id="statusTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-status="all" href="#" onclick="filterVacancies('all')">
                        All Vacancies ({{ $vacancies->total() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-status="pending" href="#" onclick="filterVacancies('pending')">
                        <i class="fas fa-clock me-1"></i>
                        Pending ({{ $vacancies->where('status', 'pending')->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-status="approved" href="#" onclick="filterVacancies('approved')">
                        <i class="fas fa-check-circle me-1"></i>
                        Approved ({{ $vacancies->where('status', 'approved')->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-status="rejected" href="#" onclick="filterVacancies('rejected')">
                        <i class="fas fa-times-circle me-1"></i>
                        Rejected ({{ $vacancies->where('status', 'rejected')->count() }})
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if($vacancies->count() > 0)
        <div class="row">
            @foreach($vacancies as $vacancy)
                <div class="col-lg-6 col-xl-4 mb-4 vacancy-card" data-status="{{ $vacancy->status }}">
                    <div class="card vacancy-item shadow-sm h-100">
                        <!-- Status badge previously top-right — moved next to priority below -->

                        <div class="card-body">
                            <h5 class="card-title mb-2">{{ $vacancy->title }}</h5>
                            
                            <div class="vacancy-info mb-3">
                                <div class="info-item">
                                    <i class="fas fa-book text-primary me-2"></i>
                                    <span>{{ $vacancy->subject }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                                    <span>{{ $vacancy->grade_level }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <span>{{ $vacancy->budget_range }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    <span>{{ $vacancy->duration_hours }}h sessions</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</span>
                                </div>
                            </div>

                            <p class="card-text text-muted">
                                {{ Str::limit($vacancy->description, 100) }}
                            </p>

                            <div class="mb-2 d-flex align-items-center gap-2">
                                {{-- Status badge (left) --}}
                                @if($vacancy->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Pending Review
                                    </span>
                                @elseif($vacancy->status === 'approved')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Approved
                                    </span>
                                @elseif($vacancy->status === 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Rejected
                                    </span>
                                @endif

                                {{-- Priority/Urgency badge (right) --}}
                                @if($vacancy->urgency === 'high')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation me-1"></i>
                                        High Priority
                                    </span>
                                @elseif($vacancy->urgency === 'medium')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Medium Priority
                                    </span>
                                @endif
                            </div>

                            <!-- Schedule Info -->
                            @if($vacancy->formatted_schedule)
                                <div class="schedule-info mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $vacancy->formatted_schedule }}
                                    </small>
                                </div>
                            @endif

                            <div class="vacancy-footer">
                                <small class="text-muted">
                                    Posted {{ $vacancy->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('student.vacancies.show', $vacancy) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                                
                                @if(in_array($vacancy->status, ['pending', 'rejected']))
                                    <a href="{{ route('student.vacancies.edit', $vacancy) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i>
                                        Edit
                                    </a>
                                @endif

                                @if($vacancy->status !== 'approved')
                                    <form action="{{ route('student.vacancies.destroy', $vacancy) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this vacancy?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $vacancies->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-list-alt text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-3">No Vacancies Posted Yet</h4>
            <p class="text-muted mb-4">Start by posting your first vacancy to find the perfect tutor!</p>
            <a href="{{ route('student.vacancies.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>
                Post Your First Vacancy
            </a>
        </div>
    @endif
</div>

<style>
/* Color Variables */
:root {
    --orange: #ff6a00;
    --black: #000000;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --border-light: #e9ecef;
    --text-muted: #6c757d;
}

/* Base Styling */
body {
    background-color: var(--white);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.container {
    background-color: var(--white);
    max-width: 1230px;
    margin: 0 auto;
}

/* Header Styling */
h2 {
    color: var(--black);
    font-weight: 600;
    font-size: 1.75rem;
}

/* Primary Button */
.btn-primary {
    background-color: var(--orange);
    border-color: var(--orange);
    color: var(--white);
    font-weight: 500;
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-primary:hover,
.btn-primary:focus {
    background-color: #e55a00;
    border-color: #e55a00;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 106, 0, 0.3);
}

/* Alert Styling */
.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    border-radius: 8px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
    border-radius: 8px;
}

/* Filter Tabs Card */
.card {
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

.card-body {
    padding: 1.5rem;
}

/* Navigation Pills */
.nav-pills {
    gap: 0.5rem;
}

.nav-pills .nav-link {
    background-color: var(--white);
    color: var(--text-muted);
    border: 2px solid var(--border-light);
    border-radius: 8px;
    padding: 0.6rem 1rem;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none;
}

.nav-pills .nav-link:hover {
    background-color: #fff5f0;
    border-color: var(--orange);
    color: var(--orange);
}

.nav-pills .nav-link.active {
    background-color: var(--orange);
    border-color: var(--orange);
    color: var(--white);
}

/* Vacancy Cards */
.vacancy-card .card {
    border: 1px solid var(--border-light);
    border-radius: 12px;
    background-color: var(--white);
    transition: all 0.3s ease;
    overflow: hidden;
}

.vacancy-card .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--orange);
}

/* Card Content */
.card-title {
    color: var(--black);
    font-weight: 600;
    font-size: 1.1rem;
    line-height: 1.3;
}

.card-text {
    color: var(--text-muted);
    line-height: 1.5;
}

/* Vacancy Info Items */
.vacancy-info .info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.6rem;
    font-size: 0.9rem;
    color: var(--black);
}

.vacancy-info .info-item i {
    color: var(--orange);
    width: 18px;
    text-align: center;
}

/* Status Badges */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
}

.badge.bg-warning {
    background-color: var(--orange) !important;
    color: var(--white);
}

.badge.bg-success {
    background-color: #198754 !important;
    color: var(--white);
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    color: var(--white);
}

/* Schedule Info */
.schedule-info {
    background-color: var(--light-gray);
    border: 1px solid var(--border-light);
    border-radius: 6px;
    padding: 0.75rem;
}

.schedule-info small {
    color: var(--text-muted);
    font-weight: 500;
}

/* Card Footer */
.card-footer {
    background-color: var(--light-gray);
    border-top: 1px solid var(--border-light);
    padding: 1rem 1.5rem;
}

/* Action Buttons */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.85rem;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary {
    border-color: var(--orange);
    color: var(--orange);
    background-color: transparent;
}

.btn-outline-primary:hover {
    background-color: var(--orange);
    border-color: var(--orange);
    color: var(--white);
}

.btn-outline-secondary {
    border-color: var(--text-muted);
    color: var(--text-muted);
    background-color: transparent;
}

.btn-outline-secondary:hover {
    background-color: var(--text-muted);
    border-color: var(--text-muted);
    color: var(--white);
}

.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
    background-color: transparent;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: var(--white);
}

/* Empty State */
.text-center i {
    color: var(--text-muted);
}

.text-center h4 {
    color: var(--black);
    font-weight: 600;
}

.text-center p {
    color: var(--text-muted);
}

/* Utility Classes */
.text-muted {
    color: var(--text-muted) !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-pills {
        flex-wrap: wrap;
    }
    
    .nav-pills .nav-link {
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        padding: 0.5rem 0.8rem;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .d-flex.gap-2 {
        gap: 0.5rem !important;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 1.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
}

/* Professional Enhancements */
.card {
    position: relative;
}

.position-absolute.top-0.end-0 {
    z-index: 10;
}

.vacancy-footer small {
    color: var(--text-muted);
    font-weight: 400;
}

/* Smooth Animations */
* {
    transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
}

/* Focus States for Accessibility */
.btn:focus,
.nav-link:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 106, 0, 0.25);
    outline: none;
}

/* Clean Typography */
body {
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--black);
}

/* Professional Spacing */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mb-3 {
    margin-bottom: 1rem !important;
}

.py-5 {
    padding-top: 2rem !important;
    padding-bottom: 2rem !important;
}
</style>

<script>
function filterVacancies(status) {
    // Update active tab
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-status="${status}"]`).classList.add('active');

    // Filter vacancy cards
    document.querySelectorAll('.vacancy-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection