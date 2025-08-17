@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.students.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Students</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.students.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Vacancies</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $student->status == 'active' ? 'success' : ($student->status == 'suspended' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($student->status ?? 'active') }}
                                </span>
                            </td>
                            <td>{{ $student->vacancies_count ?? ($student->studentVacancies ? $student->studentVacancies->count() : 0) }}</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($student->status !== 'suspended')
                                    <!-- Suspend button opens modal to collect reason -->
                                    <button type="button" class="btn btn-warning btn-sm btn-suspend" data-id="{{ $student->id }}" data-name="{{ $student->name }}" title="Suspend">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @else
                                    <form action="{{ route('admin.students.status', $student) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="btn btn-success btn-sm" title="Reactivate">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
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
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

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

@section('modals')
<!-- Suspend Student Modal (dark themed) -->
<style>
    /* Modal dark theme matching admin panel */
    .admin-modal { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border:1px solid var(--border); color:#fff; }
    .admin-modal .modal-header, .admin-modal .modal-footer { border-color: rgba(255,255,255,0.03); }
    .admin-modal .modal-title { color: #fff; }
    .admin-modal .form-label { color: var(--muted); }
    .admin-modal .form-control { background: #0a0a0b; color: #fff; border: 1px solid #222; }
    .admin-modal .btn-secondary { background: transparent; border: 1px solid rgba(255,255,255,0.06); color: var(--muted); }
    .admin-modal .btn-danger { background: #c0392b; border: none; color: #fff; }
    .btn-close-white { filter: invert(1) grayscale(1) brightness(2); }
</style>

<div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="suspendForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="suspended">
            <div class="modal-content admin-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="suspendModalLabel">Suspend Student</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="suspendModalStudent"></p>
                    <div class="mb-3">
                        <label for="suspendReason" class="form-label">Reason (required)</label>
                        <textarea name="reason" id="suspendReason" class="form-control" rows="3" placeholder="Enter reason for suspension" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Suspend</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('suspendModal'));
        document.querySelectorAll('.btn-suspend').forEach(btn => {
                btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-id');
                        const name = btn.getAttribute('data-name');
                        const form = document.getElementById('suspendForm');
                        form.action = '/admin/students/' + id + '/status';
                        document.getElementById('suspendModalStudent').textContent = 'Suspend ' + name + '? This action can be reverted.';
                        document.getElementById('suspendReason').value = '';
                        modal.show();
                });
        });
});
</script>
@endsection
