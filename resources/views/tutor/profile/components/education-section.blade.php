<!-- Education Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-graduation-cap me-2"></i>Education</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('education')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="education-content">
        <div class="education-item">
            @if($profile && $profile->education && isset($profile->education['qualification']))
                <div class="education-title">{{ $profile->education['qualification'] }}</div>
                @if(isset($profile->education['institution']))
                    <div class="text-muted">{{ $profile->education['institution'] }}</div>
                @endif
                @if(isset($profile->education['graduation_year']))
                    <div class="text-muted">Graduated: {{ $profile->education['graduation_year'] }}</div>
                @endif
            @else
                <div class="text-muted">No education information added yet</div>
            @endif
        </div>
    </div>
</div>
