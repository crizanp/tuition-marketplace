<!-- Languages Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-language me-2"></i>Languages</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('languages')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="languages-content">
        <div class="languages-display">
            @if($profile && $profile->languages)
                @foreach($profile->languages as $language)
                    <span class="language-tag">{{ $language['name'] }} <small class="text-light">({{ $language['level'] }})</small></span>
                @endforeach
            @else
                <span class="language-tag">English <small class="text-light">(Fluent)</small></span>
                <span class="language-tag">Nepali <small class="text-light">(Native)</small></span>
            @endif
        </div>
    </div>
</div>
