@extends('admin.layouts.app')

@section('content')
<style>
    .student-header { color: #fff; }
    .profile-key { color: var(--muted); font-weight:600; }
    .profile-value { color: #fff; }
    .skill-badge { background: rgba(255,255,255,0.02); color: var(--muted); border:1px solid rgba(255,255,255,0.03); margin-right:6px; margin-bottom:6px; display:inline-block; padding:4px 8px; border-radius:8px; }
    .detail-row { border-bottom: 1px solid rgba(255,255,255,0.03); padding:0.75rem 0; }
    .card-body .small-muted { color: var(--muted); }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 student-header">Student Details</h1>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Back to Students</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if(!empty($student->profile_picture))
                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $student->name }}" class="rounded-circle me-3" style="width:96px;height:96px;object-fit:cover;border:3px solid var(--accent);">
                        @else
                            <div class="admin-avatar me-3" style="width:96px;height:96px;font-size:28px;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                                {{ strtoupper(substr($student->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <h5 class="card-title mb-0">{{ $student->name }}</h5>
                            <div class="small-muted">{{ $student->grade_level ? ucfirst($student->grade_level) : 'Student' }}</div>
                        </div>
                    </div>
                    {{-- Role and verification --}}
                    <div class="detail-row">
                        <span class="profile-key">Role:</span>
                        <div class="profile-value">Student
                            @if(!empty($student->email_verified_at) || !empty($student->verified) || !empty($student->is_verified))
                                <span class="badge" style="background:var(--accent);color:#000;margin-left:8px;">Verified</span>
                            @else
                                <span class="badge badge-status" style="margin-left:8px;">Unverified</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="profile-key">Email:</span>
                        <div class="profile-value">{{ $student->email ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Phone:</span>
                        <div class="profile-value">{{ $student->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Status:</span>
                        <div class="profile-value">
                            <span class="badge badge-status">{{ ucfirst($student->status ?? 'active') }}</span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Joined:</span>
                        <div class="profile-value">{{ optional($student->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                    </div>

                    {{-- Additional profile info --}}
                    <div class="detail-row">
                        <span class="profile-key">Qualification:</span>
                        <div class="profile-value">{{ $student->qualification ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Education:</span>
                        <div class="profile-value">{{ $student->education ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Location:</span>
                        @php
                            // Support multiple naming conventions used across the app
                            $locationParts = [
                                $student->address ?? null,
                                $student->city ?? null,
                                $student->country ?? null,
                                $student->location_district ?? null,
                                $student->location_place ?? null,
                                $student->location_landmark ?? null,
                            ];
                            $location = trim(collect($locationParts)->filter()->join(', '));
                        @endphp
                        <div class="profile-value">{{ $location ?: 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Bio:</span>
                        <div class="profile-value small-muted">{{ $student->bio ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Skills:</span>
                        <div class="profile-value">
                            @php
                                $skills = [];
                                if(is_array($student->skills ?? null)) { $skills = $student->skills; }
                                elseif(!empty($student->skills)) { $skills = preg_split('/\s*,\s*/', $student->skills); }
                            @endphp

                            @if(!empty($skills))
                                @foreach($skills as $skill)
                                    <span class="skill-badge">{{ $skill }}</span>
                                @endforeach
                            @else
                                <span class="profile-value">N/A</span>
                            @endif
                        </div>
                    </div>

                    {{-- Institution, WhatsApp, Preferred Subjects, Member Since --}}
                    <div class="detail-row">
                        <span class="profile-key">Institution:</span>
                        <div class="profile-value">{{ $student->institution ?? $student->education_institution ?? $student->education ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">WhatsApp:</span>
                        <div class="profile-value">{{ $student->whatsapp ?? $student->whatsapp_number ?? $student->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Preferred Subjects:</span>
                        <div class="profile-value">
                            @php
                                $preferred_raw = $student->preferred_subjects ?? $student->preferred_subjects_raw ?? $student->preferred_subjects_list ?? null;
                                $preferred_items = [];
                                if(is_array($preferred_raw)) { $preferred_items = $preferred_raw; }
                                elseif(!empty($preferred_raw)) {
                                    if(strpos($preferred_raw, ',') !== false) {
                                        $preferred_items = preg_split('/\s*,\s*/', $preferred_raw);
                                    } else {
                                        // fallback: keep the raw string as a single item
                                        $preferred_items = [$preferred_raw];
                                    }
                                }
                            @endphp

                            @if(!empty($preferred_items))
                                @foreach($preferred_items as $sub)
                                    <span class="skill-badge">{{ $sub }}</span>
                                @endforeach
                            @else
                                <span class="profile-value">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="profile-key">Member Since:</span>
                        <div class="profile-value">{{ optional($student->created_at)->format('F Y') ?? ($student->member_since ?? 'N/A') }}</div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-flex gap-2">
                            @if (Route::has('admin.students.edit'))
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">Edit</a>
                            @else
                                <button class="btn btn-primary btn-sm" disabled>Edit</button>
                            @endif

                            @if (Route::has('admin.students.status'))
                                <form action="{{ route('admin.students.status', $student) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $student->status === 'suspended' ? 'active' : 'suspended' }}">
                                    <button type="submit" class="btn btn-sm {{ $student->status === 'suspended' ? 'btn-success' : 'btn-warning' }}">
                                        {{ $student->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-warning" disabled>{{ $student->status === 'suspended' ? 'Activate' : 'Suspend' }}</button>
                            @endif
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Vacancies ({{ $student->vacancies ? $student->vacancies->count() : 0 }})</div>
                <div class="card-body">
                    @if($student->vacancies && $student->vacancies->count())
                        <div class="accordion" id="vacancyAccordion">
                            @foreach($student->vacancies as $vacancy)
                                <div class="card mb-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="vacancyHeading{{ $vacancy->id }}">
                                        <div>
                                            <strong>{{ $vacancy->title }}</strong>
                                            <div class="text-muted small">{{ $vacancy->subject }} — {{ $vacancy->grade_level }}</div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ optional($vacancy->created_at)->format('M d, Y') }}</small>
                                            <div class="mt-1">
                                                <a href="{{ route('admin.vacancies.show', $vacancy) ?? '#' }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @if($vacancy->status === 'approved')
                                                    <span class="badge bg-success ms-2">Approved</span>
                                                @elseif($vacancy->status === 'pending')
                                                    <span class="badge bg-warning ms-2">Pending</span>
                                                @elseif($vacancy->status === 'rejected')
                                                    <span class="badge bg-danger ms-2">Rejected</span>
                                                @elseif($vacancy->status === 'completed' || $vacancy->status === 'filled')
                                                    <span class="badge bg-secondary ms-2">Filled</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <p class="text-muted small">{{ Str::limit($vacancy->description, 220) }}</p>

                                        {{-- Applications for this vacancy --}}
                                        <div class="mt-3">
                                            <h6 class="mb-2">Applications ({{ $vacancy->applications ? $vacancy->applications->count() : 0 }})</h6>
                                            @if($vacancy->applications && $vacancy->applications->count())
                                                <div class="list-group">
                                                    @foreach($vacancy->applications as $application)
                                                        <div class="list-group-item">
                                                            <div class="d-flex justify-content-between">
                                                                <div>
                                                                    @if($application->tutor)
                                                                        <a href="{{ route('admin.tutors.show', $application->tutor->id) }}" class="fw-bold">{{ $application->tutor->name }}</a>
                                                                        <div class="small text-muted">Tutor profile</div>
                                                                    @else
                                                                        <div class="fw-bold text-muted">Tutor account removed</div>
                                                                    @endif
                                                                    <div class="small text-muted mt-1">Applied: {{ optional($application->applied_at)->diffForHumans() }}</div>
                                                                </div>
                                                                <div class="text-end">
                                                                    @if($application->proposed_rate)
                                                                        <div><strong>Rate:</strong> Rs. {{ number_format($application->proposed_rate, 2) }}</div>
                                                                    @endif
                                                                    <div class="small text-muted">Experience: {{ $application->experience_years ?? '—' }} yr(s)</div>
                                                                    <div class="mt-2">
                                                                        @if($application->status === 'approved')
                                                                            <span class="badge bg-success">Approved</span>
                                                                        @elseif($application->status === 'pending')
                                                                            <span class="badge bg-warning">Pending</span>
                                                                        @elseif($application->status === 'rejected')
                                                                            <span class="badge bg-danger">Rejected</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(!empty($application->cover_letter))
                                                                <div class="mt-2 small text-muted">Message: {{ Str::limit($application->cover_letter, 300) }}</div>
                                                            @endif
                                                            <div class="mt-2 text-end">
                                                                <a href="{{ route('admin.vacancy-applications.show', $application) ?? '#' }}" class="btn btn-sm btn-outline-primary">View Application</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-muted">No applications yet.</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mb-0">No vacancies posted by this student.</p>
                    @endif
                </div>
            </div>
            
            {{-- Ratings given by this student --}}
            <div class="card mb-4">
                <div class="card-header">Ratings Given ({{ $student->ratings ? $student->ratings->count() : 0 }})</div>
                <div class="card-body">
                    @if($student->ratings && $student->ratings->count())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tutor</th>
                                        <th>Job</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->ratings as $rating)
                                        <tr>
                                            <td>
                                                @if($rating->tutor)
                                                    <a href="{{ route('admin.tutors.show', $rating->tutor->id) }}" class="fw-bold">{{ $rating->tutor->name }}</a>
                                                    <div class="small text-muted">Tutor profile</div>
                                                @else
                                                    <div class="text-muted">Tutor removed</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rating->job)
                                                    <div class="fw-bold">{{ $rating->job->title }}</div>
                                                    <div class="small text-muted">{{ is_array($rating->job->subjects) ? implode(', ', array_slice($rating->job->subjects,0,2)) : ($rating->job->subjects ?? '') }}</div>
                                                @else
                                                    <div class="text-muted">Job removed</div>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-primary">{{ $rating->rating }}/5</span></td>
                                            <td class="small">{{ $rating->review ?? '—' }}</td>
                                            <td class="small text-muted">{{ optional($rating->created_at)->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">No ratings given by this student.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
