@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h3>Post Vacancy (Admin)</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vacancies.store') }}" method="POST" id="adminVacancyForm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject') ?? 'General' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Grade Level</label>
                <input type="text" name="grade_level" class="form-control" value="{{ old('grade_level') ?? 'All' }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Budget Min</label>
                <input type="number" name="budget_min" class="form-control" value="{{ old('budget_min') ?? 0 }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Budget Max</label>
                <input type="number" name="budget_max" class="form-control" value="{{ old('budget_max') ?? 0 }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Duration Hours</label>
            <input type="text" name="duration_hours" class="form-control" value="{{ old('duration_hours') ?? 1 }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location Type</label>
            <select name="location_type" id="location_type" class="form-control">
                <option value="flexible" {{ old('location_type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                <option value="online" {{ old('location_type') == 'online' ? 'selected' : '' }}>Online</option>
                <option value="home" {{ old('location_type') == 'home' ? 'selected' : '' }}>Home</option>
                <option value="tutor_place" {{ old('location_type') == 'tutor_place' ? 'selected' : '' }}>Tutor Place</option>
            </select>
        </div>

        <div class="mb-3" id="address_field" style="{{ old('location_type') == 'home' ? '' : 'display:none;' }}">
            <label class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
        </div>

        <!-- Schedule selection (required) -->
        <div class="form-section mb-3">
            <label class="form-label">Preferred Days <span class="text-danger">*</span></label>
            @php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $selectedDays = old('schedule_days', []);
            @endphp
            <div class="mb-2">
                @foreach($days as $day)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="schedule_days[]" id="day_{{ $day }}" value="{{ $day }}" {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                        <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-section mb-3">
            <label class="form-label">Preferred Time Slots <span class="text-danger">*</span></label>
            @php
                $timeSlots = [
                    '6:00 AM - 8:00 AM', '8:00 AM - 10:00 AM', '10:00 AM - 12:00 PM',
                    '12:00 PM - 2:00 PM', '2:00 PM - 4:00 PM', '4:00 PM - 6:00 PM',
                    '6:00 PM - 8:00 PM', '8:00 PM - 10:00 PM'
                ];
                $selectedTimes = old('schedule_times', []);
            @endphp
            <div class="row">
                @foreach($timeSlots as $i => $timeSlot)
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="schedule_times[]" id="time_{{ $i }}" value="{{ $timeSlot }}" {{ in_array($timeSlot, $selectedTimes) ? 'checked' : '' }}>
                            <label class="form-check-label" for="time_{{ $i }}">{{ $timeSlot }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Urgency</label>
            <select name="urgency" class="form-control">
                <option value="medium">Medium</option>
                <option value="low">Low</option>
                <option value="high">High</option>
            </select>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Post Vacancy</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adminVacancyForm');
    const locationSelect = document.getElementById('location_type');
    const addressField = document.getElementById('address_field');
    const addressInput = document.getElementById('address');

    if (locationSelect) {
        locationSelect.addEventListener('change', function() {
            if (this.value === 'home') {
                addressField.style.display = '';
            } else {
                addressField.style.display = 'none';
            }
        });
    }

    form.addEventListener('submit', function(e) {
        // Ensure at least one schedule day
        const days = form.querySelectorAll('input[name="schedule_days[]"]:checked');
        if (days.length === 0) {
            e.preventDefault();
            alert('Please select at least one preferred day for the schedule.');
            return;
        }

        // Ensure at least one schedule time
        const times = form.querySelectorAll('input[name="schedule_times[]"]:checked');
        if (times.length === 0) {
            e.preventDefault();
            alert('Please select at least one preferred time slot for the schedule.');
            return;
        }

        // If location is home, require address
        if (locationSelect && locationSelect.value === 'home') {
            if (!addressInput || !addressInput.value.trim()) {
                e.preventDefault();
                alert('Please provide an address for Home location type.');
                return;
            }
        }
    });
});
</script>
@endpush
