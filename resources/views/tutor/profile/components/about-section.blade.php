<!-- About Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-info-circle me-2"></i>About</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('about')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="about-content">
        <div class="info-display">
            {{ $tutor->bio ?? 'No description provided' }}
        </div>
    </div>
</div>
