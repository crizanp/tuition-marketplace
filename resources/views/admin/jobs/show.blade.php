@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Detail</h1>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Back to list</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $job->title }}</h6>
        </div>
        <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> {{ $job->id }}</p>
                        <p><strong>Tutor:</strong> {{ optional($job->tutor)->name ?? 'N/A' }}
                            @if($job->tutor)
                                &nbsp;|&nbsp;<a href="{{ route('admin.tutors.show', $job->tutor->id) }}" class="btn btn-sm btn-outline-primary">View Tutor</a>
                            @endif
                        </p>
                        @php
                            $subjects = $job->subjects;
                            if (is_string($subjects)) {
                                $decoded = json_decode($subjects, true);
                                $subjects = is_array($decoded) ? $decoded : [];
                            }
                            $subjects = $subjects ?? [];

                            $preferred = $job->preferred_times;
                            if (is_string($preferred)) {
                                $decoded = json_decode($preferred, true);
                                $preferred = is_array($decoded) ? $decoded : [];
                            }
                            $preferred = $preferred ?? [];

                            $gallery = $job->gallery;
                            if (is_string($gallery)) {
                                $decoded = json_decode($gallery, true);
                                $gallery = is_array($decoded) ? $decoded : [];
                            }
                            $gallery = $gallery ?? [];
                        @endphp
                        <p><strong>Subjects:</strong> {{ count($subjects) ? implode(', ', $subjects) : '—' }}</p>
                        <p><strong>Hourly Rate:</strong>
                            @if(isset($job->hourly_rate) || $job->hourly_rate === 0)
                                ${{ number_format((float)$job->hourly_rate, 2) }}/hr
                            @else
                                —
                            @endif
                        </p>
                        <p><strong>Status:</strong>
                            <span class="badge badge-{{ $job->status == 'active' ? 'success' : ($job->status == 'expired' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($job->status ?? 'active') }}
                            </span>
                        </p>
                        <p><strong>Featured:</strong>
                            @if($job->is_featured)
                                <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                            @else
                                <span class="badge badge-light">Regular</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Location:</strong> {{ $job->place ?? '—' }}, {{ $job->district ?? '—' }}, {{ $job->state ?? '—' }}</p>
                        <p><strong>Landmark:</strong> {{ $job->landmark ?? '—' }}</p>
                        <p><strong>Ward No:</strong> {{ $job->ward_no ?? '—' }}</p>
                        <p><strong>Preferred Times:</strong> {{ count($preferred) ? implode(', ', $preferred) : '—' }}</p>
                        <p><strong>Gender Preference:</strong> {{ $job->gender_preference ?? 'Any' }}</p>
                        <p><strong>Student Level:</strong> {{ $job->student_level ?? '—' }}</p>
                        <p><strong>Max Students:</strong> {{ $job->max_students ?? '—' }}</p>
                        <p><strong>Views:</strong> {{ $job->views ?? 0 }}</p>
                        <p><strong>Created:</strong> {{ optional($job->created_at)->format('M d, Y') ?? '—' }}</p>
                    </div>
                </div>

            <hr />

            <h5>Description</h5>
            <p>{{ $job->description }}</p>

            <hr />
            <h5>Gallery</h5>
            <div class="mb-3">
                @if(count($gallery))
                    <div class="d-flex flex-wrap">
                        @foreach($gallery as $img)
                            <div class="me-2 mb-2">
                                <img src="{{ Storage::url($img) }}" alt="img" style="width:120px;height:80px;object-fit:cover;border-radius:4px;" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>—</p>
                @endif
            </div>

            <h5>Requirements</h5>
            <p>{{ $job->requirements ?? '—' }}</p>

            <h5>Admin Notes</h5>
            <p>{{ $job->admin_notes ?? '—' }}</p>

            <!-- Admin Actions -->
            <div class="mt-4">
                <form action="{{ route('admin.jobs.toggleFeatured', $job) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button class="btn btn-{{ $job->is_featured ? 'secondary' : 'warning' }} btn-sm">
                        {!! $job->is_featured ? '<i class="fas fa-star-half-alt"></i> Unfeature' : '<i class="fas fa-star"></i> Feature' !!}
                    </button>
                </form>

                <form action="{{ route('admin.jobs.status', $job) }}" method="POST" style="display:inline-block; margin-left:8px;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $job->status == 'active' ? 'inactive' : 'active' }}">
                    <input type="hidden" name="reason" value="">
                    <button class="btn btn-{{ $job->status == 'active' ? 'secondary' : 'success' }} btn-sm">
                        {!! $job->status == 'active' ? '<i class="fas fa-pause"></i> Deactivate' : '<i class="fas fa-play"></i> Activate' !!}
                    </button>
                </form>

                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" style="display:inline-block; margin-left:8px;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this job?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>

            <!-- Admin Notes Edit -->
            <div class="mt-3">
                <form action="{{ route('admin.jobs.status', $job) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $job->status ?? 'active' }}">
                    <div class="mb-2">
                        <label for="reason" class="form-label">Admin Notes / Reason</label>
                        <textarea name="reason" id="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
                    </div>
                    <button class="btn btn-primary">Save Notes</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
