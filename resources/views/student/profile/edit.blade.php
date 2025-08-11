@extends('layouts.app')

@section('navbar')
    @include('partials.student-navbar')
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Profile
                    </h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $student->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $student->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $student->phone) }}" 
                                       placeholder="e.g., +977 98xxxxxxxx">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="grade_level" class="form-label">Grade Level</label>
                                <select class="form-control" id="grade_level" name="grade_level">
                                    <option value="">Select Grade Level</option>
                                    <option value="Grade 1" {{ old('grade_level', $student->grade_level) == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                    <option value="Grade 2" {{ old('grade_level', $student->grade_level) == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                    <option value="Grade 3" {{ old('grade_level', $student->grade_level) == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                    <option value="Grade 4" {{ old('grade_level', $student->grade_level) == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                    <option value="Grade 5" {{ old('grade_level', $student->grade_level) == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                    <option value="Grade 6" {{ old('grade_level', $student->grade_level) == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                    <option value="Grade 7" {{ old('grade_level', $student->grade_level) == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('grade_level', $student->grade_level) == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                    <option value="Grade 9" {{ old('grade_level', $student->grade_level) == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                    <option value="Grade 10" {{ old('grade_level', $student->grade_level) == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                    <option value="Grade 11" {{ old('grade_level', $student->grade_level) == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('grade_level', $student->grade_level) == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                    <option value="Bachelor" {{ old('grade_level', $student->grade_level) == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                    <option value="Master" {{ old('grade_level', $student->grade_level) == 'Master' ? 'selected' : '' }}>Master</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preferred Subjects</label>
                            <div class="subjects-container">
                                @php
                                    $allSubjects = [
                                        'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 
                                        'Nepali', 'Science', 'Social Studies', 'Computer Science', 
                                        'Accountancy', 'Economics', 'Business Studies', 'History', 
                                        'Geography', 'Psychology', 'Sociology', 'Philosophy',
                                        'Statistics', 'Management', 'Law'
                                    ];
                                    $selectedSubjects = old('preferred_subjects', $student->preferred_subjects ?? []);
                                @endphp
                                
                                <div class="row">
                                    @foreach($allSubjects as $subject)
                                        <div class="col-md-4 col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="preferred_subjects[]" value="{{ $subject }}" 
                                                       id="subject_{{ $loop->index }}"
                                                       {{ in_array($subject, $selectedSubjects) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="subject_{{ $loop->index }}">
                                                    {{ $subject }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <small class="text-muted">Select subjects you're interested in learning</small>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                            <a href="{{ route('student.profile.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.subjects-container {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.form-check {
    margin-bottom: 5px;
}

.form-check-input:checked {
    background-color: #3498db;
    border-color: #3498db;
}

.form-check-label {
    font-size: 14px;
    color: #2c3e50;
}
</style>
@endsection
