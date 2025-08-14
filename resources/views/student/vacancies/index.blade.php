@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-list-alt me-2"></i>
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
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 p-3">
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
                        </div>

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

                            @if($vacancy->urgency === 'high')
                                <div class="mb-2">
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation me-1"></i>
                                        High Priority
                                    </span>
                                </div>
                            @elseif($vacancy->urgency === 'medium')
                                <div class="mb-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Medium Priority
                                    </span>
                                </div>
                            @endif

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
.vacancy-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
}

.vacancy-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.vacancy-info .info-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 14px;
}

.schedule-info {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
}

.nav-pills .nav-link {
    color: #6c757d;
    background: none;
    border: 1px solid #dee2e6;
    margin-right: 10px;
    margin-bottom: 5px;
}

.nav-pills .nav-link.active {
    background: #3498db;
    border-color: #3498db;
    color: white;
}

.nav-pills .nav-link:hover:not(.active) {
    background: #f8f9fa;
    border-color: #3498db;
    color: #3498db;
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
