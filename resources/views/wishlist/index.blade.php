@extends('layouts.app')

@section('content')
<style>
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .job-card {
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
    .wishlist-btn {
        background: transparent;
        border: 2px solid #e9ecef;
        color: #6c757d;
        padding: 8px 10px;
        border-radius: 50%;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }
    .wishlist-btn:hover {
        border-color: #ff6b35;
        color: #ff6b35;
        transform: scale(1.1);
    }
    .wishlist-btn.active {
        background: #ff6b35;
        border-color: #ff6b35;
        color: white;
    }
    .wishlist-btn.active:hover {
        background: #e55a2b;
    }
    .wishlist-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
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

    /* Toast Notification Styles */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        min-width: 300px;
        padding: 16px 20px;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        z-index: 9999;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        transform: translateX(100%);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .toast-notification.show {
        transform: translateX(0);
    }
    .toast-notification.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    .toast-notification.error {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
    }
    .toast-notification.info {
        background: linear-gradient(135deg, #007bff, #6f42c1);
    }
    .toast-icon {
        font-size: 18px;
        flex-shrink: 0;
    }
    .toast-content {
        flex: 1;
    }
    .toast-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.8;
        transition: opacity 0.2s ease;
        flex-shrink: 0;
    }
    .toast-close:hover {
        opacity: 1;
    }

    /* Job card fade out animation */
    .job-card.removing {
        opacity: 0;
        transform: scale(0.9) translateY(-10px);
        transition: all 0.3s ease;
    }
</style>

<div class="wishlist-header" style="background: #fac8b0; padding: 60px 0 40px 0;">
    <div class="container text-center">
        <h1 style="font-weight:700; font-size:2.5rem; margin-bottom:10px;">My Wishlist</h1>
        <p style="font-size:1.2rem; opacity:0.9; margin-bottom:0;">Browse jobs you've wishlisted and manage your favorite teaching opportunities.</p>
    </div>
</div>

<div class="container" style="margin-top:20px;background-color:white;">
    @if($wishlistJobs->count() > 0)
        <div class="jobs-grid" id="jobs-grid">
            @foreach($wishlistJobs as $job)
                <div class="job-card" data-job-id="{{ $job->id }}">
                    <div class="job-card-body">
                        <div class="job-header">
                            <h6 class="job-title">{{ Str::limit($job->title, 50) }}</h6>
                            <div class="d-flex align-items-center">
                                <form action="{{ route('jobs.wishlist.toggle', $job->id) }}" method="POST" class="d-inline wishlist-form">
                                    @csrf
                                    @if($job->wishlistedBy->contains(auth()->user()))
                                        <button type="button" class="wishlist-btn active wishlist-toggle-btn" 
                                                data-job-id="{{ $job->id }}" 
                                                data-action-url="{{ route('jobs.wishlist.toggle', $job->id) }}"
                                                title="Remove from Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    @else
                                        <button type="button" class="wishlist-btn wishlist-toggle-btn" 
                                                data-job-id="{{ $job->id }}" 
                                                data-action-url="{{ route('jobs.wishlist.toggle', $job->id) }}"
                                                title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    @endif
                                </form>
                                @if($job->is_featured)
                                    <span class="featured-badge">Featured</span>
                                @endif
                            </div>
                        </div>
                        <div class="tutor-info">
                            @if($job->tutor->kyc && $job->tutor->kyc->profile_photo)
                                <img src="{{ Storage::url($job->tutor->kyc->profile_photo) }}" class="tutor-avatar" alt="Tutor">
                            @else
                                <div class="tutor-placeholder">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <a href="{{ route('tutor.profile.public', $job->tutor->id) }}" class="tutor-name">{{ $job->tutor->name }}</a>
                            @if($job->tutor->kyc && $job->tutor->kyc->status === 'approved')
                                <i class="fas fa-check-circle verified-icon" title="Verified Tutor"></i>
                            @endif
                        </div>
                        <div class="subjects-container">
                            @foreach(array_slice($job->subjects, 0, 2) as $subject)
                                <span class="subject-badge">{{ $subject }}</span>
                            @endforeach
                            @if(count($job->subjects) > 2)
                                <span class="more-subjects">+{{ count($job->subjects) - 2 }}</span>
                            @endif
                        </div>
                        <p class="job-description">{{ Str::limit($job->description, 100) }}</p>
                        <div class="job-meta">
                            <span class="location-info">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $job->place }}, {{ $job->district }}
                            </span>
                            <span class="hourly-rate">Rs.{{ number_format((float)$job->hourly_rate, 2) }}/hr</span>
                        </div>
                    </div>
                    <div class="job-footer">
                        <span class="views-count">
                            <i class="fas fa-eye"></i>{{ $job->views }} views
                        </span>
                        <a href="{{ route('jobs.show', ['tutorName' => Str::slug($job->tutor->name), 'jobId' => $job->id]) }}" class="btn-view-details">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state" id="empty-state">
            <i class="fas fa-heart-broken empty-icon"></i>
            <h5 class="empty-title">Your wishlist is empty</h5>
            <p class="empty-text">Browse jobs and add your favorites to your wishlist.</p>
            <a href="{{ route('jobs.index') }}" class="btn-view-details">View All Jobs</a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle wishlist toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-toggle-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.wishlist-toggle-btn');
            const jobId = button.getAttribute('data-job-id');
            const actionUrl = button.getAttribute('data-action-url');
            const jobCard = button.closest('.job-card');
            
            // Disable button during request
            button.disabled = true;
            button.style.opacity = '0.6';
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]');
            if (!token) {
                showToast('CSRF token not found. Please refresh the page.', 'error', 'fas fa-exclamation-triangle');
                button.disabled = false;
                button.style.opacity = '1';
                return;
            }
            
            // Make AJAX request using XMLHttpRequest for better compatibility
            const xhr = new XMLHttpRequest();
            xhr.open('POST', actionUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    button.disabled = false;
                    button.style.opacity = '1';
                    
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            
                            if (data.success) {
                                if (data.added === false) { // Item was removed
                                    showToast('Removed from wishlist successfully!', 'success', 'fas fa-heart-broken');
                                    
                                    // Animate card removal
                                    jobCard.classList.add('removing');
                                    
                                    setTimeout(() => {
                                        jobCard.remove();
                                        
                                        // Check if grid is empty
                                        const jobsGrid = document.getElementById('jobs-grid');
                                        if (jobsGrid && jobsGrid.children.length === 0) {
                                            jobsGrid.style.display = 'none';
                                            const emptyState = `
                                                <div class="empty-state" id="empty-state">
                                                    <i class="fas fa-heart-broken empty-icon"></i>
                                                    <h5 class="empty-title">Your wishlist is empty</h5>
                                                    <p class="empty-text">Browse jobs and add your favorites to your wishlist.</p>
                                                    <a href="{{ route('jobs.index') }}" class="btn-view-details">View All Jobs</a>
                                                </div>
                                            `;
                                            jobsGrid.insertAdjacentHTML('afterend', emptyState);
                                        }
                                    }, 300);
                                    
                                } else if (data.added === true) { // Item was added
                                    showToast('Added to wishlist successfully!', 'success', 'fas fa-heart');
                                    button.classList.add('active');
                                    button.title = 'Remove from Wishlist';
                                }
                            } else {
                                showToast(data.message || 'Something went wrong!', 'error', 'fas fa-exclamation-circle');
                            }
                        } catch (error) {
                            console.error('JSON Parse Error:', error);
                            showToast('Invalid response from server', 'error', 'fas fa-exclamation-triangle');
                        }
                    } else {
                        showToast('Network error. Please try again.', 'error', 'fas fa-exclamation-triangle');
                    }
                }
            };
            
            xhr.onerror = function() {
                button.disabled = false;
                button.style.opacity = '1';
                showToast('Network error. Please check your connection.', 'error', 'fas fa-exclamation-triangle');
            };
            
            // Send empty body since we're not sending any data
            xhr.send();
        }
    });
});

function showToast(message, type = 'success', icon = 'fas fa-check-circle') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    });

    // Create new toast
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    
    toast.innerHTML = `
        <i class="${icon} toast-icon"></i>
        <div class="toast-content">${message}</div>
        <button type="button" class="toast-close" onclick="this.parentElement.style.transform='translateX(100%)'; setTimeout(() => this.parentElement.remove(), 400);">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 400);
        }
    }, 4000);
}

// Show flash messages as toasts if they exist
@if(session('success'))
    showToast('{{ session('success') }}', 'success', 'fas fa-check-circle');
@endif

@if(session('error'))
    showToast('{{ session('error') }}', 'error', 'fas fa-exclamation-circle');
@endif

@if(session('info'))
    showToast('{{ session('info') }}', 'info', 'fas fa-info-circle');
@endif
</script>

@endsection