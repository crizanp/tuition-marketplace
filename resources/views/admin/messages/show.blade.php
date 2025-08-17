@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Message #{{ $message->id }}</h1>
        <div>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">Back to Messages</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h5>From: {{ $message->name }} &lt;{{ $message->email }}&gt;</h5>
            @if($message->phone)
                <p>Phone: {{ $message->phone }}</p>
            @endif

            @if($message->student)
                <p>Student: <a href="#" onclick="showStudentProfile({{ $message->student->id }})" class="text-primary">{{ $message->student->name }}</a> &lt;{{ $message->student->email }}&gt;</p>
            @endif

            @if($message->tutor)
                <p>Tutor: <a href="#" onclick="showTutorProfile({{ $message->tutor->id }})" class="text-primary">{{ $message->tutor->name }}</a></p>
            @endif

            @if($message->job)
                <p>Related Job: <a href="{{ route('admin.jobs.show', $message->job->id) }}">{{ $message->job->title ?? 'Job #' . $message->job->id }}</a></p>
            @endif

            <p><strong>Subject:</strong> {{ $message->subject }}</p>
            <hr>
            <p>{{ $message->message }}</p>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">Admin Response</div>
        <div class="card-body">
            @if($message->admin_response)
                <div class="alert alert-info">
                    <h6>Responded at: {{ $message->responded_at->format('M d, Y H:i') }}</h6>
                    <p>{{ $message->admin_response }}</p>
                </div>
            @endif

            <form action="{{ route('admin.messages.respond', $message->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="response">Response</label>
                    <textarea name="response" id="response" rows="6" class="form-control" required></textarea>
                </div>
                <button class="btn btn-primary mt-3">Send Response</button>
            </form>
        </div>
    </div>
</div>

<!-- Student Profile Modal -->
<div class="modal fade" id="studentProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-dark text-white" style="border-radius:6px;">
            <div class="modal-header border-0">
                <h5 class="modal-title">Student Profile Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="studentProfileContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer border-0 bg-transparent">
                <a href="#" id="openStudentAdminPage" class="btn btn-outline-light btn-sm" target="_blank">Open Admin Page</a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Tutor Profile Modal -->
<div class="modal fade" id="tutorProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-dark text-white" style="border-radius:6px;">
            <div class="modal-header border-0">
                <h5 class="modal-title">Tutor Profile Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="tutorProfileContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer border-0 bg-transparent">
                <a href="#" id="openTutorAdminPage" class="btn btn-outline-light btn-sm" target="_blank">Open Admin Page</a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showStudentProfile(studentId) {
    fetch(`/admin/students/${studentId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Student data received:', data); // Debug log
            const profilePicture = data.profile_picture ? 
                `<img src="${data.profile_picture}" style="width:100%;height:100%;object-fit:cover;"/>` :
                `<i class="fas fa-user text-secondary" style="font-size:32px;"></i>`;
                
            const content = `
                <div class="text-center mb-3">
                    <div style="width:80px;height:80px;overflow:hidden;border-radius:50%;background:#333;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                        ${profilePicture}
                    </div>
                    <h5 class="mt-2 text-white">${data.name}</h5>
                    <p class="text-muted">${data.email}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info mb-3">Basic Information</h6>
                        <p><strong>Phone:</strong> ${data.phone || 'Not provided'}</p>
                        <p><strong>Grade Level:</strong> ${data.grade_level || 'Not specified'}</p>
                        <p><strong>Institution:</strong> ${data.institution || 'Not provided'}</p>
                        <p><strong>Qualification:</strong> ${data.qualification || 'Not provided'}</p>
                        <p><strong>WhatsApp:</strong> ${data.whatsapp || 'Not provided'}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${data.status === 'active' ? 'success' : 'warning'}">${data.status}</span></p>
                        ${data.status_reason ? `<p><strong>Status Reason:</strong> ${data.status_reason}</p>` : ''}
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-info mb-3">Location & Activity</h6>
                        <p><strong>District:</strong> ${data.location_district || 'Not provided'}</p>
                        <p><strong>Place:</strong> ${data.location_place || 'Not provided'}</p>
                        <p><strong>Profile Completion:</strong> ${data.profile_completion}%</p>
                        <p><strong>Vacancies Posted:</strong> ${data.vacancies_count}</p>
                        <p><strong>Ratings Given:</strong> ${data.ratings_count}</p>
                        <p><strong>Joined:</strong> ${data.created_at}</p>
                        <p><strong>Email Verified:</strong> ${data.email_verified_at || 'Not verified'}</p>
                    </div>
                </div>
                
                ${data.bio ? `
                    <div class="mt-3">
                        <h6 class="text-info">Bio</h6>
                        <p class="text-light">${data.bio}</p>
                    </div>
                ` : ''}
                
                ${data.preferred_subjects && data.preferred_subjects.length ? `
                    <div class="mt-3">
                        <h6 class="text-info">Preferred Subjects</h6>
                        <div class="d-flex flex-wrap">
                            ${data.preferred_subjects.map(subject => `<span class="badge badge-secondary mr-1 mb-1">${subject}</span>`).join('')}
                        </div>
                    </div>
                ` : ''}
            `;
            
            document.getElementById('studentProfileContent').innerHTML = content;
            document.getElementById('openStudentAdminPage').href = `/admin/students/${studentId}`;
            $('#studentProfileModal').modal('show');
        })
        .catch(error => {
            console.error('Error loading student profile:', error);
            document.getElementById('studentProfileContent').innerHTML = '<p class="text-danger">Error loading profile</p>';
            $('#studentProfileModal').modal('show');
        });
}

function showTutorProfile(tutorId) {
    fetch(`/admin/tutors/${tutorId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Tutor data received:', data); // Debug log
            const profilePicture = data.profile_picture ? 
                `<img src="${data.profile_picture}" style="width:100%;height:100%;object-fit:cover;"/>` :
                `<i class="fas fa-chalkboard-teacher text-secondary" style="font-size:32px;"></i>`;
                
            const content = `
                <div class="text-center mb-3">
                    <div style="width:80px;height:80px;overflow:hidden;border-radius:50%;background:#333;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                        ${profilePicture}
                    </div>
                    <h5 class="mt-2 text-white">${data.name}</h5>
                    <p class="text-muted">${data.email}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-info mb-3">Basic Information</h6>
                        <p><strong>Phone:</strong> ${data.phone || 'Not provided'}</p>
                        <p><strong>Hourly Rate:</strong> ${data.hourly_rate ? '$' + data.hourly_rate : 'Not set'}</p>
                        <p><strong>Status:</strong> <span class="badge badge-${data.status === 'active' ? 'success' : 'warning'}">${data.status}</span></p>
                        ${data.status_reason ? `<p><strong>Status Reason:</strong> ${data.status_reason}</p>` : ''}
                        <p><strong>Jobs Posted:</strong> ${data.jobs_count}</p>
                        <p><strong>Total Ratings:</strong> ${data.ratings_count}</p>
                        ${data.average_rating ? `<p><strong>Average Rating:</strong> ${parseFloat(data.average_rating).toFixed(1)}/5</p>` : ''}
                        <p><strong>Joined:</strong> ${data.created_at}</p>
                        <p><strong>Email Verified:</strong> ${data.email_verified_at || 'Not verified'}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-info mb-3">KYC & Verification</h6>
                        ${data.kyc ? `
                            <p><strong>KYC Status:</strong> <span class="badge badge-${data.kyc.status === 'approved' ? 'success' : 'warning'}">${data.kyc.status}</span></p>
                            <p><strong>Document Type:</strong> ${data.kyc.document_type || 'Not provided'}</p>
                            <p><strong>Document Number:</strong> ${data.kyc.document_number || 'Not provided'}</p>
                            ${data.kyc.verified_at ? `<p><strong>Verified At:</strong> ${data.kyc.verified_at}</p>` : ''}
                        ` : '<p class="text-warning">No KYC information available</p>'}
                        
                        ${data.profile ? `
                            <h6 class="text-info mt-3">Profile Stats</h6>
                            <p><strong>Profile Completion:</strong> ${data.profile.completion_percentage}%</p>
                            <p><strong>Total Students:</strong> ${data.profile.total_students || 0}</p>
                            <p><strong>Total Hours:</strong> ${data.profile.total_hours || 0}</p>
                        ` : ''}
                    </div>
                </div>
                
                ${data.bio ? `
                    <div class="mt-3">
                        <h6 class="text-info">Bio</h6>
                        <p class="text-light">${data.bio}</p>
                    </div>
                ` : ''}
                
                ${data.profile && data.profile.bio ? `
                    <div class="mt-3">
                        <h6 class="text-info">Profile Bio</h6>
                        <p class="text-light">${data.profile.bio}</p>
                    </div>
                ` : ''}
                
                ${data.subjects && data.subjects.length ? `
                    <div class="mt-3">
                        <h6 class="text-info">Teaching Subjects</h6>
                        <div class="d-flex flex-wrap">
                            ${data.subjects.map(subject => `<span class="badge badge-primary mr-1 mb-1">${subject}</span>`).join('')}
                        </div>
                    </div>
                ` : ''}
                
                ${data.profile && data.profile.skills && data.profile.skills.length ? `
                    <div class="mt-3">
                        <h6 class="text-info">Skills</h6>
                        <div class="d-flex flex-wrap">
                            ${data.profile.skills.map(skill => `<span class="badge badge-secondary mr-1 mb-1">${skill}</span>`).join('')}
                        </div>
                    </div>
                ` : ''}
                
                ${data.profile && data.profile.languages && data.profile.languages.length ? `
                    <div class="mt-3">
                        <h6 class="text-info">Languages</h6>
                        <div class="d-flex flex-wrap">
                            ${data.profile.languages.map(lang => `<span class="badge badge-info mr-1 mb-1">${lang}</span>`).join('')}
                        </div>
                    </div>
                ` : ''}
            `;
            
            document.getElementById('tutorProfileContent').innerHTML = content;
            document.getElementById('openTutorAdminPage').href = `/admin/tutors/${tutorId}`;
            $('#tutorProfileModal').modal('show');
        })
        .catch(error => {
            console.error('Error loading tutor profile:', error);
            document.getElementById('tutorProfileContent').innerHTML = '<p class="text-danger">Error loading profile</p>';
            $('#tutorProfileModal').modal('show');
        });
}
</script>
@endsection
