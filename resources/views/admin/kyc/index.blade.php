@extends('admin.layouts.app')

@section('content')
<div class="container py-5" style="color:#e9eef6;">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>KYC Applications Management</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Filters -->
         <div class="card mb-4 bg-dark text-light" style="border:1px solid rgba(255,255,255,0.04)">
          <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                <select class="form-select bg-black text-light border-secondary" name="status" id="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control bg-black text-light border-secondary" name="search" id="search" 
                    placeholder="Search by name or email" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KYC Applications Table -->
            <div class="card bg-dark text-light" style="border:1px solid rgba(255,255,255,0.04)">
                <div class="card-body">
                    @if($kycApplications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-dark">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tutor Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kycApplications as $kyc)
                                        <tr>
                                            <td>{{ $kyc->id }}</td>
                                            <td>{{ $kyc->name }}</td>
                                            <td>{{ $kyc->email }}</td>
                                            <td>{{ $kyc->phone }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = $kyc->status === 'approved' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : 'warning');
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }} text-dark">
                                                    {{ ucfirst($kyc->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $kyc->submitted_at ? $kyc->submitted_at->format('M j, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.kyc.show', $kyc->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $kycApplications->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5 text-light">
                            <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                            <h5 class="text-light">No KYC applications found</h5>
                            <p class="text-light">KYC applications will appear here when tutors submit them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
