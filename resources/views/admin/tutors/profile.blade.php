@extends('admin.layouts.app')

@section('title', 'Tutor Full Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Tutor Full Profile</h3>
            <small class="text-muted">Complete tutor profile data from tutor_profiles table</small>
        </div>
        <div>
            <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-outline-secondary">Back to Summary</a>
            <a href="{{ route('admin.tutors.index') }}" class="btn btn-outline-secondary">Back to Tutors</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div><strong>Name:</strong> {{ $tutor->name ?? 'N/A' }}</div>
                            <div class="mt-2"><strong>Email:</strong> {{ $tutor->email ?? 'N/A' }}</div>
                            <div class="mt-2"><strong>Phone:</strong> {{ $tutor->phone ?? 'N/A' }}</div>
                            <div class="mt-2"><strong>Status:</strong>
                                <span class="badge {{ $tutor->status == 'active' ? 'bg-success' : ($tutor->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($tutor->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Joined:</strong> {{ optional($tutor->created_at)->format('M d, Y') }}</div>
                            <div class="mt-2"><strong>Email Verified:</strong> 
                                @if($tutor->email_verified_at)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </div>
                            <div class="mt-2"><strong>Profile Views:</strong> {{ $tutor->profile->profile_views ?? 0 }}</div>
                            <div class="mt-2"><strong>Featured:</strong> 
                                @if($tutor->profile && $tutor->profile->is_featured)
                                    <span class="badge bg-warning">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            @if($tutor && $tutor->profile)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Profile Details</h5>
                        
                        <div class="mb-4">
                            <h6>Bio</h6>
                            <p class="text-muted">{{ $tutor->profile->bio ?? 'No bio provided.' }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Skills</h6>
                                @php
                                    // gather skills from profile first, then fall back to kyc->subjects_expertise
                                    $skills = [];
                                    // profile skills
                                    if ($tutor->profile && property_exists($tutor->profile, 'skills')) {
                                        $s = $tutor->profile->skills;
                                        if (is_string($s)) {
                                            $s = json_decode($s, true) ?: [];
                                        }
                                        if (is_array($s)) {
                                            $skills = array_merge($skills, $s);
                                        }
                                    }
                                    // kyc subjects_expertise fallback
                                    if (empty($skills) && $tutor->kyc && ($tutor->kyc->subjects_expertise ?? null)) {
                                        $k = $tutor->kyc->subjects_expertise;
                                        if (is_string($k)) {
                                            $k = json_decode($k, true) ?: [];
                                        }
                                        if (is_array($k)) {
                                            $skills = array_merge($skills, $k);
                                        }
                                    }
                                    $skills = array_values(array_filter($skills, function($v){ return is_string($v) && trim($v) !== ''; }));
                                @endphp
                                @if(count($skills) > 0)
                                    <div class="d-flex flex-wrap">
                                        @foreach($skills as $skill)
                                            <span class="badge bg-light text-dark me-1 mb-1">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No skills listed.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Languages</h6>
                                @php
                                    $languages = [];
                                    if ($tutor->profile && property_exists($tutor->profile, 'languages')) {
                                        $l = $tutor->profile->languages;
                                        if (is_string($l)) {
                                            $l = json_decode($l, true) ?: [];
                                        }
                                        if (is_array($l)) {
                                            $languages = array_merge($languages, $l);
                                        }
                                    }
                                    $languages = array_values(array_filter($languages, function($v){ return is_string($v) && trim($v) !== ''; }));
                                @endphp
                                @if(count($languages) > 0)
                                    <div class="d-flex flex-wrap">
                                        @foreach($languages as $language)
                                            @php
                                                $langLabel = null;
                                                if (is_string($language)) {
                                                    $langLabel = $language;
                                                } elseif (is_array($language) || is_object($language)) {
                                                    $lg = (array) $language;
                                                    $name = $lg['name'] ?? ($lg['language'] ?? null);
                                                    $level = $lg['level'] ?? null;
                                                    $langLabel = $name ? ($name . ($level ? ' (' . $level . ')' : '')) : null;
                                                }
                                            @endphp
                                            @if($langLabel)
                                                <span class="badge bg-light text-dark me-1 mb-1">{{ $langLabel }}</span>
                                            @else
                                                <span class="badge bg-light text-dark me-1 mb-1">Unknown</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No languages listed.</p>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6>Education</h6>
                            @php
                                $education = [];
                                if ($tutor->profile && property_exists($tutor->profile, 'education')) {
                                    $e = $tutor->profile->education;
                                    if (is_string($e)) {
                                        $e = json_decode($e, true) ?: [];
                                    }
                                    if (is_array($e)) {
                                        $education = array_merge($education, $e);
                                    }
                                }
                                // fallback: kyc->qualification (string) and qualification_proof (file)
                                if (empty($education) && $tutor->kyc) {
                                    if (!empty($tutor->kyc->qualification) && is_string($tutor->kyc->qualification)) {
                                        $education[] = $tutor->kyc->qualification;
                                    }
                                    if (!empty($tutor->kyc->qualification_proof) && is_string($tutor->kyc->qualification_proof)) {
                                        $education[] = 'Qualification proof uploaded';
                                    }
                                }
                                $education = array_values(array_filter($education, function($v){ return is_string($v) && trim($v) !== ''; }));
                            @endphp
                                @if(count($education) > 0)
                                <ul class="list-unstyled">
                                    @foreach($education as $edu)
                                        <li class="mb-1">• {{ is_string($edu) ? $edu : (is_array($edu) ? implode(' - ', array_filter([$edu['qualification'] ?? null, $edu['institution'] ?? null])) : 'Invalid education entry') }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{-- If education is stored as an associative object (qualification, institution), show it --}}
                                @php
                                    $eduObj = null;
                                    if ($tutor->profile && property_exists($tutor->profile, 'education')) {
                                        $e = $tutor->profile->education;
                                        if (is_array($e) && array_key_exists('qualification', $e)) {
                                            $eduObj = $e;
                                        }
                                    }
                                @endphp
                                @if($eduObj)
                                    <ul class="list-unstyled">
                                        <li class="mb-1">• {{ $eduObj['qualification'] ?? 'N/A' }} @if(!empty($eduObj['institution'])) - <small class="text-muted">{{ $eduObj['institution'] }}</small>@endif</li>
                                    </ul>
                                @else
                                    <p class="text-muted">No education information provided.</p>
                                @endif
                            @endif
                        </div>

                        @if($tutor->profile && $tutor->profile->introduction_video)
                            <div class="mb-4">
                                <h6>Introduction Video</h6>
                                <video width="400" controls>
                                    <source src="{{ asset('storage/' . $tutor->profile->introduction_video) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h6>Portfolio Items</h6>
                            @php
                                $portfolioItems = null;
                                if ($tutor->profile && property_exists($tutor->profile, 'portfolio_items')) {
                                    $portfolioItems = $tutor->profile->portfolio_items;
                                }
                                if (is_string($portfolioItems)) {
                                    $portfolioItems = json_decode($portfolioItems, true) ?: [];
                                }
                                $portfolioItems = is_array($portfolioItems) ? $portfolioItems : [];
                            @endphp
                            @if(count($portfolioItems) > 0)
                                <div class="row">
                                    @foreach($portfolioItems as $item)
                                        @if(is_array($item))
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    @if(isset($item['image']) && is_string($item['image']))
                                                        <img src="{{ asset('storage/' . $item['image']) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                    @endif
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ is_string($item['title'] ?? null) ? $item['title'] : 'Portfolio Item' }}</h6>
                                                        <p class="card-text small">{{ is_string($item['description'] ?? null) ? $item['description'] : '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $kycFiles = [];
                                    if ($tutor->kyc) {
                                        if (!empty($tutor->kyc->qualification_proof)) $kycFiles['Qualification Proof'] = $tutor->kyc->qualification_proof;
                                        if (!empty($tutor->kyc->certificate_file)) $kycFiles['Certificate'] = $tutor->kyc->certificate_file;
                                    }
                                @endphp
                                @if(count($kycFiles) > 0)
                                    <div class="d-flex flex-column">
                                        @foreach($kycFiles as $label => $path)
                                            <div class="mb-2">
                                                <div class="fw-bold">{{ $label }}</div>
                                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No portfolio items added.</p>
                                @endif
                            @endif
                        </div>

                        <div class="mb-4">
                            <h6>Additional Certifications</h6>
                            @php
                                $certifications = null;
                                if ($tutor->profile && property_exists($tutor->profile, 'additional_certifications')) {
                                    $certifications = $tutor->profile->additional_certifications;
                                }
                                if (is_string($certifications)) {
                                    $certifications = json_decode($certifications, true) ?: [];
                                }
                                $certifications = is_array($certifications) ? $certifications : [];
                            @endphp
                            @if(count($certifications) > 0)
                                <ul class="list-unstyled">
                                    @foreach($certifications as $cert)
                                        @if(is_array($cert))
                                            <li class="mb-2">
                                                <div class="fw-bold">{{ is_string($cert['name'] ?? null) ? $cert['name'] : 'Certificate' }}</div>
                                                @if(isset($cert['issuer']) && is_string($cert['issuer']))
                                                    <div class="text-muted small">Issued by: {{ $cert['issuer'] }}</div>
                                                @endif
                                                @if(isset($cert['date']) && is_string($cert['date']))
                                                    <div class="text-muted small">Date: {{ $cert['date'] }}</div>
                                                @endif
                                                @if(isset($cert['file']) && is_string($cert['file']))
                                                    <a href="{{ asset('storage/' . $cert['file']) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">View Certificate</a>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No additional certifications.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Availability -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Availability</h5>
                        
                        <div class="mb-3">
                            <strong>Status:</strong> 
                            <span class="badge {{ ($tutor->profile && $tutor->profile->availability_status == 'available') ? 'bg-success' : 'bg-danger' }}">
                                {{ $tutor->profile && $tutor->profile->availability_status ? ucfirst($tutor->profile->availability_status) : 'Unknown' }}
                            </span>
                        </div>

                        @if($tutor->profile && $tutor->profile->unavailable_until)
                            <div class="mb-3">
                                <strong>Unavailable Until:</strong> {{ $tutor->profile->unavailable_until->format('M d, Y') }}
                            </div>
                        @endif

                        @php
                            $availabilitySchedule = null;
                            if ($tutor->profile && property_exists($tutor->profile, 'availability_schedule')) {
                                $availabilitySchedule = $tutor->profile->availability_schedule;
                            }
                            if (is_string($availabilitySchedule)) {
                                $availabilitySchedule = json_decode($availabilitySchedule, true) ?: [];
                            }
                            $availabilitySchedule = is_array($availabilitySchedule) ? $availabilitySchedule : [];
                        @endphp
                        @if(count($availabilitySchedule) > 0)
                            <div class="mb-3">
                                <strong>Schedule:</strong>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            @endphp
                                            @foreach($days as $day)
                                                @php $dayKey = strtolower($day); @endphp
                                                <tr>
                                                    <td>{{ $day }}</td>
                                                    @if(isset($availabilitySchedule[$dayKey]) && is_array($availabilitySchedule[$dayKey]))
                                                        @php $schedule = $availabilitySchedule[$dayKey]; @endphp
                                                        <td>{{ is_string($schedule['start'] ?? null) ? $schedule['start'] : '-' }}</td>
                                                        <td>{{ is_string($schedule['end'] ?? null) ? $schedule['end'] : '-' }}</td>
                                                        <td>
                                                            @if($schedule['off'] ?? false)
                                                                <span class="badge bg-secondary">Off</span>
                                                            @else
                                                                <span class="badge bg-success">Available</span>
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td><span class="badge bg-secondary">Not set</span></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Profile Details</h5>
                        <div class="text-muted">No profile data available. Tutor has not completed profile setup.</div>
                    </div>
                </div>
            @endif

            <!-- Statistics -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Statistics</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-0">{{ $tutor->jobs->count() }}</div>
                                <div class="text-muted small">Jobs Posted</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-0">{{ $tutor->ratings->count() }}</div>
                                <div class="text-muted small">Total Ratings</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @php $avgRating = $tutor->ratings->count() > 0 ? $tutor->ratings->avg('rating') : 0; @endphp
                                <div class="h4 mb-0">{{ number_format($avgRating, 1) }}</div>
                                <div class="text-muted small">Avg Rating</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h4 mb-0">{{ $tutor && $tutor->profile ? ($tutor->profile->profile_views ?? 0) : 0 }}</div>
                                <div class="text-muted small">Profile Views</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.tutors.show', $tutor->id) }}" class="btn btn-outline-primary">View Summary</a>
                        <a href="{{ route('admin.jobs.index', ['tutor' => $tutor->id]) }}" class="btn btn-outline-secondary">View All Jobs</a>
                        @if($tutor->kyc)
                            <a href="{{ route('admin.kyc.show', $tutor->kyc->id) }}" class="btn btn-outline-warning">View KYC</a>
                        @endif
                        <a href="mailto:{{ $tutor->email }}" class="btn btn-outline-info">Send Email</a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Recent Activity</h6>
                    <div class="timeline">
                        @if($tutor->jobs->count() > 0)
                            @foreach($tutor->jobs->take(3) as $job)
                                <div class="timeline-item mb-2">
                                    <div class="small fw-bold">Posted Job</div>
                                    <div class="text-muted small">{{ $job->title }}</div>
                                    <div class="text-muted tiny">{{ $job->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($tutor->ratings->count() > 0)
                            @foreach($tutor->ratings->take(2) as $rating)
                                <div class="timeline-item mb-2">
                                    <div class="small fw-bold">Received Rating</div>
                                    <div class="text-muted small">{{ $rating->rating }}/5 stars</div>
                                    <div class="text-muted tiny">{{ $rating->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
