<!-- //vacancy index page -->

@extends('layouts.app')

@section('navbar')
    @include('partials.unified-navbar')
@endsection

@section('title', 'Find Teaching Vacancies')

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

/* Vacancy Cards */
.vacancies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.vacancy-card {
    /* background: url('/images/texturebg.avif') no-repeat center center; */
    background:white;
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
    border-left: 4px solid #ff6b35;
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

.schedule-info {
    margin-bottom: 15px;
}

.schedule-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 5px;
}

.schedule-badge {
    background: rgba(255, 107, 53, 0.1);
    color: #ff6b35;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.applications-info {
    margin-top: auto;
    margin-bottom: 15px;
    text-align: center;
}

.applications-count {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
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

/* Make all filter input placeholders dim gray */
.filter-input::placeholder {
    color: #888 !important;
    opacity: 1;
}

.filter-row,
.filter-row * {
    color: black !important;
    border-color: black !important;
}

.filter-input,
.filter-select {
    color: #312f2f !important;
    border: 1px solid #444040 !important;
}

.btn-filter,
.btn-clear {
    color: white !important;
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

    .vacancies-grid {
        grid-template-columns: 1fr;
        gap: 20px;
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

    .search-filters-container {
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
            <h1>Find Teaching Vacancies</h1>
            <p>Browse approved student vacancies and apply for teaching opportunities that match your expertise.</p>
        </div>
    </div>

    <!-- Search and Filters Container -->
    <div class="container">
        <div class="search-filters-container">
            <!-- Search Bar Section -->
            <div class="search-section">
                <form method="GET" action="{{ route('vacancies.search') }}">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" 
                               value="{{ request('q') }}" placeholder="Search for vacancies...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Filters Section -->
            <div class="filters-section">
                <form method="GET" action="{{ route('vacancies.index') }}">
                    <div class="filter-row">
                        <!-- Subject Filter -->
                        <div class="filter-group">
                            <label for="subject">Subject</label>
                            <select class="filter-select" id="subject" name="subject">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grade Level Filter -->
                        <div class="filter-group">
                            <label for="grade_level">Grade Level</label>
                            <select class="filter-select" id="grade_level" name="grade_level">
                                <option value="">All Grades</option>
                                @foreach($gradeLevels as $grade)
                                    <option value="{{ $grade }}" {{ request('grade_level') == $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Location Type Filter -->
                        <div class="filter-group">
                            <label for="location_type">Location Type</label>
                            <select class="filter-select" id="location_type" name="location_type">
                                <option value="">All Types</option>
                                <option value="online" {{ request('location_type') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="home" {{ request('location_type') == 'home' ? 'selected' : '' }}>Student's Home</option>
                                <option value="tutor_place" {{ request('location_type') == 'tutor_place' ? 'selected' : '' }}>Tutor's Place</option>
                                <option value="flexible" {{ request('location_type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                            </select>
                        </div>

                        <!-- Urgency Filter -->
                        <div class="filter-group">
                            <label for="urgency">Urgency</label>
                            <select class="filter-select" id="urgency" name="urgency">
                                <option value="">All Urgency</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="filter-group">
                            <button type="submit" class="btn-filter w-100">Apply Filters</button>
                        </div>

                        <div class="filter-group">
                            <a href="{{ route('vacancies.index') }}" class="btn-clear w-100 text-center text-decoration-none">Clear Filters</a>
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
            <h2 class="section-title">
                <i class="fas fa-list-ul me-2"></i>
                {{ $vacancies->total() }} Vacancies Found
            </h2>
            <div class="text-muted">
                Showing {{ $vacancies->firstItem() ?? 0 }}-{{ $vacancies->lastItem() ?? 0 }} of {{ $vacancies->total() }}
            </div>
        </div>

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

                            <!-- Location & Schedule -->
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

                            @if($vacancy->schedule_days && $vacancy->schedule_times)
                                <div class="schedule-info">
                                    <div class="schedule-badges">
                                        @foreach(array_slice($vacancy->schedule_days, 0, 3) as $day)
                                            <span class="schedule-badge">{{ $day }}</span>
                                        @endforeach
                                        @if(count($vacancy->schedule_days) > 3)
                                            <span class="schedule-badge">+{{ count($vacancy->schedule_days) - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Student Info -->
                            <div class="student-info">
                                <div class="student-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="student-name">{{ $vacancy->student->name }}</div>
                            </div>

                            <!-- Applications Count -->
                            @if($vacancy->applications_count > 0)
                                <div class="applications-info">
                                    <span class="applications-count">
                                        <i class="fas fa-users me-1"></i>{{ $vacancy->applications_count }} applications
                                    </span>
                                </div>
                            @endif
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
                <p class="empty-text">Try adjusting your filters or search criteria.</p>
                <a href="{{ route('vacancies.index') }}" class="btn-view-all">View All Vacancies</a>
            </div>
        @endif
    </div>
</div>
@endsection