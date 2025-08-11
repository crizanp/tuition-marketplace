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
            'action' => route('search.vacancies'),
            'placeholder' => 'Search for student vacancies...',
            'size' => 'normal'
        ])
    </div>

    <!-- Search Results Header -->
    <div class="search-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">
                    <i class="fas fa-search me-2"></i>
                    Student Vacancies
                </h2>
                <p class="text-muted mb-0">
                    Found {{ $vacancies->total() }} student vacancies
                    @if($subject || $location || $grade || $keyword)
                        matching your criteria
                    @endif
                </p>
                
                @if($subject || $location || $grade || $keyword)
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
                        @if($grade)
                            <span class="badge bg-warning me-2">
                                <i class="fas fa-graduation-cap me-1"></i>
                                {{ $grade }}
                                <a href="{{ request()->fullUrlWithQuery(['grade' => null]) }}" class="text-white ms-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($vacancies->count() > 0)
        <!-- Vacancies List -->
        <div class="vacancies-container">
            <div class="row">
                @foreach($vacancies as $vacancy)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="vacancy-card h-100">
                            <!-- Vacancy Header -->
                            <div class="vacancy-header">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="vacancy-title-section">
                                        <h5 class="vacancy-title">{{ $vacancy->title }}</h5>
                                        <div class="student-info">
                                            <i class="fas fa-user-graduate me-1"></i>
                                            <span>{{ $vacancy->student->name }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($vacancy->urgency === 'high')
                                        <span class="urgency-badge high">
                                            <i class="fas fa-exclamation me-1"></i>
                                            Urgent
                                        </span>
                                    @elseif($vacancy->urgency === 'medium')
                                        <span class="urgency-badge medium">
                                            <i class="fas fa-clock me-1"></i>
                                            Medium
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Vacancy Details -->
                            <div class="vacancy-details">
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <i class="fas fa-book text-primary"></i>
                                        <span>{{ $vacancy->subject }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-graduation-cap text-success"></i>
                                        <span>{{ $vacancy->grade_level }}</span>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                        <span>{{ $vacancy->budget_range }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock text-info"></i>
                                        <span>{{ $vacancy->duration_hours }}h sessions</span>
                                    </div>
                                </div>

                                @if($vacancy->address)
                                    <div class="detail-row">
                                        <div class="detail-item full-width">
                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                            <span>{{ $vacancy->address }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="detail-row">
                                        <div class="detail-item full-width">
                                            <i class="fas fa-globe text-info"></i>
                                            <span>{{ ucfirst(str_replace('_', ' ', $vacancy->location_type)) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="vacancy-description">
                                    <p>{{ Str::limit($vacancy->description, 120) }}</p>
                                </div>

                                @if($vacancy->schedule_days && count($vacancy->schedule_days) > 0)
                                    <div class="schedule-info">
                                        <div class="schedule-label">
                                            <i class="fas fa-calendar me-1"></i>
                                            Preferred Days:
                                        </div>
                                        <div class="schedule-tags">
                                            @foreach(array_slice($vacancy->schedule_days, 0, 3) as $day)
                                                <span class="schedule-tag">{{ $day }}</span>
                                            @endforeach
                                            @if(count($vacancy->schedule_days) > 3)
                                                <span class="more-days">+{{ count($vacancy->schedule_days) - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Vacancy Footer -->
                            <div class="vacancy-footer">
                                <div class="posted-time">
                                    <i class="fas fa-clock me-1"></i>
                                    Posted {{ $vacancy->created_at->diffForHumans() }}
                                </div>
                                
                                <div class="vacancy-actions">
                                    @auth('tutor')
                                        <button class="btn btn-primary btn-sm" onclick="showContactModal('{{ $vacancy->id }}')">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            Apply Now
                                        </button>
                                    @else
                                        <a href="{{ route('tutor.login') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            Login to Apply
                                        </a>
                                    @endauth
                                    
                                    <button class="btn btn-outline-info btn-sm" onclick="showVacancyDetails('{{ $vacancy->id }}')">
                                        <i class="fas fa-eye me-1"></i>
                                        Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $vacancies->withQueryString()->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="no-results text-center py-5">
            <div class="mb-4">
                <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
            </div>
            <h4 class="text-muted mb-3">No vacancies found</h4>
            <p class="text-muted mb-4">Try adjusting your search criteria or check back later for new opportunities.</p>
            <a href="{{ route('search.vacancies') }}" class="btn btn-primary">
                <i class="fas fa-refresh me-2"></i>
                Clear Filters
            </a>
        </div>
    @endif
</div>

<!-- Modals for details and contact -->
<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply for Vacancy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Send a message to the student expressing your interest in this vacancy.</p>
                <form>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" rows="4" 
                                  placeholder="Hi! I'm interested in tutoring your child. I have experience in..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Send Application</button>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vacancy Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
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

.vacancy-card {
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

.vacancy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.vacancy-header {
    margin-bottom: 20px;
}

.vacancy-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
    line-height: 1.3;
}

.student-info {
    font-size: 14px;
    color: #6c757d;
    display: flex;
    align-items: center;
}

.urgency-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    white-space: nowrap;
}

.urgency-badge.high {
    background: #fee;
    color: #dc3545;
}

.urgency-badge.medium {
    background: #fff8e1;
    color: #f57c00;
}

.vacancy-details {
    flex: 1;
    margin-bottom: 20px;
}

.detail-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #495057;
    flex: 1;
    min-width: 120px;
}

.detail-item.full-width {
    flex: 1 1 100%;
    min-width: auto;
}

.detail-item i {
    width: 16px;
    text-align: center;
}

.vacancy-description {
    margin-bottom: 15px;
}

.vacancy-description p {
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
    margin: 0;
}

.schedule-info {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.schedule-label {
    font-size: 13px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.schedule-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.schedule-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 500;
}

.more-days {
    color: #6c757d;
    font-size: 11px;
    font-style: italic;
    align-self: center;
}

.vacancy-footer {
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
}

.posted-time {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.vacancy-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.vacancy-actions .btn {
    font-size: 13px;
    padding: 6px 12px;
    flex: 1;
    min-width: auto;
}

.no-results {
    background: white;
    border-radius: 15px;
    padding: 60px 40px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .search-header .d-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .vacancy-card {
        padding: 20px;
    }
    
    .vacancy-title {
        font-size: 16px;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .detail-item {
        min-width: auto;
    }
    
    .vacancy-actions {
        flex-direction: column;
    }
    
    .vacancy-actions .btn {
        flex: none;
    }
}
</style>

<script>
function showContactModal(vacancyId) {
    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
}

function showVacancyDetails(vacancyId) {
    // Load vacancy details via AJAX
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const modalBody = document.getElementById('detailsModalBody');
    
    // Show loading
    modalBody.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // In a real implementation, you would fetch the details via AJAX
    setTimeout(() => {
        modalBody.innerHTML = `
            <div class="vacancy-detail-content">
                <h6>Full Description</h6>
                <p>This is where the full vacancy description would be displayed...</p>
                
                <h6>Requirements</h6>
                <ul>
                    <li>Experience in the subject</li>
                    <li>Good communication skills</li>
                    <li>Patience with students</li>
                </ul>
                
                <h6>Schedule</h6>
                <p>Flexible schedule based on student availability</p>
            </div>
        `;
    }, 1000);
}
</script>
@endsection
