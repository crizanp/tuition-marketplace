@extends('layouts.admin')

@section('title', 'Vacancy Applications Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt me-2"></i>Vacancy Applications Management
        </h1>
        <div>
            <a href="{{ route('admin.vacancy-applications.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applications->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applications->where('status', 'approved')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applications->where('status', 'rejected')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applications->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search applications...">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <select class="form-control" id="subject" name="subject">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                {{ $subject }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.vacancy-applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if(request('status') == 'pending')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Bulk Actions for Pending Applications</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vacancy-applications.bulkUpdate') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <select name="action" class="form-control" required>
                            <option value="">Select Action</option>
                            <option value="approve">Approve Selected</option>
                            <option value="reject">Reject Selected</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning">Apply to Selected</button>
                    </div>
                </div>
                <div id="selected-applications"></div>
            </form>
        </div>
    </div>
    @endif

    <!-- Applications Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Applications List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @if(request('status') == 'pending')
                                <th><input type="checkbox" id="select-all"></th>
                            @endif
                            <th>Tutor</th>
                            <th>Vacancy</th>
                            <th>Subject</th>
                            <th>Proposed Rate</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                @if(request('status') == 'pending')
                                    <td>
                                        <input type="checkbox" class="application-checkbox" 
                                               name="application_ids[]" value="{{ $application->id }}">
                                    </td>
                                @endif
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-user-graduate text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $application->tutor->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $application->tutor->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $application->vacancy->title ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">by {{ $application->vacancy->student->name ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $application->vacancy->subject ?? 'N/A' }}</td>
                                <td>
                                    @if($application->proposed_rate)
                                        Rs. {{ number_format($application->proposed_rate) }}
                                    @else
                                        <span class="text-muted">To discuss</span>
                                    @endif
                                </td>
                                <td>{{ $application->experience_years }} years</td>
                                <td>
                                    @if($application->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.vacancy-applications.show', $application) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($application->status == 'pending')
                                        <form action="{{ route('admin.vacancy-applications.approve', $application) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this application?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $application->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <form action="{{ route('admin.vacancy-applications.destroy', $application) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to delete this application?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Reject Modal -->
                            @if($application->status == 'pending')
                            <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.vacancy-applications.reject', $application) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Application</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="reason{{ $application->id }}" class="form-label">Reason for rejection</label>
                                                    <textarea class="form-control" id="reason{{ $application->id }}" name="reason" 
                                                              rows="3" required placeholder="Provide a reason for rejecting this application..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Reject Application</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ request('status') == 'pending' ? '9' : '8' }}" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">No applications found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const selectedApplicationsDiv = document.getElementById('selected-applications');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            applicationCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedApplications();
        });

        applicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedApplications);
        });
    }

    function updateSelectedApplications() {
        const selected = Array.from(applicationCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        selectedApplicationsDiv.innerHTML = selected
            .map(id => `<input type="hidden" name="application_ids[]" value="${id}">`)
            .join('');
    }
});
</script>
@endsection
