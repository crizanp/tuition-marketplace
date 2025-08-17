@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tutor Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.tutors.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Tutors</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tutors.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.tutors.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if(request('status') == 'pending')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Bulk Actions for Pending Tutors</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tutors.bulkUpdate') }}" method="POST">
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
                <div id="selected-tutors"></div>
            </form>
        </div>
    </div>
    @endif

    <!-- Tutors Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tutors List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @if(request('status') == 'pending')
                            <th><input type="checkbox" id="select-all"></th>
                            @endif
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>KYC</th>
                            <th>Jobs</th>
                            <th>Rating</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tutors as $tutor)
                        <tr>
                            @if(request('status') == 'pending')
                            <td><input type="checkbox" class="tutor-checkbox" value="{{ $tutor->id }}"></td>
                            @endif
                            <td>{{ $tutor->id }}</td>
                            <td>{{ $tutor->name }}</td>
                            <td>{{ $tutor->email }}</td>
                            <td>{{ $tutor->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $tutor->status == 'active' ? 'success' : ($tutor->status == 'pending' ? 'warning' : ($tutor->status == 'suspended' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($tutor->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                @if($tutor->kyc)
                                <span class="badge badge-{{ $tutor->kyc->status == 'approved' ? 'success' : ($tutor->kyc->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($tutor->kyc->status) }}
                                </span>
                                @else
                                <span class="badge badge-secondary">Not Submitted</span>
                                @endif
                            </td>
                            <td>{{ $tutor->jobs_count ?? $tutor->tutorJobs->count() }}</td>
                            <td>
                                @if($tutor->average_rating)
                                {{ number_format($tutor->average_rating, 1) }}/5
                                @else
                                No ratings
                                @endif
                            </td>
                            <td>{{ $tutor->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tutors.show', $tutor) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($tutor->status == 'pending')
                                    <form action="{{ route('admin.tutors.approve', $tutor) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this tutor?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tutors.status', $tutor) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this tutor?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @elseif($tutor->status == 'active')
                                    <form action="{{ route('admin.tutors.status', $tutor) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="suspended">
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to suspend this tutor?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                    @elseif($tutor->status == 'suspended')
                                    <form action="{{ route('admin.tutors.status', $tutor) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.tutors.destroy', $tutor) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this tutor? This action cannot be undone.')">
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
                {{ $tutors->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.tutor-checkbox');
    const selectedTutorsDiv = document.getElementById('selected-tutors');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedTutors();
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedTutors);
    });

    function updateSelectedTutors() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        selectedTutorsDiv.innerHTML = selected.map(id => 
            `<input type="hidden" name="tutor_ids[]" value="${id}">`
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
