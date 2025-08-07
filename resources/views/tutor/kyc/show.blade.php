@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="kyc-form-container py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="kyc-form-card">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">KYC Application</h4>
                            <p class="text-muted mb-0">Your submitted KYC details</p>
                        </div>
                        <div class="card-body">
                            @if(!$kyc)
                                <div class="alert alert-warning text-center py-5">
                                    <i class="fas fa-shield-alt fa-2x mb-3"></i>
                                    <h4 class="mb-3">KYC Verification Required</h4>
                                    <p class="text-muted mb-4">
                                        To start teaching on our platform, you need to complete your KYC verification.<br>
                                        This helps us maintain trust and safety for all users.
                                    </p>
                                    <a href="{{ route('tutor.kyc.create') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus me-2"></i>Start KYC Application
                                    </a>
                                </div>
                            @else
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-{{ $kyc->status === 'approved' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : 'warning') }} px-3 py-2" style="font-size:1rem;">
                                            {{ ucfirst($kyc->status) }}
                                        </span>
                                        @if($kyc->status === 'pending')
                                            @if($kyc->submitted_at)
                                                <span class="text-muted small">Submitted on: {{ $kyc->submitted_at->format('F j, Y \a\t g:i A') }}</span>
                                            @endif
                                        @elseif($kyc->status === 'approved')
                                            @if($kyc->reviewed_at)
                                                <span class="text-muted small">Approved on: {{ $kyc->reviewed_at->format('F j, Y \a\t g:i A') }}</span>
                                            @endif
                                        @elseif($kyc->status === 'rejected')
                                            @if($kyc->reviewed_at)
                                                <span class="text-muted small">Rejected on: {{ $kyc->reviewed_at->format('F j, Y \a\t g:i A') }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($kyc->status === 'pending')
                                        <div class="alert alert-info mt-3">
                                            <i class="fas fa-clock me-2"></i>
                                            Your KYC application is under review. We'll notify you once it's processed.
                                        </div>
                                    @elseif($kyc->status === 'approved')
                                        <div class="alert alert-success mt-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Congratulations! Your KYC application has been approved.
                                        </div>
                                    @elseif($kyc->status === 'rejected')
                                        <div class="alert alert-danger mt-3">
                                            <i class="fas fa-times-circle me-2"></i>
                                            Your KYC application has been rejected.
                                            @if($kyc->rejection_reason)
                                                <br><strong>Reason:</strong> {{ $kyc->rejection_reason }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="form-section">
                                    <h5 class="section-title">Personal Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Full Name</label>
                                                <div class="form-value">{{ $kyc->name }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email Address</label>
                                                <div class="form-value">{{ $kyc->email }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Phone Number</label>
                                                <div class="form-value">{{ $kyc->phone }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Hourly Rate</label>
                                                <div class="form-value">${{ $kyc->hourly_rate }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Exact Location</label>
                                        <div class="form-value">{{ $kyc->exact_location }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description/Bio</label>
                                        <div class="form-value">{{ $kyc->description }}</div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h5 class="section-title">Professional Information</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Highest Qualification</label>
                                        <div class="form-value">{{ $kyc->qualification }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Has Certificate</label>
                                        <div class="form-value">{{ $kyc->has_certificate ? 'Yes' : 'No' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subjects You Expertise In</label>
                                        <div class="form-value">{{ implode(', ', $kyc->subjects_expertise) }}</div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <h5 class="section-title">Uploaded Documents</h5>
                                    <div class="row">
                                        @if($kyc->profile_photo)
                                            <div class="col-md-3 mb-3">
                                                <div class="current-file text-center">
                                                    <img src="{{ Storage::url($kyc->profile_photo) }}" alt="Profile Photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                    <small class="d-block text-muted">Profile Photo</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if($kyc->qualification_proof)
                                            <div class="col-md-3 mb-3">
                                                <div class="current-file text-center">
                                                    <a href="{{ Storage::url($kyc->qualification_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-file-alt me-1"></i>View Qualification
                                                    </a>
                                                    <small class="d-block text-muted">Qualification Proof</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if($kyc->certificate_file)
                                            <div class="col-md-3 mb-3">
                                                <div class="current-file text-center">
                                                    <a href="{{ Storage::url($kyc->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-certificate me-1"></i>View Certificate
                                                    </a>
                                                    <small class="d-block text-muted">Certificate</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if($kyc->citizenship_front)
                                            <div class="col-md-3 mb-3">
                                                <div class="current-file text-center">
                                                    <img src="{{ Storage::url($kyc->citizenship_front) }}" alt="Citizenship Front" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                                    <small class="d-block text-muted">Citizenship (Front)</small>
                                                </div>
                                            </div>
                                        @endif
                                        @if($kyc->citizenship_back)
                                            <div class="col-md-3 mb-3">
                                                <div class="current-file text-center">
                                                    <img src="{{ Storage::url($kyc->citizenship_back) }}" alt="Citizenship Back" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                                                    <small class="d-block text-muted">Citizenship (Back)</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    @if($kyc->status !== 'approved')
                                        <a href="{{ route('tutor.kyc.edit') }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-2"></i>Edit Application
                                        </a>
                                    @endif
                                    <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body, .kyc-form-container {
    background: #fff !important;
    color: #111;
    min-height: 100vh;
}

.kyc-form-card .card {
    border: 1px solid #eee;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    border-radius: 14px;
    background: #fff;
}

.kyc-form-card .card-header {
    background: #ff8800;
    color: #fff;
    border-radius: 14px 14px 0 0;
    padding: 28px 24px 18px 24px;
    border-bottom: 1px solid #ff8800;
    box-shadow: 0 2px 8px rgba(255,136,0,0.08);
    text-align: center;
}

.kyc-form-card .card-header h4 {
    color: #fff;
    font-weight: 700;
    letter-spacing: 1px;
}

.kyc-form-card .card-header p {
    color: #fff;
    opacity: 0.85;
}

.form-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee;
}
.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #111;
    font-weight: 700;
    margin-bottom: 18px;
    padding-bottom: 8px;
    border-bottom: 2px solid #ff8800;
    display: inline-block;
    letter-spacing: 0.5px;
}

.form-label {
    color: #111;
    font-weight: 500;
    margin-bottom: 8px;
}

.form-value {
    color: #333;
    font-weight: 500;
    background: #fafafa;
    border-radius: 8px;
    padding: 10px 14px;
    border: 1px solid #eee;
    margin-bottom: 0;
}

.current-file {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #eee;
}

.btn-primary {
    background: #ff8800;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    color: #fff;
    box-shadow: 0 2px 8px rgba(255,136,0,0.08);
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-primary:hover {
    background: #e67e22;
    color: #fff;
    box-shadow: 0 4px 16px rgba(255,136,0,0.13);
}

.btn-secondary {
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    background: #111;
    color: #fff;
    border: none;
    transition: background 0.2s;
}
.btn-secondary:hover {
    background: #222;
    color: #fff;
}

.badge-success {
    background-color: #27ae60;
    color: white;
    border-radius: 6px;
}
.badge-warning {
    background-color: #f39c12;
    color: white;
    border-radius: 6px;
}
.badge-danger {
    background-color: #e74c3c;
    color: white;
    border-radius: 6px;
}

.alert {
    border-radius: 8px;
    background: #fff;
    color: #111;
    border: 1px solid #ff8800;
}
.alert-warning {
    background: #fff7ed;
    color: #ff8800;
    border: 1px solid #ff8800;
}
.alert-info {
    background: #eaf6fb;
    color: #3498db;
    border: 1px solid #3498db;
}
.alert-success {
    background: #eafaf1;
    color: #27ae60;
    border: 1px solid #27ae60;
}
.alert-danger {
    background: #faeaea;
    color: #e74c3c;
    border: 1px solid #e74c3c;
}

small.text-muted {
    font-size: 0.85em;
    color: #888 !important;
}
</style>
@endsection
