<!-- //vacancy search page -->

@extends('layouts.app')

@section('navbar')
    @include('partials.unified-navbar')
@endsection

@section('title', 'Search Vacancies')

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

/* Search Container */
.search-container {
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: -60px auto 40px;
    position: relative;
    z-index: 10;
    max-width: 1200px;
}

.search-input {
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    font-size: 16px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    width: 100%;
}

.search-input:focus {
    outline: none;
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.search-btn {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border: none;
    color: white;
    padding: 15px 30px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

/* Main Content */
.content-section {
    max-width: 1200px;
    margin: 0 auto;
}

.search-results-header {
    background: white;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.results-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.search-query {
    color: #ff6b35;
    font-weight: 600;
}

.results-count {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-top: 15px;
}

.count-info {
    font-size: 1.1rem;
    color: #495057;
}

.pagination-info {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Vacancy Cards */
.vacancies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.vacancy-card {
    background: url('/images/texturebg.avif') no-repeat center center;
    background-size: cover;
    background-color: rgb(255 255 255 / 95%);
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

.vacancy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}

.vacancy-card-body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.vacancy-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.vacancy-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0;
    line-height: 1.3;
    flex: 1;
    min-height: 2.6rem;
}

.urgency-badge {
    font-weight: 600;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 10px;
    flex-shrink: 0;
}

.urgency-high {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.urgency-medium {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #212529;
}

.urgency-low {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
}

.vacancy-info {
    margin-bottom: 15px;
}

.info-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 13px;
}

.info-item i {
    color: #ff6b35;
    margin-right: 8px;
    width: 16px;
    text-align: center;
}

.info-item strong {
    color: #495057;
}

.vacancy-description {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 15px;
    flex: 1;
    min-height: 3rem;
}

.student-info {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 15px;
}

.student-avatar {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: white;
}

.student-name {
    font-weight: 600;
    color: #495057;
    margin-left: 12px;
    font-size: 14px;
}

.budget-info {
    background: rgba(40, 167, 69, 0.1);
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 15px;
    text-align: center;
}

.budget-amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 2px;
}

.budget-label {
    font-size: 12px;
    color: #6c757d;
}

.vacancy-footer {
    background: #f8f9fa;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    flex-shrink: 0;
}

.posted-time {
    color: #6c757d;
    font-size: 13px;
}

.posted-time i {
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

.btn-back {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    border: none;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-right: 15px;
}

.btn-back:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
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

    .search-container {
        margin: -40px 20px 30px;
        padding: 20px;
    }

    .search-results-header {
        margin: 0 20px 30px;
        padding: 20px;
    }

    .results-count {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }

    .vacancies-grid {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 0 20px;
    }

    .info-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .content-section {
        padding: 0 15px;
    }

    .search-container {
        margin: -40px 15px 25px;
        padding: 15px;
    }

    .vacancy-card-body {
        padding: 15px;
    }

    .vacancy-footer {
        padding: 12px 15px;
    }
}
</style>

<div class="">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h1>
                <i class="fas fa-search me-2"></i>
                Search Results for Vacancies
            </h1>
            @if(request('q'))
                <p>Showing results for: <strong>"{{ request('q') }}"</strong></p>
            @else
                <p>Enter a search term to find teaching vacancies that match your expertise.</p>
            @endif
        </div>
    </div>

    <!-- Search Container -->
    <div class="container">
        <div class="search-container">
            <form method="GET" action="{{ route('vacancies.search') }}">
                <div class="row">
                    <div class="col-md-9 mb-3 mb-md-0">
                        <input type="text" class="search-input" name="q" 
                               value="{{ request('q') }}" placeholder="Search for vacancies by title, subject, or description..."
                               autofocus>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="search-btn w-100">
                            <i class="fas fa-search me-2"></i>Search Vacancies
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <!-- Search Results Header -->
        @if(request('q'))
            <div class="search-results-header">
                <h2 class="results-title">Search Results</h2>
                <p class="mb-0">Results for: <span class="search-query">"{{ request('q') }}"</span></p>
                
                <div class="results-count">
                    <span class="count-info">
                        <strong>{{ $vacancies->total() }}</strong> vacancies found
                    </span>
                    @if($vacancies->hasPages())
                        <span class="pagination-info">
                            Showing {{ $vacancies->firstItem() ?? 0 }}-{{ $vacancies->lastItem() ?? 0 }} of {{ $vacancies->total() }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Vacancies Grid -->
        @if($vacancies->count() > 0)
            <div class="vacancies-grid">
                @foreach($vacancies as $vacancy)
                    <div class="vacancy-card">
                        <div class="vacancy-card-body">
                            <div class="vacancy-header">
                                <h6 class="vacancy-title">{{ Str::limit($vacancy->title, 60) }}</h6>
                                <span class="urgency-badge urgency-{{ $vacancy->urgency }}">
                                    {{ ucfirst($vacancy->urgency) }} Priority
                                </span>
                            </div>
                            
                            <!-- Subject and Grade Info -->
                            <div class="info-row">
                                <div class="info-item">
                                    <i class="fas fa-book"></i>
                                    <strong>{{ $vacancy->subject }}</strong>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <strong>{{ $vacancy->grade_level }}</strong>
                                </div>
                            </div>

                            <!-- Budget Info -->
                            <div class="budget-info">
                                <div class="budget-amount">Rs. {{ number_format($vacancy->budget_min) }} - Rs. {{ number_format($vacancy->budget_max) }}</div>
                                <div class="budget-label">Per session ({{ $vacancy->duration_hours }} hours)</div>
                            </div>

                            <!-- Description -->
                            <p class="vacancy-description">{{ Str::limit($vacancy->description, 120) }}</p>

                            <!-- Location & Duration -->
                            <div class="info-row">
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $vacancy->duration_hours }}h/session</span>
                                </div>
                            </div>

                            <!-- Student Info -->
                            <div class="student-info">
                                <div class="student-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="student-name">{{ $vacancy->student->name }}</div>
                            </div>
                        </div>
                        
                        <div class="vacancy-footer">
                            <span class="posted-time">
                                <i class="fas fa-clock"></i>{{ $vacancy->approved_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('vacancies.show', $vacancy->id) }}" class="btn-view-details">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($vacancies->hasPages())
                <div class="pagination-container">
                    {{ $vacancies->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-search empty-icon"></i>
                <h5 class="empty-title">No vacancies found</h5>
                @if(request('q'))
                    <p class="empty-text">
                        No vacancies match your search criteria.<br>
                        Try different keywords or browse all available vacancies.
                    </p>
                    <div>
                        <a href="{{ route('vacancies.search') }}" class="btn-back">
                            <i class="fas fa-search me-2"></i>New Search
                        </a>
                        <a href="{{ route('vacancies.index') }}" class="btn-view-all">
                            <i class="fas fa-list me-2"></i>Browse All Vacancies
                        </a>
                    </div>
                @else
                    <p class="empty-text">
                        Enter a search term above to find teaching vacancies.
                    </p>
                    <a href="{{ route('vacancies.index') }}" class="btn-view-all">
                        <i class="fas fa-list me-2"></i>Browse All Vacancies
                    </a>
                @endif
            </div>
        @endif

        <!-- Back to All Vacancies -->
        @if(request('q') && $vacancies->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('vacancies.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to All Vacancies
                </a>
            </div>
        @endif
    </div>
</div>
@endsection