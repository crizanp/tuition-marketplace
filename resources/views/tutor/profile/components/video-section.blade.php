<!-- Introduction Video Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-video me-2"></i>Introduction Video</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('video')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="video-content">
        @if($profile && $profile->introduction_video)
            <video class="w-100" controls style="max-height: 300px; border-radius: 8px;">
                <source src="{{ Storage::url($profile->introduction_video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <div class="video-upload-area">
                <i class="fas fa-video fa-2x mb-2"></i>
                <p class="mb-1">Upload introduction video</p>
                <small class="text-muted">Maximum file size: 5MB</small>
            </div>
        @endif
    </div>
</div>
