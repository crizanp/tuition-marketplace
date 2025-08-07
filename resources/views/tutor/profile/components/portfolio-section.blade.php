<!-- Portfolio Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-briefcase me-2"></i>Portfolio/Experience</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('portfolio')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="portfolio-content">
        <div class="portfolio-grid">
            @if($profile && $profile->portfolio_items)
                @foreach($profile->portfolio_items as $item)
                    <div class="portfolio-item">
                        @if(isset($item['image']))
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['title'] }}">
                        @endif
                        <div class="portfolio-info">
                            <h6>{{ $item['title'] }}</h6>
                            @if(isset($item['description']))
                                <p class="text-muted small">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="portfolio-item add-portfolio" onclick="editSection('portfolio')">
                <i class="fas fa-plus"></i>
                <span>Add Portfolio Item</span>
            </div>
        </div>
    </div>
</div>
