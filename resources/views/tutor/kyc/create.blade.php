@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="kyc-form-container py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="kyc-form-card">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">KYC Application Form</h4>
                            <p class="text-muted mb-0">Please fill in all required information accurately</p>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('tutor.kyc.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Personal Information Section -->
                                <div class="form-section">
                                    <h5 class="section-title">Personal Information</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::guard('tutor')->user()->name) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::guard('tutor')->user()->email) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::guard('tutor')->user()->phone) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="hourly_rate" class="form-label">Hourly Rate (Rs) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', Auth::guard('tutor')->user()->hourly_rate) }}" min="1" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>

<div class="mb-3 position-relative">
    <label for="exact_location" class="form-label">Exact Location <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="exact_location" name="exact_location" value="{{ old('exact_location') }}" placeholder="Start typing your city or area in Nepal..." autocomplete="off" required>
    <ul id="location-suggestions" class="list-group position-absolute w-100" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></ul>
</div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description/Bio <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Tell us about yourself, your teaching experience, and methodology (minimum 50 characters)">{{ old('description', Auth::guard('tutor')->user()->bio) }}</textarea>
                                    </div>
                                </div>

                                <!-- Document Upload Section -->
                                <div class="form-section">
                                    <h5 class="section-title">Document Upload</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="profile_photo" class="form-label">Profile Photo <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*" required>
                                                <small class="text-muted">Upload a clear photo of yourself (JPG, PNG max 2MB)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="qualification_proof" class="form-label">Qualification Proof <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="qualification_proof" name="qualification_proof" accept="image/*,.pdf" required>
                                                <small class="text-muted">Upload your degree/certificate (JPG, PNG, PDF max 2MB)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="citizenship_front" class="form-label">Citizenship (Front) <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="citizenship_front" name="citizenship_front" accept="image/*,.pdf" required>
                                                <small class="text-muted">Front side of your citizenship/ID (JPG, PNG, PDF max 2MB)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="citizenship_back" class="form-label">Citizenship (Back) <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="citizenship_back" name="citizenship_back" accept="image/*,.pdf" required>
                                                <small class="text-muted">Back side of your citizenship/ID (JPG, PNG, PDF max 2MB)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Professional Information Section -->
                                <div class="form-section">
                                    <h5 class="section-title">Professional Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="qualification" class="form-label">Highest Qualification <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="qualification" name="qualification" value="{{ old('qualification') }}" placeholder="e.g., Bachelor in Computer Science, Master in Mathematics" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Do you have any teaching certificate? <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_certificate" id="has_certificate_yes" value="1" {{ old('has_certificate') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_certificate_yes">
                                                Yes, I have a teaching certificate
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_certificate" id="has_certificate_no" value="0" {{ old('has_certificate') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_certificate_no">
                                                No, I don't have a teaching certificate
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3" id="certificate_upload" style="display: none;">
                                        <label for="certificate_file" class="form-label">Upload Teaching Certificate</label>
                                        <input type="file" class="form-control" id="certificate_file" name="certificate_file" accept="image/*,.pdf">
                                        <small class="text-muted">Upload your teaching certificate (JPG, PNG, PDF max 2MB)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Subjects You Expertise In <span class="text-danger">*</span></label>
                                        <div class="subjects-grid">
                                            @foreach($subjects as $subject)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="subjects_expertise[]" value="{{ $subject }}" id="subject_{{ $loop->index }}" {{ in_array($subject, old('subjects_expertise', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="subject_{{ $loop->index }}">
                                                        {{ $subject }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="Other" id="subject_other" {{ in_array('Other', old('subjects_expertise', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="subject_other">
                                                    Other
                                                </label>
                                            </div>
                                        </div>
                                        <div id="other-subject-input" style="display: none; margin-top: 10px;">
                                            <input type="text" class="form-control" name="subjects_expertise[]" id="other_subject" placeholder="Please specify other subject" value="{{ old('other_subject') }}" disabled>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Section -->
                                <div class="form-section">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        By submitting this form, you confirm that all information provided is accurate and true. 
                                        Your application will be reviewed within 2-3 business days.
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('tutor.kyc.show') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Submit KYC Application
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body, .kyc-form-container {
    background: #fff !important;
    color: #111;
    min-height: 100vh;
}

.kyc-form-card .card {
    border: 1px solid #eee;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    border-radius: 14px;
    background: #fff;
}

.kyc-form-card .card-header {
    background: #ff8800;
    color: #fff;
    border-radius: 14px 14px 0 0;
    padding: 28px 24px 18px 24px;
    border-bottom: 1px solid #ff8800;
    box-shadow: 0 2px 8px rgba(255,136,0,0.08);
    text-align: center;
}

.kyc-form-card .card-header h4 {
    color: #fff;
    font-weight: 700;
    letter-spacing: 1px;
}

.kyc-form-card .card-header p {
    color: #fff;
    opacity: 0.85;
}

.form-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee;
}
.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #111;
    font-weight: 700;
    margin-bottom: 18px;
    padding-bottom: 8px;
    border-bottom: 2px solid #ff8800;
    display: inline-block;
    letter-spacing: 0.5px;
}

.form-label {
    color: #111;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #bbb;
    border-radius: 8px;
    padding: 12px;
    background: #fff;
    color: #111;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
    border-color: #ff8800;
    box-shadow: 0 0 0 0.15rem rgba(255,136,0,0.18);
    background: #fff;
    color: #111;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.form-check {
    margin-bottom: 5px;
}
.form-check-input:checked {
    background-color: #ff8800;
    border-color: #ff8800;
}

.btn-primary {
    background: #ff8800;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    color: #fff;
    box-shadow: 0 2px 8px rgba(255,136,0,0.08);
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-primary:hover {
    background: #e67e22;
    color: #fff;
    box-shadow: 0 4px 16px rgba(255,136,0,0.13);
}

.btn-secondary {
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    background: #111;
    color: #fff;
    border: none;
    transition: background 0.2s;
}
.btn-secondary:hover {
    background: #222;
    color: #fff;
}

.text-danger {
    color: #e74c3c !important;
}

.alert {
    border-radius: 8px;
    background: #fff;
    color: #111;
    border: 1px solid #ff8800;
}
.alert-info {
    background: #fff7ed;
    color: #ff8800;
    border: 1px solid #ff8800;
}

small.text-muted {
    font-size: 0.85em;
    color: #888 !important;
}

#other-subject-input {
    margin-top: 10px;
}
</style>

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide other subject input
    const otherCheckbox = document.getElementById('subject_other');
    const otherInputDiv = document.getElementById('other-subject-input');
    const otherInput = document.getElementById('other_subject');
    
    function toggleOtherInput() {
        if (otherCheckbox && otherCheckbox.checked) {
            otherInputDiv.style.display = 'block';
            otherInput.disabled = false;
            otherInput.required = true;
        } else {
            otherInputDiv.style.display = 'none';
            otherInput.disabled = true;
            otherInput.required = false;
            otherInput.value = '';
        }
    }
    if (otherCheckbox) {
        otherCheckbox.addEventListener('change', toggleOtherInput);
        // On page load, show if checked
        toggleOtherInput();
    }
    const hasCertificateRadios = document.querySelectorAll('input[name="has_certificate"]');
    const certificateUpload = document.getElementById('certificate_upload');
    const certificateFile = document.getElementById('certificate_file');

    hasCertificateRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                certificateUpload.style.display = 'block';
                certificateFile.required = true;
            } else {
                certificateUpload.style.display = 'none';
                certificateFile.required = false;
                certificateFile.value = '';
            }
        });
    });

    // Initialize on page load
    const checkedRadio = document.querySelector('input[name="has_certificate"]:checked');
    if (checkedRadio && checkedRadio.value === '1') {
        certificateUpload.style.display = 'block';
        certificateFile.required = true;
    }

    // --- Nominatim (OpenStreetMap) Autocomplete for Nepal ---
    const locationInput = document.getElementById('exact_location');
    const suggestionsBox = document.getElementById('location-suggestions');
    let debounceTimeout;

    locationInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (debounceTimeout) clearTimeout(debounceTimeout);
        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=8&countrycodes=np&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length === 0) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }
                    data.forEach(place => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.style.cursor = 'pointer';
                        // Minimal label: name + (city or suburb or district)
                        let label = '';
                        if (place.name) {
                            label = place.name;
                            if (place.address) {
                                if (place.address.city) {
                                    label += ', ' + place.address.city;
                                } else if (place.address.town) {
                                    label += ', ' + place.address.town;
                                } else if (place.address.village) {
                                    label += ', ' + place.address.village;
                                } else if (place.address.suburb) {
                                    label += ', ' + place.address.suburb;
                                } else if (place.address.city_district) {
                                    label += ', ' + place.address.city_district;
                                }
                            }
                        } else {
                            label = place.display_name;
                        }
                        li.textContent = label;
                        li.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            locationInput.value = label;
                            suggestionsBox.style.display = 'none';
                            suggestionsBox.innerHTML = '';
                        });
                        suggestionsBox.appendChild(li);
                    });
                    suggestionsBox.style.display = 'block';
                });
        }, 250);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('mousedown', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== locationInput) {
            suggestionsBox.style.display = 'none';
        }
    });
});
</script>
</script>
@endsection
