@props(['action' => '', 'placeholder' => 'Search for tutors, subjects, locations...', 'showFilters' => true, 'size' => 'large'])

<div class="search-container {{ $size === 'large' ? 'search-large' : 'search-normal' }}">
    <form action="{{ $action }}" method="GET" class="search-form" id="searchForm">
        <div class="search-main">
            <!-- Main Search Input -->
            <div class="search-input-group">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="keyword" 
                           id="mainSearchInput"
                           class="search-input" 
                           placeholder="{{ $placeholder }}"
                           value="{{ request('keyword') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="mainSuggestions"></div>
                </div>
            </div>

            <!-- Subject Filter -->
            <div class="search-filter-group">
                <div class="filter-input-wrapper">
                    <i class="fas fa-book filter-icon"></i>
                    <input type="text" 
                           name="subject" 
                           id="subjectInput"
                           class="filter-input" 
                           placeholder="Subject"
                           value="{{ request('subject') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="subjectSuggestions"></div>
                </div>
            </div>

            <!-- Location Filter -->
            <div class="search-filter-group">
                <div class="filter-input-wrapper">
                    <i class="fas fa-map-marker-alt filter-icon"></i>
                    <input type="text" 
                           name="location" 
                           id="locationInput"
                           class="filter-input" 
                           placeholder="Location"
                           value="{{ request('location') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="locationSuggestions"></div>
                </div>
            </div>

            <!-- Search Button -->
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
                <span class="search-btn-text">Search</span>
            </button>
        </div>

        @if($showFilters)
        <!-- Advanced Filters (Collapsible) -->
        <div class="advanced-filters" id="advancedFilters">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="grade">Grade Level</label>
                    <select name="grade" id="grade" class="filter-select">
                        <option value="">Any Grade</option>
                        @php
                            $grades = [
                                'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
                                'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10',
                                'Grade 11', 'Grade 12', 'Bachelor', 'Master'
                            ];
                        @endphp
                        @foreach($grades as $grade)
                            <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="budget_min">Min Budget (Rs.)</label>
                    <input type="number" name="budget_min" id="budget_min" class="filter-input" 
                           placeholder="500" value="{{ request('budget_min') }}" min="0" step="100">
                </div>

                <div class="filter-group">
                    <label for="budget_max">Max Budget (Rs.)</label>
                    <input type="number" name="budget_max" id="budget_max" class="filter-input" 
                           placeholder="2000" value="{{ request('budget_max') }}" min="0" step="100">
                </div>

                <div class="filter-group">
                    <label for="sort">Sort By</label>
                    <select name="sort" id="sort" class="filter-select">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Relevance</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Most Experienced</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Toggle Advanced Filters -->
        <div class="filters-toggle">
            <button type="button" id="toggleFilters" class="toggle-btn">
                <i class="fas fa-sliders-h"></i>
                <span>Advanced Filters</span>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </button>
        </div>
        @endif
    </form>
</div>

<style>
.search-container {
    background: #c0b8f1;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.search-large {
    padding: 50px 40px;
}

.search-normal {
    padding: 20px 25px;
}

.search-main {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.search-input-group {
    flex: 2;
    min-width: 300px;
    position: relative;
}

.search-filter-group {
    flex: 1;
    min-width: 150px;
    position: relative;
}

.search-input-wrapper, .filter-input-wrapper {
    position: relative;
    width: 100%;
}

.search-input, .filter-input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    color: #2c3e50;
}

.search-input:focus, .filter-input:focus {
    outline: none;
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    background: rgba(255,255,255,1);
}

.search-icon, .filter-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    z-index: 2;
    font-size: 18px;
}

.search-btn {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    border: none;
    padding: 15px 30px;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(238, 90, 36, 0.3);
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(238, 90, 36, 0.4);
}

.search-btn:active {
    transform: translateY(0);
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.suggestion-item {
    padding: 12px 20px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s ease;
    color: #2c3e50;
}

.suggestion-item:hover {
    background: #f8f9fa;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.advanced-filters {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 20px;
    backdrop-filter: blur(10px);
    display: none;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    color: white;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
}

.filter-select {
    padding: 12px 15px;
    border: none;
    border-radius: 8px;
    background: rgba(255,255,255,0.95);
    color: #2c3e50;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    background: white;
    transform: translateY(-1px);
}

.filters-toggle {
    text-align: center;
    margin-top: 15px;
}

.toggle-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.toggle-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-1px);
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.toggle-icon.rotated {
    transform: rotate(180deg);
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-main {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input-group, .search-filter-group {
        flex: none;
        min-width: auto;
    }
    
    .search-btn {
        justify-content: center;
        padding: 15px 20px;
    }
    
    .search-btn-text {
        display: inline;
    }
    
    .filters-row {
        grid-template-columns: 1fr;
    }
    
    .search-large {
        padding: 30px 20px;
    }
}

@media (max-width: 480px) {
    .search-btn-text {
        display: none;
    }
    
    .search-input, .filter-input {
        font-size: 14px;
        padding: 12px 15px 12px 40px;
    }
    
    .search-icon, .filter-icon {
        font-size: 16px;
        left: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live search functionality
    const mainSearchInput = document.getElementById('mainSearchInput');
    const subjectInput = document.getElementById('subjectInput');
    const locationInput = document.getElementById('locationInput');
    
    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Show suggestions for subjects
    const searchSubjects = debounce(async function(query) {
        if (query.length < 2) {
            hideSuggestions('subjectSuggestions');
            return;
        }
        
        try {
            const response = await fetch(`{{ route('api.search.subjects') }}?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            showSuggestions('subjectSuggestions', suggestions, 'subjectInput');
        } catch (error) {
            console.error('Error fetching subject suggestions:', error);
        }
    }, 300);
    
    // Show suggestions for locations
    const searchLocations = debounce(async function(query) {
        if (query.length < 2) {
            hideSuggestions('locationSuggestions');
            return;
        }
        
        try {
            const response = await fetch(`{{ route('api.search.locations') }}?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            showSuggestions('locationSuggestions', suggestions, 'locationInput');
        } catch (error) {
            console.error('Error fetching location suggestions:', error);
        }
    }, 300);
    
    // Event listeners
    if (subjectInput) {
        subjectInput.addEventListener('input', function() {
            searchSubjects(this.value);
        });
        
        subjectInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                searchSubjects(this.value);
            }
        });
    }
    
    if (locationInput) {
        locationInput.addEventListener('input', function() {
            searchLocations(this.value);
        });
        
        locationInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                searchLocations(this.value);
            }
        });
    }
    
    // Show suggestions
    function showSuggestions(containerId, suggestions, inputId) {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);
        
        if (!container || !input) return;
        
        container.innerHTML = '';
        
        if (suggestions.length === 0) {
            hideSuggestions(containerId);
            return;
        }
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = suggestion;
            item.addEventListener('click', function() {
                input.value = suggestion;
                hideSuggestions(containerId);
            });
            container.appendChild(item);
        });
        
        container.style.display = 'block';
    }
    
    // Hide suggestions
    function hideSuggestions(containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            container.style.display = 'none';
        }
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-input-wrapper') && !e.target.closest('.filter-input-wrapper')) {
            hideSuggestions('mainSuggestions');
            hideSuggestions('subjectSuggestions');
            hideSuggestions('locationSuggestions');
        }
    });
    
    // Advanced filters toggle
    const toggleBtn = document.getElementById('toggleFilters');
    const advancedFilters = document.getElementById('advancedFilters');
    const toggleIcon = document.querySelector('.toggle-icon');
    
    if (toggleBtn && advancedFilters) {
        toggleBtn.addEventListener('click', function() {
            if (advancedFilters.style.display === 'none' || advancedFilters.style.display === '') {
                advancedFilters.style.display = 'block';
                toggleIcon.classList.add('rotated');
            } else {
                advancedFilters.style.display = 'none';
                toggleIcon.classList.remove('rotated');
            }
        });
    }
    
    // Form enhancement for real-time search
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        // Auto-submit on filter changes (optional)
        const autoSubmitElements = searchForm.querySelectorAll('select[name="grade"], select[name="sort"]');
        autoSubmitElements.forEach(element => {
            element.addEventListener('change', function() {
                // Optionally auto-submit the form
                // searchForm.submit();
            });
        });
    }
});
</script>
