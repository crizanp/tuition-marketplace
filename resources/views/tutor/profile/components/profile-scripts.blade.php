<script>
// CSRF Token for AJAX requests - with error handling
let csrfToken = null;
try {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        csrfToken = metaTag.getAttribute('content');
    }
} catch (error) {
    console.error('Error getting CSRF token:', error);
}

console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');

// Fallback CSRF token from Laravel
if (!csrfToken) {
    csrfToken = '{{ csrf_token() }}';
    console.log('Using fallback CSRF token');
}

// Helper function to safely attach form event listeners
function attachFormListener(formId, callback) {
    setTimeout(() => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', callback);
        } else {
            console.error(`Form with ID '${formId}' not found`);
        }
    }, 100);
}

// Helper function to make safe AJAX requests with CSRF protection
function safeFetch(url, options = {}) {
    // Ensure CSRF token is available
    if (!csrfToken) {
        showAlert('Security token missing. Please refresh the page.', 'error');
        return Promise.reject(new Error('CSRF token missing'));
    }
    
    // Merge headers with CSRF token
    const headers = {
        'X-CSRF-TOKEN': csrfToken,
        ...options.headers
    };
    
    return fetch(url, {
        ...options,
        headers
    });
}

// Setup AJAX with jQuery (with fallback)
if (typeof $ !== 'undefined') {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
} else {
    console.warn('jQuery not available, AJAX setup skipped');
}

function editSection(section) {
    switch(section) {
        case 'personal':
            editPersonalInfo();
            break;
        case 'about':
            editAbout();
            break;
        case 'skills':
            editSkills();
            break;
        case 'education':
            editEducation();
            break;
        case 'languages':
            editLanguages();
            break;
        case 'video':
            editVideo();
            break;
        case 'portfolio':
            editPortfolio();
            break;
        case 'certifications':
            editCertifications();
            break;
        case 'availability':
            editAvailability();
            break;
    }
}

function editPersonalInfo() {
    const content = document.getElementById('personal-content');
    const currentData = {
        name: '{{ $tutor->name }}',
        email: '{{ $tutor->email }}',
        phone: '{{ $tutor->phone ?? "" }}',
        hourly_rate: '{{ $tutor->hourly_rate ?? "" }}'
    };
    
    content.innerHTML = `
        <form id="personal-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="${currentData.name}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="${currentData.email}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="${currentData.phone}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hourly Rate (Rs.)</label>
                    <input type="number" class="form-control" name="hourly_rate" value="${currentData.hourly_rate}" min="0">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('personal')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('personal-form', function(e) {
        e.preventDefault();
        console.log('Personal form submitted');
        
        const formData = new FormData(this);
        console.log('Form data:', Object.fromEntries(formData));
        
        safeFetch('{{ route("tutor.profile.personal") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Personal information updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating personal information: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating personal information: ' + error.message, 'error');
        });
    });
}

function editAbout() {
    const content = document.getElementById('about-content');
    const currentBio = `{{ $tutor->bio ?? "" }}`;
    
    content.innerHTML = `
        <form id="about-form">
            <div class="mb-3">
                <label class="form-label">About You</label>
                <textarea class="form-control" name="bio" rows="5" maxlength="1000" required>${currentBio}</textarea>
                <small class="text-muted">Maximum 1000 characters</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('about')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('about-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.about") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('About section updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating about section: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating about section: ' + error.message, 'error');
        });
    });
}

function editSkills() {
    const content = document.getElementById('skills-content');
    const currentSkills = @json($kyc && $kyc->subjects_expertise ? $kyc->subjects_expertise : []);
    
    content.innerHTML = `
        <form id="skills-form">
            <div class="mb-3">
                <label class="form-label">Skills & Subjects</label>
                <div id="skills-container">
                    ${currentSkills.map((skill, index) => `
                        <div class="skill-input-group mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="skills[]" value="${skill}" required>
                                <button type="button" class="btn btn-outline-danger" onclick="removeSkill(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `).join('')}
                    ${currentSkills.length === 0 ? `
                        <div class="skill-input-group mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="skills[]" placeholder="Enter a skill" required>
                                <button type="button" class="btn btn-outline-danger" onclick="removeSkill(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    ` : ''}
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSkill()">
                    <i class="fas fa-plus"></i> Add Skill
                </button>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('skills')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('skills-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.skills") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Skills updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating skills: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating skills: ' + error.message, 'error');
        });
    });
}

function addSkill() {
    const container = document.getElementById('skills-container');
    const newSkill = document.createElement('div');
    newSkill.className = 'skill-input-group mb-2';
    newSkill.innerHTML = `
        <div class="input-group">
            <input type="text" class="form-control" name="skills[]" placeholder="Enter a skill" required>
            <button type="button" class="btn btn-outline-danger" onclick="removeSkill(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(newSkill);
}

function removeSkill(button) {
    button.closest('.skill-input-group').remove();
}

function editEducation() {
    const content = document.getElementById('education-content');
    const currentQualification = `{{ $kyc && $kyc->qualification ? $kyc->qualification : "" }}`;
    
    content.innerHTML = `
        <form id="education-form">
            <div class="mb-3">
                <label class="form-label">Highest Qualification</label>
                <input type="text" class="form-control" name="qualification" value="${currentQualification}" placeholder="Enter your highest qualification" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Institution</label>
                <input type="text" class="form-control" name="institution" placeholder="Enter institution name">
            </div>
            <div class="mb-3">
                <label class="form-label">Year of Completion</label>
                <input type="number" class="form-control" name="year" min="1950" max="${new Date().getFullYear()}" placeholder="Enter year">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('education')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('education-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.education") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Education updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating education: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating education: ' + error.message, 'error');
        });
    });
}

function addLanguage() {
    const container = document.getElementById('languages-container');
    const languageCount = container.children.length;
    const newLanguage = document.createElement('div');
    newLanguage.className = 'language-input-group mb-2';
    newLanguage.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" name="languages[${languageCount}][name]" placeholder="Language" required>
            </div>
            <div class="col-md-4">
                <select class="form-control" name="languages[${languageCount}][level]" required>
                    <option value="Basic">Basic</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Fluent">Fluent</option>
                    <option value="Native">Native</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger" onclick="removeLanguage(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newLanguage);
}

function removeLanguage(button) {
    button.closest('.language-input-group').remove();
}

function editLanguages() {
    const content = document.getElementById('languages-content');
    
    content.innerHTML = `
        <form id="languages-form">
            <div class="mb-3">
                <label class="form-label">Languages</label>
                <div id="languages-container">
                    <div class="language-input-group mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="languages[0][name]" placeholder="Language" value="English" required>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="languages[0][level]" required>
                                    <option value="Basic">Basic</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                    <option value="Fluent" selected>Fluent</option>
                                    <option value="Native">Native</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger" onclick="removeLanguage(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="language-input-group mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="languages[1][name]" placeholder="Language" value="Nepali" required>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="languages[1][level]" required>
                                    <option value="Basic">Basic</option>
                                    <option value="Intermediate">Intermediate</option>
                                    <option value="Advanced">Advanced</option>
                                    <option value="Fluent">Fluent</option>
                                    <option value="Native" selected>Native</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger" onclick="removeLanguage(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addLanguage()">
                    <i class="fas fa-plus"></i> Add Language
                </button>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('languages')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('languages-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.languages") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Languages updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating languages: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating languages: ' + error.message, 'error');
        });
    });
}

function editVideo() {
    const content = document.getElementById('video-content');
    
    content.innerHTML = `
        <form id="video-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Introduction Video</label>
                <input type="file" class="form-control" name="video" accept="video/*" required>
                <small class="text-muted">Maximum file size: 5MB. Supported formats: MP4, AVI, MOV, WMV</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('video')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('video-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.video") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Video uploaded successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error uploading video: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error uploading video: ' + error.message, 'error');
        });
    });
}

function editPortfolio() {
    const content = document.getElementById('portfolio-content');
    
    content.innerHTML = `
        <form id="portfolio-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Portfolio Item Title</label>
                <input type="text" class="form-control" name="title" placeholder="Enter portfolio title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Describe your portfolio item"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <small class="text-muted">Optional. Maximum file size: 2MB</small>
            </div>
            <div class="mb-3">
                <label class="form-label">URL</label>
                <input type="url" class="form-control" name="url" placeholder="https://example.com">
                <small class="text-muted">Optional. Link to your work</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Add Portfolio Item</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('portfolio')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('portfolio-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.portfolio") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Portfolio item added successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error adding portfolio item: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error adding portfolio item: ' + error.message, 'error');
        });
    });
}

function editCertifications() {
    const content = document.getElementById('certifications-content');
    
    content.innerHTML = `
        <form id="certifications-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Certification Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter certification name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Issuing Organization</label>
                <input type="text" class="form-control" name="issuer" placeholder="Who issued this certification?">
            </div>
            <div class="mb-3">
                <label class="form-label">Date Obtained</label>
                <input type="date" class="form-control" name="date">
            </div>
            <div class="mb-3">
                <label class="form-label">Certificate File</label>
                <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Optional. PDF or image file, maximum 2MB</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Add Certification</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('certifications')">Cancel</button>
            </div>
        </form>
    `;
    
    // Use helper function to safely attach form event listener
    attachFormListener('certifications-form', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        safeFetch('{{ route("tutor.profile.certifications") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Certification added successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error adding certification: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error adding certification: ' + error.message, 'error');
        });
    });
}

function editAvailability() {
    const content = document.getElementById('availability-content');
    
    // Get current availability data from server
    const currentStatus = '{{ $profile && $profile->availability_status ? $profile->availability_status : "available" }}';
    const currentUnavailableUntil = '{{ $profile && $profile->unavailable_until ? $profile->unavailable_until->format("Y-m-d\TH:i") : "" }}';
    
    // Get current hourly availability data - make sure to get the raw data
    const currentHourlyData = @json($profile && $profile->hourly_availability ? $profile->hourly_availability : null);
    console.log('Current hourly data loaded:', currentHourlyData);
    
    content.innerHTML = `
        <form id="availability-form">
            <div class="mb-3">
                <label class="form-label">Availability Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="available" id="available" ${currentStatus === 'available' ? 'checked' : ''}>
                    <label class="form-check-label" for="available">Available Now</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="unavailable" id="unavailable" ${currentStatus === 'unavailable' ? 'checked' : ''}>
                    <label class="form-check-label" for="unavailable">Currently Unavailable</label>
                </div>
            </div>
            <div id="unavailable-until" style="display: ${currentStatus === 'unavailable' ? 'block' : 'none'};">
                <div class="mb-3">
                    <label class="form-label">Available From</label>
                    <input type="datetime-local" class="form-control" name="unavailable_until" value="${currentUnavailableUntil}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Hourly Availability</label>
                <p class="text-muted small">Select the hours when you are available (4:00 AM to 10:00 PM)</p>
                <div class="hourly-schedule">
                    ${getDaysHtml(currentHourlyData)}
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEdit('availability')">Cancel</button>
            </div>
        </form>
    `;
    
    // Show/hide unavailable until field
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const unavailableField = document.getElementById('unavailable-until');
            if(this.value === 'unavailable') {
                unavailableField.style.display = 'block';
            } else {
                unavailableField.style.display = 'none';
            }
        });
    });
    
    // Use helper function to safely attach form event listener
    attachFormListener('availability-form', function(e) {
        e.preventDefault();
        
        // Collect hourly availability data
        const hourlyAvailability = {};
        const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        days.forEach(day => {
            const checkedHours = [];
            const checkboxes = document.querySelectorAll(`input[name="hourly_availability[${day}][]"]:checked`);
            checkboxes.forEach(checkbox => {
                checkedHours.push(parseInt(checkbox.value));
            });
            hourlyAvailability[day] = checkedHours;
        });
        
        console.log('Collected hourly availability:', hourlyAvailability);
        
        const formData = new FormData(this);
        
        // Add hourly availability as individual form fields for each day
        days.forEach(day => {
            const checkboxes = document.querySelectorAll(`input[name="hourly_availability[${day}][]"]:checked`);
            checkboxes.forEach(checkbox => {
                formData.append(`hourly_availability[${day}][]`, checkbox.value);
            });
        });
        
        // Log form data for debugging
        console.log('Form data being sent:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        safeFetch('{{ route("tutor.profile.availability") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                showAlert('Availability updated successfully', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('Error updating availability: ' + (data.error || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating availability: ' + error.message, 'error');
        });
    });
}

function getDaysHtml(currentData = null) {
    const days = [
        { key: 'sunday', label: 'Sunday' },
        { key: 'monday', label: 'Monday' },
        { key: 'tuesday', label: 'Tuesday' },
        { key: 'wednesday', label: 'Wednesday' },
        { key: 'thursday', label: 'Thursday' },
        { key: 'friday', label: 'Friday' },
        { key: 'saturday', label: 'Saturday' }
    ];
    
    const timeSlots = [];
    for (let hour = 4; hour <= 22; hour++) {
        const time = new Date();
        time.setHours(hour, 0, 0, 0);
        timeSlots.push({
            value: hour,
            label: time.toLocaleTimeString('en-US', { hour: 'numeric', hour12: true })
        });
    }
    
    return days.map(day => {
        // Get current availability for this day
        const dayAvailability = getCurrentAvailabilityForDay(day.key, currentData);
        
        return `
            <div class="day-schedule mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">${day.label}</h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllHours('${day.key}')">All</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectBusinessHours('${day.key}')">9-6</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllHours('${day.key}')">None</button>
                    </div>
                </div>
                <div class="time-slots-grid">
                    ${timeSlots.map(slot => `
                        <div class="time-slot-checkbox">
                            <input type="checkbox" 
                                   id="${day.key}_${slot.value}" 
                                   name="hourly_availability[${day.key}][]" 
                                   value="${slot.value}"
                                   ${dayAvailability.includes(slot.value) ? 'checked' : ''}>
                            <label for="${day.key}_${slot.value}">${slot.label}</label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function getCurrentAvailabilityForDay(dayKey, currentData = null) {
    console.log(`Getting availability for ${dayKey}:`, currentData);
    
    // If we have current data from the parameter, use it
    if (currentData && currentData[dayKey]) {
        console.log(`Found data for ${dayKey}:`, currentData[dayKey]);
        return currentData[dayKey];
    }
    
    // Default availability patterns if no data exists
    console.log(`Using default pattern for ${dayKey}`);
    if (dayKey === 'sunday') {
        return [];
    } else if (dayKey === 'saturday') {
        return [10, 11, 12, 13, 14, 15, 16]; // 10 AM to 4 PM
    } else {
        return [9, 10, 11, 12, 13, 14, 15, 16, 17, 18]; // 9 AM to 6 PM
    }
}

// Helper functions for quick time selection
function selectAllHours(dayKey) {
    const checkboxes = document.querySelectorAll(`input[name="hourly_availability[${dayKey}][]"]`);
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function selectBusinessHours(dayKey) {
    const checkboxes = document.querySelectorAll(`input[name="hourly_availability[${dayKey}][]"]`);
    checkboxes.forEach(checkbox => {
        const hour = parseInt(checkbox.value);
        checkbox.checked = (hour >= 9 && hour <= 18); // 9 AM to 6 PM
    });
}

function clearAllHours(dayKey) {
    const checkboxes = document.querySelectorAll(`input[name="hourly_availability[${dayKey}][]"]`);
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

function cancelEdit(section) {
    location.reload();
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove any existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Add new alert at the top of the container
    const container = document.querySelector('.container');
    if (container) {
        container.insertAdjacentHTML('afterbegin', alertHtml);
    }
}

function shareProfile() {
    fetch('{{ route("tutor.profile.share") }}')
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            navigator.clipboard.writeText(data.url);
            showAlert('Profile URL copied to clipboard', 'success');
        }
    });
}

function previewProfile() {
    window.open('{{ route("tutor.profile.preview") }}', '_blank');
}
</script>
