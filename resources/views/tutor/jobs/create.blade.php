@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Post a New Job</h4>
                        <p class="text-muted mb-0">Share your teaching opportunity with students</p>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('tutor.jobs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Basic Information -->
                            <div class="section-header mb-3">
                                <h5 class="text-primary">Basic Information</h5>
                                <hr>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="e.g., Mathematics Tutor for High School Students">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Describe your teaching approach, experience, and what makes you unique...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="subjects" class="form-label">Subjects <span class="text-danger">*</span></label>
                                    <select class="form-select @error('subjects') is-invalid @enderror" 
                                            id="subjects" name="subjects[]" multiple>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject }}" 
                                                {{ in_array($subject, old('subjects', [])) ? 'selected' : '' }}>
                                                {{ $subject }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple subjects</small>
                                    @error('subjects')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hourly_rate" class="form-label">Hourly Rate (USD) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" 
                                           min="1" step="0.01" placeholder="25.00">
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="section-header mb-3 mt-4">
                                <h5 class="text-primary">Location Details</h5>
                                <hr>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country', $kyc->country ?? '') }}" 
                                           placeholder="Nepal">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label">State/Province <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           id="state" name="state" value="{{ old('state') }}" 
                                           placeholder="Bagmati Province">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror" 
                                           id="district" name="district" value="{{ old('district') }}" 
                                           placeholder="Kathmandu">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="place" class="form-label">Place/Area <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('place') is-invalid @enderror" 
                                           id="place" name="place" value="{{ old('place') }}" 
                                           placeholder="Thamel">
                                    @error('place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="landmark" class="form-label">Landmark</label>
                                    <input type="text" class="form-control @error('landmark') is-invalid @enderror" 
                                           id="landmark" name="landmark" value="{{ old('landmark') }}" 
                                           placeholder="Near School">
                                    @error('landmark')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="ward_no" class="form-label">Ward No.</label>
                                    <input type="text" class="form-control @error('ward_no') is-invalid @enderror" 
                                           id="ward_no" name="ward_no" value="{{ old('ward_no') }}" 
                                           placeholder="5">
                                    @error('ward_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}" 
                                           placeholder="44600">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Teaching Preferences -->
                            <div class="section-header mb-3 mt-4">
                                <h5 class="text-primary">Teaching Preferences</h5>
                                <hr>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="teaching_mode" class="form-label">Teaching Mode <span class="text-danger">*</span></label>
                                    <select class="form-select @error('teaching_mode') is-invalid @enderror" 
                                            id="teaching_mode" name="teaching_mode">
                                        <option value="">Select teaching mode</option>
                                        <option value="home" {{ old('teaching_mode') === 'home' ? 'selected' : '' }}>Home Tuition</option>
                                        <option value="online" {{ old('teaching_mode') === 'online' ? 'selected' : '' }}>Online Classes</option>
                                        <option value="institute" {{ old('teaching_mode') === 'institute' ? 'selected' : '' }}>Institute Classes</option>
                                        <option value="any" {{ old('teaching_mode') === 'any' ? 'selected' : '' }}>Flexible Location</option>
                                    </select>
                                    @error('teaching_mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender_preference" class="form-label">Gender Preference <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender_preference') is-invalid @enderror" 
                                            id="gender_preference" name="gender_preference">
                                        <option value="">Select preference</option>
                                        <option value="male" {{ old('gender_preference') === 'male' ? 'selected' : '' }}>Male Students Only</option>
                                        <option value="female" {{ old('gender_preference') === 'female' ? 'selected' : '' }}>Female Students Only</option>
                                        <option value="any" {{ old('gender_preference') === 'any' ? 'selected' : '' }}>Any Gender</option>
                                    </select>
                                    @error('gender_preference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="student_level" class="form-label">Student Level</label>
                                    <input type="text" class="form-control @error('student_level') is-invalid @enderror" 
                                           id="student_level" name="student_level" value="{{ old('student_level') }}" 
                                           placeholder="e.g., Grade 6-10, University Level">
                                    @error('student_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="session_type" class="form-label">Session Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('session_type') is-invalid @enderror" 
                                            id="session_type" name="session_type">
                                        <option value="">Select session type</option>
                                        <option value="individual" {{ old('session_type') === 'individual' ? 'selected' : '' }}>Individual Only</option>
                                        <option value="group" {{ old('session_type') === 'group' ? 'selected' : '' }}>Group Only</option>
                                        <option value="both" {{ old('session_type') === 'both' ? 'selected' : '' }}>Both Individual & Group</option>
                                    </select>
                                    @error('session_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="max_students" class="form-label">Max Students per Session <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                           id="max_students" name="max_students" value="{{ old('max_students', 1) }}" 
                                           min="1" max="50">
                                    @error('max_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="expires_at" class="form-label">Job Expiry Date</label>
                                    <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at') }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <small class="form-text text-muted">Leave empty for no expiry</small>
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="section-header mb-3 mt-4">
                                <h5 class="text-primary">Additional Information</h5>
                                <hr>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="requirements" class="form-label">Special Requirements</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                              id="requirements" name="requirements" rows="3" 
                                              placeholder="Any special requirements or expectations from students...">{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="gallery" class="form-label">Gallery Images (Optional)</label>
                                    <input type="file" class="form-control @error('gallery') is-invalid @enderror" 
                                           id="gallery" name="gallery[]" multiple accept="image/*">
                                    <small class="form-text text-muted">Upload up to 5 images showcasing your teaching materials, workspace, or achievements</small>
                                    @error('gallery')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Preferred Times -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Preferred Teaching Times</label>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="preferred_times[]" value="morning" id="time_morning">
                                                <label class="form-check-label" for="time_morning">
                                                    Morning (6:00 AM - 12:00 PM)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="preferred_times[]" value="afternoon" id="time_afternoon">
                                                <label class="form-check-label" for="time_afternoon">
                                                    Afternoon (12:00 PM - 6:00 PM)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="preferred_times[]" value="evening" id="time_evening">
                                                <label class="form-check-label" for="time_evening">
                                                    Evening (6:00 PM - 10:00 PM)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('tutor.jobs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Post Job
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.section-header h5 {
    margin-bottom: 10px;
}
.form-label {
    font-weight: 500;
}
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
</style>
@endpush
