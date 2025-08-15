@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Vacancy Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.vacancies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Post Vacancy
            </a>
            <a href="{{ route('admin.vacancies.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Vacancies</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vacancies.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="subject" class="form-control">
                            <option value="">All Subjects</option>
                            <option value="Math" {{ request('subject') == 'Math' ? 'selected' : '' }}>Math</option>
                            <option value="English" {{ request('subject') == 'English' ? 'selected' : '' }}>English</option>
                            <option value="Science" {{ request('subject') == 'Science' ? 'selected' : '' }}>Science</option>
                            <option value="Physics" {{ request('subject') == 'Physics' ? 'selected' : '' }}>Physics</option>
                            <option value="Chemistry" {{ request('subject') == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                            <option value="Biology" {{ request('subject') == 'Biology' ? 'selected' : '' }}>Biology</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or location" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.vacancies.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions for Pending Vacancies -->
    @if(request('status') == 'pending' || !request('status'))
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Bulk Actions</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vacancies.bulkUpdate') }}" method="POST">
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
                <div id="selected-vacancies"></div>
            </form>
        </div>
    </div>
    @endif

    <!-- Vacancies Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student Vacancies List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @if(request('status') == 'pending' || !request('status'))
                            <th><input type="checkbox" id="select-all"></th>
                            @endif
                            <th>ID</th>
                            <th>Title</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Budget</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacancies as $vacancy)
                        <tr>
                            @if(request('status') == 'pending' || !request('status'))
                            <td><input type="checkbox" class="vacancy-checkbox" value="{{ $vacancy->id }}"></td>
                            @endif
                            <td>{{ $vacancy->id }}</td>
                            <td>{{ $vacancy->title }}</td>
                            <td>{{ $vacancy->student->name ?? 'N/A' }}</td>
                            <td>{{ $vacancy->subject }}</td>
                            <td>${{ number_format($vacancy->budget_min, 2) }} - ${{ number_format($vacancy->budget_max, 2) }}/hr</td>
                            <td>
                                @if($vacancy->location_type === 'online')
                                    Online
                                @else
                                    {{ $vacancy->address ?? 'N/A' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $vacancy->status == 'approved' ? 'success' : ($vacancy->status == 'pending' ? 'warning' : ($vacancy->status == 'rejected' ? 'danger' : 'info')) }}">
                                    {{ ucfirst($vacancy->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>{{ $vacancy->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.vacancies.show', $vacancy) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($vacancy->status, ['pending', 'rejected']))
                                    {{-- Show approve button for pending and rejected vacancies so an admin can reverse a rejection --}}
                                    <form action="{{ route('admin.vacancies.approve', $vacancy) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this vacancy?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @if($vacancy->status == 'pending')
                                    {{-- Only show reject button when currently pending --}}
                                    <form action="{{ route('admin.vacancies.reject', $vacancy) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this vacancy?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @elseif($vacancy->status == 'approved')
                                    <form action="{{ route('admin.vacancies.status', $vacancy) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="filled">
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Mark this vacancy as filled?')">
                                            <i class="fas fa-flag"></i>
                                        </button>
                                    </form>
                                    @elseif($vacancy->status == 'completed')
                                    <form action="{{ route('admin.vacancies.status', $vacancy) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Reopen this vacancy (set to pending)?')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.vacancies.destroy', $vacancy) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vacancy? This action cannot be undone.')">
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
                {{ $vacancies->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.vacancy-checkbox');
    const selectedVacanciesDiv = document.getElementById('selected-vacancies');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedVacancies();
        });
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedVacancies);
    });

    function updateSelectedVacancies() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        selectedVacanciesDiv.innerHTML = selected.map(id => 
            `<input type="hidden" name="vacancy_ids[]" value="${id}">`
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
