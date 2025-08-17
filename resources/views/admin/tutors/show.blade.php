@extends('admin.layouts.app')

@section('title', 'Tutor Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Tutor Profile</h3>
            <small class="text-muted">Full tutor details, KYC, jobs and applications</small>
        </div>
        <div>
            <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline-secondary">Back to Tutors</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex gap-3">
                    @php $tutor = $tutor ?? null; @endphp
                    <div>
                        @if($tutor && $tutor->kyc && $tutor->kyc->profile_photo)
                            <img src="{{ asset('storage/' . $tutor->kyc->profile_photo) }}" alt="avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:100px;height:100px;font-size:30px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $tutor->name ?? 'N/A' }}</h4>
                        <div class="text-muted">{{ $tutor->email ?? 'N/A' }} @if($tutor && $tutor->email_verified_at) <span class="badge bg-success ms-2">Verified</span> @endif</div>
                        <div class="mt-2">
                            <small class="text-muted">Joined {{ optional($tutor->created_at)->format('M Y') }}</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline-primary btn-sm">Manage Tutor</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="mb-2">KYC / Identity</h6>
                            @if($tutor && $tutor->kyc)
                                <div><strong>Status:</strong>
                                    <span class="badge {{ $tutor->kyc->status == 'approved' ? 'bg-success' : ($tutor->kyc->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($tutor->kyc->status) }}</span>
                                </div>
                                   <div class="mt-2"><strong>Full Name (on ID):</strong> {{ $tutor->kyc->full_name ?? ($tutor->name ?? 'N/A') }}</div>
                                <div class="mt-2"><strong>DOB:</strong> {{ optional($tutor->kyc->dob)->format('M d, Y') ?? ($tutor->dob ?? 'N/A') }}</div>
                                <div class="mt-2"><strong>Submitted:</strong> {{ optional($tutor->kyc->created_at)->format('M d, Y') }}</div>

                                {{-- KYC files --}}
                                <div class="mt-3">
                                    <strong>Documents</strong>
                                    <div class="d-flex flex-column mt-2">
                                        @php
                                            $files = [];
                                            if($tutor->kyc->profile_photo ?? false) $files['Profile Photo'] = $tutor->kyc->profile_photo;
                                            if($tutor->kyc->citizenship_front ?? false) $files['Citizenship Front'] = $tutor->kyc->citizenship_front;
                                            if($tutor->kyc->citizenship_back ?? false) $files['Citizenship Back'] = $tutor->kyc->citizenship_back;
                                            if($tutor->kyc->qualification_proof ?? false) $files['Qualification Proof'] = $tutor->kyc->qualification_proof;
                                            if($tutor->kyc->certificate_file ?? false) $files['Certificate'] = $tutor->kyc->certificate_file;
                                        @endphp

                                        @if(count($files))
                                            @foreach($files as $label => $path)
                                                @if(is_array($path))
                                                    @foreach($path as $p)
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <div class="small text-truncate" style="max-width:65%">{{ $label }}</div>
                                                            <div>
                                                                <a href="{{ asset('storage/' . $p) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">View</a>
                                                                <a href="{{ asset('storage/' . $p) }}" download class="btn btn-sm btn-outline-secondary">Download</a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div class="small text-truncate" style="max-width:65%">{{ $label }}</div>
                                                        <div>
                                                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">View</a>
                                                            <a href="{{ asset('storage/' . $path) }}" download class="btn btn-sm btn-outline-secondary">Download</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="text-muted">No documents uploaded.</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    {{-- Link to admin KYC show (use admin.kyc routes) --}}
                                    <a href="{{ route('admin.kyc.show', $tutor->kyc->id) }}" class="btn btn-sm btn-outline-warning">View KYC</a>
                                    @if($tutor->kyc->status != 'approved')
                                        <form action="{{ route('admin.kyc.approve', $tutor->kyc->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Mark Approved</button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted">No KYC record found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="mb-2">Profile & Activity</h6>
                            <div><strong>Jobs Posted:</strong> {{ $tutor ? $tutor->jobs->count() : 0 }}</div>
                            <div class="mt-2"><strong>Applications:</strong> {{ $tutor ? $tutor->vacancyApplications->count() : 0 }}</div>
                            <div class="mt-2"><strong>Ratings:</strong> {{ $tutor ? $tutor->ratings->count() : 0 }}
                                @if($tutor && $tutor->ratings->count() > 0)
                                    @php $avgRating = $tutor->ratings->avg('rating'); @endphp
                                    <span class="badge bg-warning text-dark ms-2">{{ number_format($avgRating, 1) }}/5</span>
                                @endif
                            </div>

                            <hr>
                            <h6 class="mb-2">Full Profile</h6>
                            @php $profile = $tutor->profile ?? null; @endphp
                            <div><strong>Bio:</strong></div>
                            <div class="text-muted mb-2">{{ $profile->bio ?? 'N/A' }}</div>

                            <div><strong>Education:</strong></div>
                            <div class="text-muted mb-2">@if($profile && ($profile->education ?? false)) @php $edu = is_array($profile->education) ? implode(', ', $profile->education) : $profile->education; @endphp {{ $edu }} @else N/A @endif</div>

                            <div><strong>Skills:</strong></div>
                            <div class="text-muted mb-2">@if($profile && ($profile->skills ?? false)) @php $skills = is_array($profile->skills) ? implode(', ', $profile->skills) : $profile->skills; @endphp {{ $skills }} @else N/A @endif</div>

                            <div><strong>Qualification:</strong></div>
                            <div class="text-muted mb-2">
                                @if($tutor->kyc && $tutor->kyc->qualification)
                                    {{ $tutor->kyc->qualification }}
                                @elseif($profile && ($profile->education ?? false))
                                    @php $edu = is_array($profile->education) ? implode(', ', $profile->education) : $profile->education; @endphp 
                                    {{ $edu }}
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('admin.tutors.profile', $tutor->id) }}" class="btn btn-sm btn-outline-primary">View Full Profile</a>
                                <a href="{{ route('admin.jobs.index', ['tutor' => $tutor->id]) }}" class="btn btn-sm btn-outline-secondary">View Jobs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Jobs Posted</h6>
                    @if($tutor && $tutor->jobs->count())
                        <ul class="list-group list-group-flush">
                            @foreach($tutor->jobs as $job)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $job->title }}</div>
                                        <div class="text-muted small">Posted {{ optional($job->created_at)->diffForHumans() }}</div>
                                    </div>
                                    <a href="{{ route('tutor.jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">No jobs posted.</div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Ratings & Reviews</h6>
                        @if($tutor && $tutor->ratings->count())
                            <div class="text-end">
                                @php
                                    $avgRating = $tutor->ratings->avg('rating');
                                    $totalRatings = $tutor->ratings->count();
                                @endphp
                                <div class="fw-bold">{{ number_format($avgRating, 1) }}/5</div>
                                <div class="small text-muted">{{ $totalRatings }} rating{{ $totalRatings != 1 ? 's' : '' }}</div>
                            </div>
                        @endif
                    </div>
                    @if($tutor && $tutor->ratings->count())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Job</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tutor->ratings as $rating)
                                        <tr>
                                            <td>
                                                @if($rating->user)
                                                    <div class="fw-bold">{{ $rating->user->name }}</div>
                                                    <div class="small text-muted">{{ $rating->user->email }}</div>
                                                @else
                                                    <div class="text-muted">Student account removed</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rating->job)
                                                    <div class="fw-bold">{{ $rating->job->title }}</div>
                                                    <div class="small text-muted">
                                                        @if($rating->job->subjects && is_array($rating->job->subjects))
                                                            {{ implode(', ', array_slice($rating->job->subjects, 0, 2)) }}
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="text-muted">Job removed</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $rating->rating }}/5</span>
                                            </td>
                                            <td class="small text-wrap" style="max-width:300px">{{ $rating->review ?? '-' }}</td>
                                            <td class="small text-muted">{{ optional($rating->created_at)->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.tutors.ratings.destroy', [$tutor->id, $rating->id]) }}" method="POST" onsubmit="return confirm('Delete this rating?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">No ratings yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Contact</h6>
                    <div><strong>Phone:</strong> {{ $tutor->phone ?? 'N/A' }}</div>
                    <div class="mt-2"><strong>Location:</strong> 
                        @if($tutor->kyc && $tutor->kyc->exact_location)
                            {{ $tutor->kyc->exact_location }}
                        @elseif($tutor->profile && $tutor->profile->city)
                            {{ $tutor->profile->city }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="mt-3">
                        @if($tutor && $tutor->email)
                            <a href="mailto:{{ $tutor->email }}" class="btn btn-outline-info w-100">Email Tutor</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2">Recent Applications</h6>
                    @if($tutor && $tutor->vacancyApplications->count())
                        <ul class="list-unstyled small">
                            @foreach($tutor->vacancyApplications->take(5) as $app)
                                <li class="mb-2">
                                    <div class="fw-bold">{{ optional($app->vacancy)->title ?? 'Vacancy removed' }}</div>
                                    <div class="text-muted">Applied {{ optional($app->applied_at)->diffForHumans() }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">No recent applications.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
