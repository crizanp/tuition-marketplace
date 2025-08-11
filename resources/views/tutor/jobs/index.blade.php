@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">My Job Posts</h3>
                    <a href="{{ route('tutor.jobs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Post New Job
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Job Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Jobs</h6>
                                        <h4 class="mb-0">{{ $jobs->total() }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-briefcase fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Active Jobs</h6>
                                        <h4 class="mb-0">{{ $jobs->where('status', 'active')->count() }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Views</h6>
                                        <h4 class="mb-0">{{ $jobs->sum('views') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-eye fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Inquiries</h6>
                                        <h4 class="mb-0">{{ $jobs->sum('inquiries') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-envelope fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jobs List -->
                <div class="card">
                    <div class="card-body">
                        @if($jobs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Subjects</th>
                                            <th>Location</th>
                                            <th>Rate</th>
                                            <th>Status</th>
                                            <th>Views</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jobs as $job)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $job->title }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $job->teaching_mode_label }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($job->subjects)
                                                        @foreach(array_slice($job->subjects, 0, 2) as $subject)
                                                            <span class="badge bg-secondary me-1">{{ $subject }}</span>
                                                        @endforeach
                                                        @if(count($job->subjects) > 2)
                                                            <span class="badge bg-light text-dark">+{{ count($job->subjects) - 2 }} more</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ $job->place }}, {{ $job->district }}<br>
                                                        {{ $job->state }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong>${{ number_format($job->hourly_rate, 2) }}/hr</strong>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $job->status_badge_class }}">
                                                        {{ ucfirst($job->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $job->views }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('tutor.jobs.show', $job) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('tutor.jobs.edit', $job) }}" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-{{ $job->status === 'active' ? 'secondary' : 'success' }}"
                                                                onclick="toggleJobStatus({{ $job->id }})"
                                                                title="{{ $job->status === 'active' ? 'Pause' : 'Activate' }}">
                                                            <i class="fas fa-{{ $job->status === 'active' ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete({{ $job->id }})"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $jobs->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No jobs posted yet</h5>
                                <p class="text-muted">Start by posting your first job to attract students!</p>
                                <a href="{{ route('tutor.jobs.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Post Your First Job
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleJobStatus(jobId) {
    fetch(`/tutor/jobs/${jobId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating job status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating job status');
    });
}

function confirmDelete(jobId) {
    const form = document.getElementById('deleteForm');
    form.action = `/tutor/jobs/${jobId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
