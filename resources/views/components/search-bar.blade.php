@props(['action' => '', 'size' => 'large'])

<div class="search-container {{ $size === 'large' ? 'search-large' : 'search-normal' }}">
    <form action="{{ $action }}" method="GET" class="search-form" id="searchForm">
        <div class="search-main">
            <!-- Main Keyword Search Input -->
            <div class="search-input-group">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" 
                           name="keyword" 
                           id="mainSearchInput"
                           class="search-input" 
                           placeholder="Search by keyword, subject, description, tutor name..."
                           value="{{ request('keyword') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="mainSuggestions"></div>
                </div>
            </div>

            <!-- District Filter -->
            <div class="search-filter-group">
                <div class="filter-input-wrapper">
                    <i class="fas fa-map-marked-alt filter-icon"></i>
                    <input type="text" 
                           name="district" 
                           id="districtInput"
                           class="filter-input" 
                           placeholder="District"
                           value="{{ request('district') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="districtSuggestions"></div>
                </div>
            </div>

            <!-- Place Filter -->
            <div class="search-filter-group">
                <div class="filter-input-wrapper">
                    <i class="fas fa-map-marker-alt filter-icon"></i>
                    <input type="text" 
                           name="place" 
                           id="placeInput"
                           class="filter-input" 
                           placeholder="Place/Area"
                           value="{{ request('place') }}"
                           autocomplete="off">
                    <div class="search-suggestions" id="placeSuggestions"></div>
                </div>
            </div>

            <!-- Search Button -->
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
                <span class="search-btn-text">Search</span>
            </button>
        </div>
    </form>
</div>

<style>
.search-container {
    background: #7972dd;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.search-large {
    padding: 50px 40px;
}

.search-normal {
    padding: 20px 25px;
}

.search-main {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.search-input-group {
    flex: 2.5;
    min-width: 250px;
    position: relative;
}

.search-filter-group {
    flex: 1;
    min-width: 130px;
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

/* Responsive Design */
@media (max-width: 1024px) {
    .search-main {
        gap: 10px;
    }
    
    .search-input-group {
        flex: 2;
        min-width: 200px;
    }
    
    .search-filter-group {
        flex: 1;
        min-width: 110px;
    }
}

@media (max-width: 768px) {
    .search-main {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
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
    const districtInput = document.getElementById('districtInput');
    const placeInput = document.getElementById('placeInput');
    
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
    
    // Show suggestions for districts
    const searchDistricts = debounce(async function(query) {
        if (query.length < 2) {
            hideSuggestions('districtSuggestions');
            return;
        }
        
        try {
            const response = await fetch(`{{ route('api.search.districts') }}?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            showSuggestions('districtSuggestions', suggestions, 'districtInput');
        } catch (error) {
            console.error('Error fetching district suggestions:', error);
        }
    }, 300);
    
    // Show suggestions for places
    const searchPlaces = debounce(async function(query) {
        if (query.length < 2) {
            hideSuggestions('placeSuggestions');
            return;
        }
        
        try {
            const response = await fetch(`{{ route('api.search.places') }}?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            showSuggestions('placeSuggestions', suggestions, 'placeInput');
        } catch (error) {
            console.error('Error fetching place suggestions:', error);
        }
    }, 300);
    
    // Event listeners for districts
    if (districtInput) {
        districtInput.addEventListener('input', function() {
            searchDistricts(this.value);
        });
        
        districtInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                searchDistricts(this.value);
            }
        });
    }
    
    // Event listeners for places
    if (placeInput) {
        placeInput.addEventListener('input', function() {
            searchPlaces(this.value);
        });
        
        placeInput.addEventListener('focus', function() {
            if (this.value.length >= 2) {
                searchPlaces(this.value);
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
            hideSuggestions('districtSuggestions');
            hideSuggestions('placeSuggestions');
        }
    });
});
</script>
