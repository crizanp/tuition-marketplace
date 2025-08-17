@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Job Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.jobs.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Jobs</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs.index') }}">
                <div class="row">
                    @if(!empty($selectedTutor))
                        <input type="hidden" name="tutor" value="{{ $selectedTutor }}">
                    @endif
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="featured" class="form-control">
                            <option value="">All Jobs</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                            <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Non-Featured</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or subject" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Bulk Actions</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.jobs.bulkUpdate') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <select name="action" class="form-control" required>
                            <option value="">Select Action</option>
                            <option value="featured">Make Featured</option>
                            <option value="unfeatured">Remove Featured</option>
                            <option value="active">Activate</option>
                            <option value="inactive">Deactivate</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning">Apply to Selected</button>
                    </div>
                </div>
                <div id="selected-jobs"></div>
            </form>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Jobs List
                @if(!empty($selectedTutor))
                    <small class="text-muted"> — Showing jobs for tutor #{{ $selectedTutor }}</small>
                @endif
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Tutor</th>
                            <th>Subject</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                        <tr>
                            <td><input type="checkbox" class="job-checkbox" value="{{ $job->id }}"></td>
                            <td>{{ $job->id }}</td>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->tutor->name ?? 'N/A' }}</td>
                            @php
                                // Normalize subjects (handle array, null or JSON string stored)
                                $subjects = $job->subjects;
                                if (is_string($subjects)) {
                                    $decoded = json_decode($subjects, true);
                                    $subjects = is_array($decoded) ? $decoded : [];
                                }
                                $subjects = $subjects ?? [];
                            @endphp
                            <td>{{ count($subjects) ? implode(', ', array_slice($subjects, 0, 3)) : '—' }}</td>
                            <td>
                                @if(isset($job->hourly_rate) || $job->hourly_rate === 0)
                                    ${{ number_format((float)$job->hourly_rate, 2) }}/hr
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $job->status == 'active' ? 'success' : ($job->status == 'expired' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($job->status ?? 'active') }}
                                </span>
                            </td>
                            <td>
                                @if($job->is_featured)
                                <span class="badge badge-warning">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                                @else
                                <span class="badge badge-light">Regular</span>
                                @endif
                            </td>
                            <td>{{ $job->views ?? 0 }}</td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.jobs.toggleFeatured', $job) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @if($job->is_featured)
                                        <button type="submit" class="btn btn-outline-warning btn-sm" title="Remove Featured">
                                            <i class="fas fa-star-half-alt"></i>
                                        </button>
                                        @else
                                        <button type="submit" class="btn btn-warning btn-sm" title="Make Featured">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        @endif
                                    </form>
                                    @if($job->status == 'active')
                                    <form action="{{ route('admin.jobs.status', $job) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="inactive">
                                        <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to deactivate this job?')">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.jobs.status', $job) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.job-checkbox');
    const selectedJobsDiv = document.getElementById('selected-jobs');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedJobs();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedJobs);
    });

    function updateSelectedJobs() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        selectedJobsDiv.innerHTML = selected.map(id => 
            `<input type="hidden" name="job_ids[]" value="${id}">`
        ).join('');
    }
});
</script>

@if(session('success'))
<script>
    $(document).ready(function() {
        toastr.success('{{ session('success') }}');
    });
</script>
@endif

@if(session('error'))
<script>
    $(document).ready(function() {
        toastr.error('{{ session('error') }}');
    });
</script>
@endif
@endsection
