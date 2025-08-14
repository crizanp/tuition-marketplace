@extends('layouts.app')
@section('content')
<div class="dashboard-container py-5">
    <div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm profile-card">
                <div class="card-header profile-card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Profile
                    </h4>
                </div>
                <div class="card-body profile-card-body">
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
                                    <label for="name" class="form-label text-light">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $student->name) }}" required>
                                </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label text-light">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $student->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label text-light">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $student->phone) }}" 
                                       placeholder="e.g., +977 98xxxxxxxx">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="grade_level" class="form-label text-light">Grade Level</label>
                                @php
                                    $grades = [
                                        'Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12','Bachelor','Master'
                                    ];
                                    $selectedGrade = old('grade_level', $student->grade_level);
                                    $isCustomGrade = $selectedGrade && !in_array($selectedGrade, $grades);
                                @endphp
                                <select class="form-control" id="grade_level" name="grade_level">
                                    <option value="">Select Grade Level</option>
                                    @foreach($grades as $g)
                                        <option value="{{ $g }}" {{ $selectedGrade == $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomGrade ? 'selected' : '' }}>Other</option>
                                </select>

                                <div id="other_grade_container" style="margin-top:8px; display:{{ $isCustomGrade ? 'block' : 'none' }};">
                                    <input type="text" id="grade_level_other" name="grade_level_other" class="form-control" placeholder="Enter your grade level" value="{{ $isCustomGrade ? $selectedGrade : old('grade_level_other') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="qualification" class="form-label text-light">Qualification (optional)</label>
                                <input type="text" class="form-control" id="qualification" name="qualification" value="{{ old('qualification', $student->qualification) }}" placeholder="e.g., High School, BSc, MSc">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="institution" class="form-label text-light">Current School / College / University (optional)</label>
                                <input type="text" class="form-control" id="institution" name="institution" value="{{ old('institution', $student->institution) }}" placeholder="Institution name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="location_district" class="form-label text-light">District (optional)</label>
                                <input type="text" class="form-control" id="location_district" name="location_district" value="{{ old('location_district', $student->location_district) }}" placeholder="District">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="location_place" class="form-label text-light">Place (optional)</label>
                                <input type="text" class="form-control" id="location_place" name="location_place" value="{{ old('location_place', $student->location_place) }}" placeholder="Town / Area">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="location_landmark" class="form-label text-light">Landmark (optional)</label>
                                <input type="text" class="form-control" id="location_landmark" name="location_landmark" value="{{ old('location_landmark', $student->location_landmark) }}" placeholder="Nearby landmark">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label text-light">WhatsApp Number (optional)</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $student->whatsapp) }}" placeholder="e.g., +977 98xxxxxxxx">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light">Preferred Subjects</label>
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
                                    // detect any selected subjects that are not in the predefined list
                                    $otherSubjects = array_values(array_diff($selectedSubjects, $allSubjects));
                                    $otherSubjectsText = old('preferred_subjects_other', implode(', ', $otherSubjects));
                                @endphp
                                
                                <div class="row">
                                    @foreach($allSubjects as $subject)
                                        <div class="col-md-4 col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="preferred_subjects[]" value="{{ $subject }}" 
                                                       id="subject_{{ $loop->index }}"
                                                       {{ in_array($subject, $selectedSubjects) ? 'checked' : '' }}>
                                                <label class="form-check-label text-light" for="subject_{{ $loop->index }}">
                                                    {{ $subject }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="col-12 mt-2">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="subject_other_checkbox" {{ count($otherSubjects) ? 'checked' : '' }}>
                                            <label class="form-check-label text-light me-2" for="subject_other_checkbox">Other</label>
                                            <input type="text" id="subject_other_input" name="preferred_subjects_other" class="form-control" placeholder="Enter other subjects, comma separated" style="display:inline-block; width:auto; max-width:70%;" value="{{ $otherSubjectsText }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="text-light" style="color:white">Select subjects you're interested in learning</small>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                            <a href="{{ route('student.profile.index') }}" class="btn btn-outline-dark-primary">
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
</div>

<style>
.dashboard-container {
    background-color: #fffdfaff;
    min-height: 100vh;
    color: #e9ecef;
}
.container{
    max-width: 1230px;
    margin: 0 auto;
}
.subjects-container {
    background: linear-gradient(135deg, #282825ff 0%, #171716ff 100%);
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #222222;
}

.form-check {
    margin-bottom: 5px;
}

.form-check-input:checked {
    background-color: #48b14f;
    border-color: #cfcfcf;
}

.form-check-label {
    font-size: 14px;
    color: #e6e6e6;
}

.form-control {
    background: #0d0d0d;
    color: #e6e6e6;
    border: 1px solid #222222;
}

.form-control::placeholder { color: #9a9a9a; opacity: 1; }
.form-control::-webkit-input-placeholder { color: #9a9a9a; }
.form-control:-ms-input-placeholder { color: #9a9a9a; }
.form-control::-ms-input-placeholder { color: #9a9a9a; }
.form-control::-moz-placeholder { color: #9a9a9a; opacity: 1; }

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.03);
    border-color: #444444;
}

.profile-card {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);
    border: 1px solid #bbbbbb;
    border-radius: 8px;
    padding: 20px;
    color: #e6e6e6;
}

.profile-card-header {
    background: transparent;
    border-bottom: none;
    color: #ffffff;
}

.profile-card-body {
    background: transparent;
}

.btn-dark-primary {
    background: linear-gradient(135deg, #494948ff 0%, #393735ff 100%);
    color: #fff;
    border: 1px solid #bbbbbb;
}

.btn-outline-dark-primary {
    background: transparent;
    color: #e6e6e6;
    border: 1px solid #bbbbbb;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const gradeSelect = document.getElementById('grade_level');
    const otherGradeContainer = document.getElementById('other_grade_container');
    const gradeOtherInput = document.getElementById('grade_level_other');

    function toggleGradeOther(){
        if(!gradeSelect) return;
        if(gradeSelect.value === 'Other'){
            otherGradeContainer.style.display = 'block';
        } else {
            otherGradeContainer.style.display = 'none';
        }
    }

    if(gradeSelect){
        gradeSelect.addEventListener('change', toggleGradeOther);
        toggleGradeOther();
    }

    // Subjects other toggle
    const subjectOtherCheckbox = document.getElementById('subject_other_checkbox');
    const subjectOtherInput = document.getElementById('subject_other_input');
    if(subjectOtherCheckbox && subjectOtherInput){
        subjectOtherInput.style.display = subjectOtherCheckbox.checked ? 'inline-block' : 'none';
        subjectOtherCheckbox.addEventListener('change', function(){
            subjectOtherInput.style.display = this.checked ? 'inline-block' : 'none';
            if(!this.checked){ subjectOtherInput.value = ''; }
        });
    }

    // On submit, if other subjects provided, append them as hidden inputs named preferred_subjects[]
    const form = document.querySelector('form[action="{{ route('student.profile.update') }}"]');
    if(form){
        form.addEventListener('submit', function(e){
            // grade: if Other selected and grade_other has value, set the select value to that custom text so server receives it
            if(gradeSelect && gradeSelect.value === 'Other' && gradeOtherInput && gradeOtherInput.value.trim() !== ''){
                // create a hidden input to send custom grade instead of 'Other'
                let existing = document.querySelector('input[name="grade_level_custom_sent"]');
                if(existing) existing.remove();
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'grade_level';
                hidden.value = gradeOtherInput.value.trim();
                hidden.setAttribute('data-generated','1');
                form.appendChild(hidden);
                // Also mark the select so server-side won't get duplicate - remove name from select
                gradeSelect.removeAttribute('name');
            }

            if(subjectOtherCheckbox && subjectOtherCheckbox.checked && subjectOtherInput && subjectOtherInput.value.trim() !== ''){
                // remove any previously generated hidden other subject inputs
                document.querySelectorAll('input[data-generated-subject]').forEach(n => n.remove());
                const others = subjectOtherInput.value.split(',').map(s => s.trim()).filter(Boolean);
                others.forEach(s =>{
                    const h = document.createElement('input');
                    h.type = 'hidden';
                    h.name = 'preferred_subjects[]';
                    h.value = s;
                    h.setAttribute('data-generated-subject','1');
                    form.appendChild(h);
                });
            }
        });
    }
});
</script>
@endsection
