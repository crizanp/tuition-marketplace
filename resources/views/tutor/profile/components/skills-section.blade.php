<!-- Skills Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-skills me-2"></i>Skills & Subjects</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('skills')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="skills-content">
        <div class="skills-display">
            @if($profile && $profile->skills)
                @foreach($profile->skills as $skill)
                    <span class="skill-tag">{{ $skill }}</span>
                @endforeach
            @else
                <div class="text-muted">No skills added yet</div>
            @endif
        </div>
    </div>
</div>
