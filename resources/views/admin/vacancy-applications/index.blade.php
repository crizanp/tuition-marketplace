@extends('admin.layouts.app')

@section('title', 'Vacancy Applications')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 text-white"><i class="fas fa-file-alt me-2 text-info"></i>Vacancy Applications</h3>
            <small class="text-muted">Manage tutor applications for student vacancies</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vacancy-applications.export') }}" class="btn btn-dark btn-outline-success">
                <i class="fas fa-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Top stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 h-100">
                <div class="d-flex align-items-start">
                    <div class="me-3 icon-wrap bg-warning text-dark"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="muted">Pending</div>
                        <div class="h5 mb-0">{{ $applications->where('status','pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 h-100">
                <div class="d-flex align-items-start">
                    <div class="me-3 icon-wrap bg-success text-dark"><i class="fas fa-check"></i></div>
                    <div>
                        <div class="muted">Approved</div>
                        <div class="h5 mb-0">{{ $applications->where('status','approved')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 h-100">
                <div class="d-flex align-items-start">
                    <div class="me-3 icon-wrap bg-danger text-white"><i class="fas fa-times"></i></div>
                    <div>
                        <div class="muted">Rejected</div>
                        <div class="h5 mb-0">{{ $applications->where('status','rejected')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card p-3 h-100">
                <div class="d-flex align-items-start">
                    <div class="me-3 icon-wrap bg-info text-white"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="muted">Total</div>
                        <div class="h5 mb-0">{{ $applications->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Export -->
    <div class="card dark-card mb-4">
        <div class="card-body">
            <form method="GET" class="row gy-2 gx-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-dark" placeholder="Search by tutor, vacancy, email...">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted">Status</label>
                    <select name="status" class="form-select form-select-dark">
                        <option value="">All</option>
                        <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Subject</label>
                    <select name="subject" class="form-select form-select-dark">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.vacancy-applications.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk actions for pending -->
    @if(request('status') == 'pending')
    <div class="card dark-card mb-4">
        <div class="card-body d-flex flex-wrap align-items-center gap-3">
            <form action="{{ route('admin.vacancy-applications.bulkUpdate') }}" method="POST" class="d-flex gap-2 align-items-center">
                @csrf
                <select name="action" class="form-select form-select-dark" required>
                    <option value="">Bulk action</option>
                    <option value="approve">Approve selected</option>
                    <option value="reject">Reject selected</option>
                </select>
                <button class="btn btn-warning">Apply</button>
                <div id="selected-applications" style="display:none"></div>
            </form>
            <div class="ms-auto text-muted">Select rows to apply bulk actions</div>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="card dark-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="thead-dark">
                        <tr>
                            @if(request('status') == 'pending')
                                <th style="width:40px"><input type="checkbox" id="select-all"></th>
                            @endif
                            <th>Tutor</th>
                            <th>Vacancy</th>
                            <th>Subject</th>
                            <th>Rate</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                @if(request('status') == 'pending')
                                    <td><input type="checkbox" class="application-checkbox" value="{{ $application->id }}"></td>
                                @endif
                                <td style="min-width:180px">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $application->tutor->name ?? 'N/A' }}</div>
                                            <div class="text-muted small">{{ $application->tutor->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $application->vacancy->title ?? 'N/A' }}</div>
                                    <div class="text-muted small">by {{ $application->vacancy->student->name ?? 'Posted by Admin' }}</div>
                                </td>
                                <td>{{ $application->vacancy->subject ?? 'N/A' }}</td>
                                <td>
                                    @if($application->proposed_rate)
                                        Rs. {{ number_format($application->proposed_rate) }}
                                    @else
                                        <span class="text-muted">To discuss</span>
                                    @endif
                                </td>
                                <td>{{ $application->experience_years }} yrs</td>
                                <td>
                                    @if($application->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($application->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.vacancy-applications.show', $application) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($application->status == 'pending')
                                            <form action="{{ route('admin.vacancy-applications.approve', $application) }}" method="POST" style="display:inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success" onclick="return confirm('Approve this application?')"><i class="fas fa-check"></i></button>
                                            </form>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $application->id }}"><i class="fas fa-times"></i></button>
                                        @endif
                                        <form action="{{ route('admin.vacancy-applications.destroy', $application) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Delete application?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @if($application->status == 'pending')
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-dark text-white border-secondary">
                                            <form action="{{ route('admin.vacancy-applications.reject', $application) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-bottom-0">
                                                    <h5 class="modal-title">Reject Application</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reason{{ $application->id }}" class="form-label">Reason</label>
                                                        <textarea id="reason{{ $application->id }}" name="reason" class="form-control form-control-dark" rows="4" required placeholder="Provide reason for rejection"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <div>No applications found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
        <div class="d-flex justify-content-center">
            {{ $applications->withQueryString()->links() }}
        </div>
    @endif

</div>

@push('styles')
<style>
    :root{
        --dm-bg: #0b0f14;
        --dm-panel: #0f1720;
        --muted: #9aa4af;
        --accent: #ff7a00;
        --border: rgba(255,255,255,0.06);
    }
    .dark-card{ background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border:1px solid var(--border); border-radius:12px; }
    body .stat-card{ background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:10px; border:1px solid var(--border); }
    .muted{ color: var(--muted); }
    .icon-wrap{ width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px }
    .avatar-sm{ width:40px;height:40px;border-radius:50%; }
    .form-control-dark, .form-select-dark{ background: #0b1116; border:1px solid var(--border); color: #e6eef7 }
    .form-control-dark::placeholder{ color: #6f7a82 }
    .table-dark thead.thead-dark th{ background: transparent; border-bottom:1px solid var(--border); color:#e5eef7 }
    .table-dark tbody td{ border-color: rgba(255,255,255,0.03); color:#dbe9f2 }
    .btn-outline-info{ color:#7fd3ff;border-color: rgba(127,211,255,0.12) }
    .btn-outline-info:hover{ background:#07202a }
    .badge{ font-weight:600 }
    .btn-close-white{ filter: invert(1); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.application-checkbox');
    const selectedDiv = document.getElementById('selected-applications');

    function updateSelected(){
        if(!selectedDiv) return;
        const vals = Array.from(checkboxes).filter(cb=>cb.checked).map(cb=>cb.value);
        selectedDiv.innerHTML = vals.map(v=>`<input type="hidden" name="application_ids[]" value="${v}">`).join('');
    }

    if(selectAll){
        selectAll.addEventListener('change', function(){
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelected();
        });
        checkboxes.forEach(cb => cb.addEventListener('change', updateSelected));
    }
});
</script>
@endpush

@endsection
