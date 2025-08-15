@extends('layouts.app')



@section('content')
<div class="dashboard-container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm profile-card">
                <div class="card-header profile-card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Post a New Vacancy
                    </h4>
                    <p class="mb-0 mt-2 opacity-75 text-light">Find the perfect tutor for your learning needs</p>
                </div>
                <div class="card-body profile-card-body">
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
                     <label for="title" class="form-label text-light">Vacancy Title <span class="text-danger">*</span></label>
                     <input type="text" class="form-control" id="title" name="title" 
                         value="{{ old('title') }}" required
                         placeholder="e.g., Need Mathematics Tutor for Grade 10">
                     <small class="text-light">Be specific about what you're looking for</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="urgency" class="form-label text-light">Priority Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="urgency" name="urgency" required>
                                        <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High (Urgent)</option>
                                    </select>
                                </div>
                            </div>

                                <div class="mb-3">
                                <label for="description" class="form-label text-light">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          required placeholder="Describe your learning goals, current level, specific topics you need help with, preferred teaching style, etc.">{{ old('description') }}</textarea>
                                <small class="text-light">Provide detailed information to help tutors understand your needs</small>
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
                                    <label for="subject" class="form-label text-light">Subject <span class="text-danger">*</span></label>
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
                                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div id="subject_other_field" class="mt-2" style="display: none;">
                                        <input type="text" id="subject_other" class="form-control" placeholder="Please specify subject" value="{{ old('subject_other') }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="grade_level" class="form-label text-light">Grade Level <span class="text-danger">*</span></label>
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
                                        <option value="other" {{ old('grade_level') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div id="grade_other_field" class="mt-2" style="display: none;">
                                        <input type="text" id="grade_level_other" class="form-control" placeholder="Please specify grade/level" value="{{ old('grade_level_other') }}">
                                    </div>
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
                            <small class="text-light">Enter your preferred hourly rate range</small>
                        </div>

                        <!-- Schedule Selection -->
                        <div class="form-section mb-4">
                            <h5 class="section-title">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Schedule Preferences
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label text-light">Preferred Days <span class="text-danger">*</span></label>
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
                                    <label for="duration_hours" class="form-label text-light">Session Duration (hours) <span class="text-danger">*</span></label>
                                    <select class="form-control" id="duration_hours" name="duration_hours" required>
                                        <option value="1" {{ old('duration_hours') == '1' ? 'selected' : '' }}>1 hour</option>
                                        <option value="2" {{ old('duration_hours') == '2' ? 'selected' : '' }}>2 hours</option>
                                        <option value="3" {{ old('duration_hours') == '3' ? 'selected' : '' }}>3 hours</option>
                                        <option value="4" {{ old('duration_hours') == '4' ? 'selected' : '' }}>4 hours</option>
                                        <option value="other" {{ old('duration_hours') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div id="duration_other_field" class="mt-2" style="display: none;">
                                        <input type="number" id="duration_hours_other" min="0.25" step="0.25" class="form-control" placeholder="Specify duration in hours (e.g., 1.5)" value="{{ old('duration_hours_other') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                    <label class="form-label text-light">Preferred Time Slots <span class="text-danger">*</span></label>
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
                                    <label for="location_type" class="form-label text-light">Location Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="location_type" name="location_type" required>
                                        <option value="flexible" {{ old('location_type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                                        <option value="online" {{ old('location_type') == 'online' ? 'selected' : '' }}>Online Only</option>
                                        <option value="home" {{ old('location_type') == 'home' ? 'selected' : '' }}>At My Home</option>
                                        <option value="tutor_place" {{ old('location_type') == 'tutor_place' ? 'selected' : '' }}>At Tutor's Place</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="address_field" style="display: none;">
                     <label for="address" class="form-label text-light">Address</label>
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
                                <button type="button" class="btn btn-outline-dark-primary btn-sm" onclick="addRequirement()">
                                    <i class="fas fa-plus me-1"></i>
                                    Add Requirement
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('student.vacancies.index') }}" class="btn btn-outline-dark-primary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-dark-primary">
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
/* -----------------------------
   Clean modern palette: orange, black, white
   ----------------------------- */
:root{
    --orange: #ff6a00; /* primary orange */
    --black: #111111;
    --white: #ffffff;
}

/* Page background and base text color */
.dashboard-container{
    background: var(--white);
    min-height: 100vh;
    color: var(--black);
    font-family: Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
}

/* Card */
.profile-card{
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(17,17,17,0.08);
}

/* Header - bold orange bar with white text */
.profile-card-header{
    background: var(--orange);
    color: var(--white);
    padding: 22px 26px;
}
.profile-card-header h4{ margin: 0; font-weight: 700; letter-spacing: -0.2px; }
.profile-card-header p{ margin: .35rem 0 0; opacity: .95; color: rgba(255,255,255,0.95); }

/* Card body */
.profile-card-body{
    padding: 28px;
    background: var(--white);
}

.form-section{
    background: var(--white);
    padding: 18px 18px 16px;
    border-radius: 10px;
    border: 1px solid rgba(17,17,17,0.06);
    color: var(--black);
}

.section-title{
    color: var(--black);
    margin-bottom: 16px;
    padding-bottom: 10px;
    font-weight: 600;
    border-bottom: 1px solid rgba(17,17,17,0.06);
}

.days-selector, .time-slots{
    background: var(--white);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid rgba(17,17,17,0.06);
}

/* Inputs */
.form-control{
    background: var(--white);
    color: var(--black);
    border: 1px solid rgba(17,17,17,0.12);
    border-radius: 8px;
    padding: 10px 12px;
    transition: box-shadow .12s ease, border-color .12s ease, transform .08s ease;
}
.form-control::placeholder{ color: rgba(17,17,17,0.35); }
.form-control:focus{
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 4px 18px rgba(255,106,0,0.12);
    transform: translateY(-1px);
}

/* Small helper for the 'other' input fields */
#subject_other_field input, #grade_other_field input, #duration_other_field input{ border: 1px solid rgba(17,17,17,0.12); }

/* Checkboxes / radios accent color modern way */
.form-check-input{ width: 1.05rem; height: 1.05rem; }
.form-check-input:checked{ accent-color: var(--orange); }

.requirement-item{ animation: fadeIn 0.28s ease; }
@keyframes fadeIn{ from{ opacity: 0; transform: translateY(-8px); } to{ opacity: 1; transform: translateY(0);} }

/* Buttons */
.btn-dark-primary{
    background: var(--black);
    color: var(--white);
    border: 1px solid var(--black);
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(17,17,17,0.06);
}
.btn-dark-primary:hover{ background: #292828ff; transform: translateY(-1px);color: var(--white); }

.btn-outline-dark-primary{
    background: transparent;
    color: var(--black);
    border: 1px solid var(--orange);
    padding: 9px 14px;
    border-radius: 8px;
    font-weight: 600;
}
.btn-outline-dark-primary:hover{ background: rgba(255,106,0,0.06); }

.btn.btn-outline-danger{ border-radius: 8px; }

/* Links and small accents use orange only */
a, .link{ color: var(--orange); }

/* Prevent horizontal overflow */
html, body{ overflow-x: hidden; }

/* Responsiveness */
@media (max-width: 767px){
    .profile-card-header{ padding: 16px; }
    .profile-card-body{ padding: 18px; }
}

/* Improve label contrast when .text-light is used in template */
.text-light{ color: var(--black) !important; font-weight: 600; }

</style>

<script>
// Autosave draft key (per user if authenticated)
const VACANCY_DRAFT_KEY = 'vacancy_draft_' + '{{ auth()->id() ?? "guest" }}';

function saveDraft() {
    try {
        const data = {
            title: document.getElementById('title').value || '',
            urgency: document.getElementById('urgency').value || '',
            description: document.getElementById('description').value || '',
            subject: document.getElementById('subject').value || '',
            subject_other: document.getElementById('subject_other') ? document.getElementById('subject_other').value || '' : '',
            grade_level: document.getElementById('grade_level').value || '',
            grade_level_other: document.getElementById('grade_level_other') ? document.getElementById('grade_level_other').value || '' : '',
            budget_min: document.getElementById('budget_min').value || '',
            budget_max: document.getElementById('budget_max').value || '',
            duration_hours: document.getElementById('duration_hours').value || '',
            duration_hours_other: document.getElementById('duration_hours_other') ? document.getElementById('duration_hours_other').value || '' : '',
            location_type: document.getElementById('location_type').value || '',
            address: document.getElementById('address') ? document.getElementById('address').value || '' : '',
            schedule_days: Array.from(document.querySelectorAll('input[name="schedule_days[]"]:checked')).map(i => i.value),
            schedule_times: Array.from(document.querySelectorAll('input[name="schedule_times[]"]:checked')).map(i => i.value),
            requirements: Array.from(document.querySelectorAll('input[name="requirements[]"]')).map(i => i.value).filter(v => v && v.trim() !== '')
        };

        localStorage.setItem(VACANCY_DRAFT_KEY, JSON.stringify(data));
        // optional: show a tiny saved indicator (not implemented visually here)
    } catch (err) {
        // localStorage may be unavailable in some browsers or private modes
        console.warn('Could not save draft:', err);
    }
}

function restoreDraft() {
    try {
        const raw = localStorage.getItem(VACANCY_DRAFT_KEY);
        if (!raw) return;
        const data = JSON.parse(raw);

        if (data.title) document.getElementById('title').value = data.title;
        if (data.urgency) document.getElementById('urgency').value = data.urgency;
        if (data.description) document.getElementById('description').value = data.description;
        if (data.subject) document.getElementById('subject').value = data.subject;
    if (data.subject_other && document.getElementById('subject_other')) document.getElementById('subject_other').value = data.subject_other;
        if (data.grade_level) document.getElementById('grade_level').value = data.grade_level;
    if (data.grade_level_other && document.getElementById('grade_level_other')) document.getElementById('grade_level_other').value = data.grade_level_other;
        if (data.budget_min) document.getElementById('budget_min').value = data.budget_min;
        if (data.budget_max) document.getElementById('budget_max').value = data.budget_max;
        if (data.duration_hours) document.getElementById('duration_hours').value = data.duration_hours;
    if (data.duration_hours_other && document.getElementById('duration_hours_other')) document.getElementById('duration_hours_other').value = data.duration_hours_other;
        if (data.location_type) document.getElementById('location_type').value = data.location_type;
        if (data.address && document.getElementById('address')) document.getElementById('address').value = data.address;

        // Restore checkboxes
        if (Array.isArray(data.schedule_days)) {
            data.schedule_days.forEach(val => {
                const el = document.querySelector(`input[name="schedule_days[]"][value="${val}"]`);
                if (el) el.checked = true;
            });
        }
        if (Array.isArray(data.schedule_times)) {
            data.schedule_times.forEach(val => {
                const el = Array.from(document.querySelectorAll('input[name="schedule_times[]"]')).find(i => i.value === val);
                if (el) el.checked = true;
            });
        }

        // Restore requirements - clear existing and rebuild
        if (Array.isArray(data.requirements) && data.requirements.length) {
            const container = document.getElementById('requirements-container');
            container.innerHTML = '';
            data.requirements.forEach(req => {
                const newRequirement = document.createElement('div');
                newRequirement.className = 'requirement-item mb-2';
                newRequirement.innerHTML = `
                    <div class="input-group">
                        <input type="text" class="form-control" name="requirements[]" value="${escapeHtml(req)}" placeholder="e.g., Must have experience with IGCSE curriculum">
                        <button type="button" class="btn btn-outline-danger" onclick="removeRequirement(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRequirement);
            });
        }

        // Trigger address visibility logic in case restored location_type is 'home'
        const locationTypeSelect = document.getElementById('location_type');
        locationTypeSelect.dispatchEvent(new Event('change'));
    // Trigger subject/grade/duration other-field visibility
    document.getElementById('subject').dispatchEvent(new Event('change'));
    document.getElementById('grade_level').dispatchEvent(new Event('change'));
    document.getElementById('duration_hours').dispatchEvent(new Event('change'));
    } catch (err) {
        console.warn('Could not restore draft:', err);
    }
}

// Basic escaping for safe injection into value attributes
function escapeHtml(text) {
    return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

// Show/hide address field based on location type
function handleLocationTypeChange() {
    const select = document.getElementById('location_type');
    const addressField = document.getElementById('address_field');
    const addressInput = document.getElementById('address');
    
    if (select.value === 'home') {
        addressField.style.display = 'block';
        addressInput.required = true;
    } else {
        addressField.style.display = 'none';
        addressInput.required = false;
    }
}

function handleOtherFieldVisibility(selectId, otherFieldId) {
    const sel = document.getElementById(selectId);
    const other = document.getElementById(otherFieldId);
    if (!sel || !other) return;
    if (sel.value === 'other') {
        other.style.display = 'block';
    } else {
        other.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('vacancyForm');

    // Restore draft if present
    restoreDraft();

    // Hook change/input listeners to save draft
    form.querySelectorAll('input, textarea, select').forEach(el => {
        if (el.type === 'file') return; // skip files
        el.addEventListener('input', saveDraft);
        el.addEventListener('change', saveDraft);
    });

    // Show/hide other fields
    const subjectSelect = document.getElementById('subject');
    const gradeSelect = document.getElementById('grade_level');
    const durationSelect = document.getElementById('duration_hours');

    if (subjectSelect) subjectSelect.addEventListener('change', function() {
        handleOtherFieldVisibility('subject', 'subject_other_field');
    });
    if (gradeSelect) gradeSelect.addEventListener('change', function() {
        handleOtherFieldVisibility('grade_level', 'grade_other_field');
    });
    if (durationSelect) durationSelect.addEventListener('change', function() {
        handleOtherFieldVisibility('duration_hours', 'duration_other_field');
    });

    // Attach location change handler
    const locationTypeSelect = document.getElementById('location_type');
    locationTypeSelect.addEventListener('change', function() {
        handleLocationTypeChange();
        saveDraft();
    });

    // Initialize address field visibility
    handleLocationTypeChange();

    // Save draft before unload
    window.addEventListener('beforeunload', saveDraft);

    // On submit: validate and clear draft
    form.addEventListener('submit', function(e) {
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

        // If any select has 'other' selected, ensure the corresponding other field has value and then copy into hidden inputs
        // Ensure hidden inputs exist or create them
        function ensureHidden(name) {
            let el = form.querySelector('input[name="' + name + '"]');
            if (!el) {
                el = document.createElement('input');
                el.type = 'hidden';
                el.name = name;
                form.appendChild(el);
            }
            return el;
        }

        // Subject
        if (document.getElementById('subject').value === 'other') {
            const v = (document.getElementById('subject_other') && document.getElementById('subject_other').value.trim()) || '';
            if (!v) {
                e.preventDefault();
                alert('Please specify the subject in the "Other" field.');
                return false;
            }
            ensureHidden('subject_other').value = v;
        }

        // Grade
        if (document.getElementById('grade_level').value === 'other') {
            const v = (document.getElementById('grade_level_other') && document.getElementById('grade_level_other').value.trim()) || '';
            if (!v) {
                e.preventDefault();
                alert('Please specify the grade/level in the "Other" field.');
                return false;
            }
            ensureHidden('grade_level_other').value = v;
        }

        // Duration
        if (document.getElementById('duration_hours').value === 'other') {
            const v = (document.getElementById('duration_hours_other') && document.getElementById('duration_hours_other').value.trim()) || '';
            if (!v) {
                e.preventDefault();
                alert('Please specify the session duration in hours.');
                return false;
            }
            ensureHidden('duration_hours_other').value = v;
            // Optionally, set duration_hours to the custom value so server sees a numeric primary field
            ensureHidden('duration_hours').value = v;
        }

        try { localStorage.removeItem(VACANCY_DRAFT_KEY); } catch (err) { /* ignore */ }
        return true;
    });
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
    // Save draft after adding
    saveDraft();
}

function removeRequirement(button) {
    button.closest('.requirement-item').remove();
    // Save draft after removing
    saveDraft();
}
</script>
@endsection
