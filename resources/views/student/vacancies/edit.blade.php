@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Vacancy
                    </h4>
                    <p class="mb-0 mt-2 opacity-75">Update your vacancy details</p>
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

                    @if($vacancy->status === 'rejected' && $vacancy->admin_notes)
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Admin Feedback
                            </h6>
                            <p class="mb-0">{{ $vacancy->admin_notes }}</p>
                            <hr>
                            <small>Please address the feedback below and resubmit your vacancy.</small>
                        </div>
                    @endif

                    <form action="{{ route('student.vacancies.update', $vacancy) }}" method="POST" id="vacancyForm">
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('title', $vacancy->title) }}" required
                                           placeholder="e.g., Need Mathematics Tutor for Grade 10">
                                    <small class="text-muted">Be specific about what you're looking for</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="urgency" class="form-label">Priority Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="urgency" name="urgency" required>
                                        <option value="medium" {{ old('urgency', $vacancy->urgency) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ old('urgency', $vacancy->urgency) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="high" {{ old('urgency', $vacancy->urgency) == 'high' ? 'selected' : '' }}>High (Urgent)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          required placeholder="Describe your learning goals, current level, specific topics you need help with, preferred teaching style, etc.">{{ old('description', $vacancy->description) }}</textarea>
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
                                            <option value="{{ $subject }}" {{ old('subject', $vacancy->subject) == $subject ? 'selected' : '' }}>
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
                                            <option value="{{ $grade }}" {{ old('grade_level', $vacancy->grade_level) == $grade ? 'selected' : '' }}>
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
                                           value="{{ old('budget_min', $vacancy->budget_min) }}" required min="0" step="50"
                                           placeholder="e.g., 500">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="budget_max" class="form-label">Maximum Budget (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="budget_max" name="budget_max" 
                                           value="{{ old('budget_max', $vacancy->budget_max) }}" required min="0" step="50"
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
                                            $selectedDays = old('schedule_days', $vacancy->schedule_days ?? []);
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
                                        <option value="1" {{ old('duration_hours', $vacancy->duration_hours) == '1' ? 'selected' : '' }}>1 hour</option>
                                        <option value="2" {{ old('duration_hours', $vacancy->duration_hours) == '2' ? 'selected' : '' }}>2 hours</option>
                                        <option value="3" {{ old('duration_hours', $vacancy->duration_hours) == '3' ? 'selected' : '' }}>3 hours</option>
                                        <option value="4" {{ old('duration_hours', $vacancy->duration_hours) == '4' ? 'selected' : '' }}>4 hours</option>
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
                                        $selectedTimes = old('schedule_times', $vacancy->schedule_times ?? []);
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
                                        <option value="flexible" {{ old('location_type', $vacancy->location_type) == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                        <option value="online" {{ old('location_type', $vacancy->location_type) == 'online' ? 'selected' : '' }}>Online Only</option>
                                        <option value="home" {{ old('location_type', $vacancy->location_type) == 'home' ? 'selected' : '' }}>At My Home</option>
                                        <option value="tutor_place" {{ old('location_type', $vacancy->location_type) == 'tutor_place' ? 'selected' : '' }}>At Tutor's Place</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="address_field" style="display: none;">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="{{ old('address', $vacancy->address) }}" 
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
                                    @php
                                        $requirements = old('requirements', $vacancy->requirements ?? []);
                                    @endphp
                                    @if($requirements && count($requirements) > 0)
                                        @foreach($requirements as $index => $requirement)
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
                            <a href="{{ route('student.vacancies.show', $vacancy) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>
                                Update Vacancy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Color Variables */
:root {
    --orange: #ff6a00;
    --black: #000000;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --border-light: #e9ecef;
    --text-muted: #6c757d;
    --success-green: #28a745;
    --danger-red: #dc3545;
    --info-blue: #17a2b8;
}

/* Base Styling */
body {
    background-color: var(--white);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: var(--black);
}

.container {
    background-color: var(--white);
}

/* Card Styling */
.card {
    border: 1px solid var(--border-light);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header.bg-warning {
    background-color: var(--orange) !important;
    color: var(--white);
    border-bottom: none;
    padding: 1.5rem;
}

.card-header h4 {
    font-weight: 600;
    font-size: 1.25rem;
}

.card-body {
    padding: 2rem;
}

/* Alert Styling */
.alert {
    border-radius: 8px;
    border: none;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

.alert-heading {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

/* Form Section Styling */
.form-section {
    background-color: var(--light-gray);
    border: 1px solid var(--border-light);
    border-radius: 10px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}

.section-title {
    color: var(--black);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--orange);
    display: flex;
    align-items: center;
}

.section-title i {
    color: var(--orange);
}

/* Form Controls */
.form-label {
    color: var(--black);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control, .form-select {
    border: 2px solid var(--border-light);
    border-radius: 8px;
    padding: 0.625rem 0.875rem;
    font-size: 0.9rem;
    background-color: var(--white);
    color: var(--black);
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 0.2rem rgba(255, 106, 0, 0.15);
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Small Text */
.text-muted {
    color: var(--text-muted) !important;
    font-size: 0.85rem;
}

.text-danger {
    color: var(--danger-red) !important;
}

/* Checkbox and Radio Styling */
.days-selector, .time-slots {
    background-color: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 8px;
    padding: 1.25rem;
    margin-top: 0.5rem;
}

.form-check {
    margin-bottom: 0.75rem;
}

.form-check-input {
    width: 1.1em;
    height: 1.1em;
    margin-top: 0.1em;
    border: 2px solid #ced4da;
    border-radius: 4px;
}

.form-check-input:checked {
    background-color: var(--orange);
    border-color: var(--orange);
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 106, 0, 0.15);
}

.form-check-label {
    color: var(--black);
    font-weight: 400;
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.form-check-inline {
    margin-right: 1rem;
    margin-bottom: 0.5rem;
}

/* Requirements Section */
.requirements-section {
    background-color: var(--white);
    border: 2px solid var(--border-light);
    border-radius: 8px;
    padding: 1.25rem;
}

.requirement-item {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.input-group {
    margin-bottom: 0.5rem;
}

.input-group .form-control {
    border-right: none;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: none;
}

/* Button Styling */
.btn {
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border-width: 2px;
}

.btn-warning {
    background-color: var(--orange);
    border-color: var(--orange);
    color: var(--white);
}

.btn-warning:hover, .btn-warning:focus {
    background-color: #e55a00;
    border-color: #e55a00;
    color: var(--white);
    transform: translateY(-1px);
}

.btn-outline-primary {
    border-color: var(--orange);
    color: var(--orange);
    background-color: transparent;
}

.btn-outline-primary:hover, .btn-outline-primary:focus {
    background-color: var(--orange);
    border-color: var(--orange);
    color: var(--white);
}

.btn-outline-secondary {
    border-color: var(--text-muted);
    color: var(--text-muted);
    background-color: transparent;
}

.btn-outline-secondary:hover, .btn-outline-secondary:focus {
    background-color: var(--text-muted);
    border-color: var(--text-muted);
    color: var(--white);
}

.btn-outline-danger {
    border-color: var(--danger-red);
    color: var(--danger-red);
    background-color: transparent;
}

.btn-outline-danger:hover, .btn-outline-danger:focus {
    background-color: var(--danger-red);
    border-color: var(--danger-red);
    color: var(--white);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
}

/* Button Groups */
.d-flex.gap-2 {
    gap: 0.75rem;
}

/* Focus States for Accessibility */
.btn:focus, .form-control:focus, .form-check-input:focus {
    outline: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .form-section {
        padding: 1.25rem;
    }
    
    .section-title {
        font-size: 1rem;
    }
    
    .form-check-inline {
        display: block;
        margin-right: 0;
        margin-bottom: 0.75rem;
    }
    
    .d-flex.justify-content-end {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .d-flex.justify-content-end .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-header {
        padding: 1.25rem;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .form-section {
        padding: 1rem;
    }
}

/* Professional Enhancements */
.form-control::placeholder {
    color: #adb5bd;
    opacity: 1;
}

.card-header p {
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Clean Typography */
h4, h5, h6 {
    line-height: 1.3;
}

ul li {
    margin-bottom: 0.25rem;
}

/* Address Field Animation */
#address_field {
    transition: all 0.3s ease;
}

/* Professional Spacing */
.mb-3 {
    margin-bottom: 1rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.py-5 {
    padding-top: 2.5rem !important;
    padding-bottom: 2.5rem !important;
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