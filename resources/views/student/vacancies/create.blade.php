@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Post a New Vacancy
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Find the perfect tutor for your learning needs</p>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.vacancies.store') }}" method="POST" id="vacancyForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Basic Information
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">Vacancy Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title') }}" required
                                           placeholder="e.g., Need Mathematics Tutor for Grade 10">
                                    <small class="text-muted">Be specific about what you're looking for</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="urgency" class="form-label">Priority Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="urgency" name="urgency" required>
                                        <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High (Urgent)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          required placeholder="Describe your learning goals, current level, specific topics you need help with, preferred teaching style, etc.">{{ old('description') }}</textarea>
                                <small class="text-muted">Provide detailed information to help tutors understand your needs</small>
                            </div>
                        </div>

                        <!-- Subject & Grade -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-book me-2"></i>
                                Subject & Grade Details
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject" name="subject" required>
                                        <option value="">Select Subject</option>
                                        @php
                                            $subjects = [
                                                'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 
                                                'Nepali', 'Science', 'Social Studies', 'Computer Science', 
                                                'Accountancy', 'Economics', 'Business Studies', 'History', 
                                                'Geography', 'Psychology', 'Sociology', 'Philosophy',
                                                'Statistics', 'Management', 'Law'
                                            ];
                                        @endphp
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject }}" {{ old('subject') == $subject ? 'selected' : '' }}>
                                                {{ $subject }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="grade_level" name="grade_level" required>
                                        <option value="">Select Grade Level</option>
                                        @php
                                            $grades = [
                                                'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
                                                'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10',
                                                'Grade 11', 'Grade 12', 'Bachelor', 'Master'
                                            ];
                                        @endphp
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>
                                                {{ $grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Budget Range
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="budget_min" class="form-label">Minimum Budget (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="budget_min" name="budget_min" 
                                           value="{{ old('budget_min') }}" required min="0" step="50"
                                           placeholder="e.g., 500">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="budget_max" class="form-label">Maximum Budget (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="budget_max" name="budget_max" 
                                           value="{{ old('budget_max') }}" required min="0" step="50"
                                           placeholder="e.g., 1000">
                                </div>
                            </div>
                            <small class="text-muted">Enter your preferred hourly rate range</small>
                        </div>

                        <!-- Schedule Selection -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Schedule Preferences
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Preferred Days <span class="text-danger">*</span></label>
                                    <div class="days-selector">
                                        @php
                                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            $selectedDays = old('schedule_days', []);
                                        @endphp
                                        @foreach($days as $day)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="schedule_days[]" value="{{ $day }}" 
                                                       id="day_{{ strtolower($day) }}"
                                                       {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ strtolower($day) }}">
                                                    {{ $day }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="duration_hours" class="form-label">Session Duration (hours) <span class="text-danger">*</span></label>
                                    <select class="form-control" id="duration_hours" name="duration_hours" required>
                                        <option value="1" {{ old('duration_hours') == '1' ? 'selected' : '' }}>1 hour</option>
                                        <option value="2" {{ old('duration_hours') == '2' ? 'selected' : '' }}>2 hours</option>
                                        <option value="3" {{ old('duration_hours') == '3' ? 'selected' : '' }}>3 hours</option>
                                        <option value="4" {{ old('duration_hours') == '4' ? 'selected' : '' }}>4 hours</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Preferred Time Slots <span class="text-danger">*</span></label>
                                <div class="time-slots">
                                    @php
                                        $timeSlots = [
                                            '6:00 AM - 8:00 AM', '8:00 AM - 10:00 AM', '10:00 AM - 12:00 PM',
                                            '12:00 PM - 2:00 PM', '2:00 PM - 4:00 PM', '4:00 PM - 6:00 PM',
                                            '6:00 PM - 8:00 PM', '8:00 PM - 10:00 PM'
                                        ];
                                        $selectedTimes = old('schedule_times', []);
                                    @endphp
                                    <div class="row">
                                        @foreach($timeSlots as $timeSlot)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="schedule_times[]" value="{{ $timeSlot }}" 
                                                           id="time_{{ $loop->index }}"
                                                           {{ in_array($timeSlot, $selectedTimes) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="time_{{ $loop->index }}">
                                                        {{ $timeSlot }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Location Preferences
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location_type" class="form-label">Location Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="location_type" name="location_type" required>
                                        <option value="flexible" {{ old('location_type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                        <option value="online" {{ old('location_type') == 'online' ? 'selected' : '' }}>Online Only</option>
                                        <option value="home" {{ old('location_type') == 'home' ? 'selected' : '' }}>At My Home</option>
                                        <option value="tutor_place" {{ old('location_type') == 'tutor_place' ? 'selected' : '' }}>At Tutor's Place</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="address_field" style="display: none;">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="{{ old('address') }}" 
                                           placeholder="Enter your address">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Requirements -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-list-check me-2"></i>
                                Additional Requirements (Optional)
                            </h5>
                            
                            <div class="requirements-section">
                                <div id="requirements-container">
                                    @if(old('requirements'))
                                        @foreach(old('requirements') as $index => $requirement)
                                            <div class="requirement-item mb-2">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="requirements[]" 
                                                           value="{{ $requirement }}" placeholder="e.g., Must have experience with IGCSE curriculum">
                                                    <button type="button" class="btn btn-outline-danger" onclick="removeRequirement(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRequirement()">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Requirement
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('student.vacancies.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>
                                Post Vacancy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.section-title {
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.days-selector, .time-slots {
    background: white;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.requirement-item {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
// Show/hide address field based on location type
document.getElementById('location_type').addEventListener('change', function() {
    const addressField = document.getElementById('address_field');
    const addressInput = document.getElementById('address');
    
    if (this.value === 'home') {
        addressField.style.display = 'block';
        addressInput.required = true;
    } else {
        addressField.style.display = 'none';
        addressInput.required = false;
    }
});

// Initialize address field visibility
document.addEventListener('DOMContentLoaded', function() {
    const locationTypeSelect = document.getElementById('location_type');
    locationTypeSelect.dispatchEvent(new Event('change'));
});

function addRequirement() {
    const container = document.getElementById('requirements-container');
    const newRequirement = document.createElement('div');
    newRequirement.className = 'requirement-item mb-2';
    newRequirement.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="requirements[]" 
                   placeholder="e.g., Must have experience with IGCSE curriculum">
            <button type="button" class="btn btn-outline-danger" onclick="removeRequirement(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(newRequirement);
}

function removeRequirement(button) {
    button.closest('.requirement-item').remove();
}

// Validate that at least one day and one time slot is selected
document.getElementById('vacancyForm').addEventListener('submit', function(e) {
    const selectedDays = document.querySelectorAll('input[name="schedule_days[]"]:checked');
    const selectedTimes = document.querySelectorAll('input[name="schedule_times[]"]:checked');
    
    if (selectedDays.length === 0) {
        e.preventDefault();
        alert('Please select at least one preferred day.');
        return false;
    }
    
    if (selectedTimes.length === 0) {
        e.preventDefault();
        alert('Please select at least one preferred time slot.');
        return false;
    }
});
</script>
@endsection
