@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>KYC Application Details</h2>
                <a href="{{ route('admin.kyc.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            <div class="row">
                <!-- KYC Status Card -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Application Status</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-2">
                                <span class="badge badge-{{ $kyc->status === 'approved' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : 'warning') }} fs-6">{{ ucfirst($kyc->status) }}</span>
                            </div>

                            <p class="mb-2"><strong>Submitted:</strong> {{ $kyc->submitted_at->format('F j, Y \a\t g:i A') }}</p>

                            @if($kyc->reviewed_at)
                                <p class="mb-3"><strong>Reviewed:</strong> {{ $kyc->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
                            @endif

                            {{-- Action buttons: pending => both; approved => show Reject; rejected => show Approve --}}
                            <div class="action-buttons">
                                @if($kyc->status === 'pending')
                                    <button type="button" class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @elseif($kyc->status === 'approved')
                                    {{-- Show reject option for an already approved application --}}
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @elseif($kyc->status === 'rejected')
                                    {{-- Provide approve action to re-approve rejected applications --}}
                                    <form method="POST" action="{{ route('admin.kyc.approve', $kyc->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mb-2">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($kyc->status === 'rejected' && $kyc->rejection_reason)
                                <div class="alert alert-danger mt-3">
                                    <strong>Rejection Reason:</strong><br>
                                    {{ $kyc->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KYC Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Application Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label>Full Name</label>
                                        <p>{{ $kyc->name }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Email</label>
                                        <p>{{ $kyc->email }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Phone</label>
                                        <p>{{ $kyc->phone }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Hourly Rate</label>
                                        <p>${{ $kyc->hourly_rate }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-group">
                                        <label>Qualification</label>
                                        <p>{{ $kyc->qualification }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Has Certificate</label>
                                        <p>{{ $kyc->has_certificate ? 'Yes' : 'No' }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Location</label>
                                        <p>{{ $kyc->exact_location }}</p>
                                    </div>
                                    <div class="detail-group">
                                        <label>Subjects</label>
                                        <p>{{ implode(', ', $kyc->subjects_expertise) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-group">
                                <label>Description</label>
                                <p>{{ $kyc->description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Uploaded Documents</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($kyc->profile_photo)
                                    <div class="col-md-3 mb-3">
                                        <div class="document-item">
                                            <img src="{{ Storage::url($kyc->profile_photo) }}" alt="Profile Photo" class="img-thumbnail">
                                            <small>Profile Photo</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($kyc->citizenship_front)
                                    <div class="col-md-3 mb-3">
                                        <div class="document-item">
                                            <img src="{{ Storage::url($kyc->citizenship_front) }}" alt="Citizenship Front" class="img-thumbnail">
                                            <small>Citizenship (Front)</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($kyc->citizenship_back)
                                    <div class="col-md-3 mb-3">
                                        <div class="document-item">
                                            <img src="{{ Storage::url($kyc->citizenship_back) }}" alt="Citizenship Back" class="img-thumbnail">
                                            <small>Citizenship (Back)</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($kyc->qualification_proof)
                                    <div class="col-md-3 mb-3">
                                        <div class="document-item">
                                            <a href="{{ Storage::url($kyc->qualification_proof) }}" target="_blank" class="file-link">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <small>Qualification Proof</small>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($kyc->certificate_file)
                                    <div class="col-md-3 mb-3">
                                        <div class="document-item">
                                            <a href="{{ Storage::url($kyc->certificate_file) }}" target="_blank" class="file-link">
                                                <i class="fas fa-certificate"></i>
                                            </a>
                                            <small>Certificate</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve KYC Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this KYC application?</p>
                <p class="text-muted">This action will activate the tutor's account and allow them to start teaching.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.kyc.approve', $kyc->id) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject KYC Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.kyc.reject', $kyc->id) }}">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this KYC application:</p>
                    <textarea class="form-control" name="rejection_reason" rows="4" required 
                              placeholder="Enter the reason for rejection..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.detail-group {
    margin-bottom: 15px;
    color:white;
}

.detail-group label {
    display: block;
    font-weight: 600;
    color: #bec5cbff;
    font-size: 12px;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.detail-group p {
    margin: 0;
    color: #fefeffff;
    font-weight: 500;
}

.document-item {
    text-align: center;
}

.document-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

.file-link {
    display: block;
    width: 100%;
    height: 120px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #6c757d;
    font-size: 30px;
}

.file-link:hover {
    background: #e9ecef;
    color: #495057;
}

.document-item small {
    display: block;
    margin-top: 8px;
    color: #6c757d;
    font-size: 11px;
}

.badge-success {
    background-color: #27ae60 !important;
}

.badge-warning {
    background-color: #f39c12 !important;
}

.badge-danger {
    background-color: #e74c3c !important;
}

/* Modal header styling for approve/reject modals */
#approveModal .modal-header,
#rejectModal .modal-header {
    background: linear-gradient(90deg, #111827 0%, #0b1220 100%);
    border-bottom: 1px solid rgba(255,255,255,0.04);
    padding: 1rem 1.25rem;
}

#approveModal .modal-title,
#rejectModal .modal-title {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.05rem;
}

/* Ensure close button is visible on dark header */
#approveModal .btn-close,
#rejectModal .btn-close {
    filter: invert(1) hue-rotate(180deg) brightness(1.2);
    opacity: 0.95;
}

/* Match modal content to dark theme */
#approveModal .modal-content,
#rejectModal .modal-content {
    background: #071018;
    color: #e6eef6;
    border: 1px solid rgba(255,255,255,0.03);
}
</style>
@endsection
