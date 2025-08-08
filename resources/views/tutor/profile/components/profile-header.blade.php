<!-- Profile Header -->
<div class="col-12">
    <div class="profile-header-card mb-4" style="background-color: #ffccb4ff;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="profile-avatar me-3">
                    @if($kyc && $kyc->profile_photo)
                        <img src="{{ Storage::url($kyc->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <h3 class="mb-0 me-2">{{ $tutor->name }}</h3>
                        @if($tutor->status === 'active' && $kyc)
                            <i class="fas fa-check-circle text-primary" title="Verified" style="font-size: 0.8em;"></i>
                        @endif
                    </div>
                    <p class="text-muted mb-1">{{ $tutor->email }}</p>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-{{ $tutor->status === 'active' ? 'success' : 'warning' }} me-2" style="color: #333; background-color: #ffd699; border: 1px solid #ffa500; font-weight: 600;">
                            KYC: {{ ucfirst($tutor->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <style>
                .btn-outline-dark.share-btn {
                    border-color: #000;
                    color: #000;
                }
                .btn-outline-dark.share-btn:hover, .btn-outline-dark.share-btn:focus {
                    background-color: #000;
                    color: #fff;
                }
                .btn-outline-dark.share-btn:hover i, .btn-outline-dark.share-btn:focus i {
                    color: #fff !important;
                }
            </style>
            <div class="profile-actions">
                <button class="btn btn-outline-dark share-btn me-2" onclick="shareProfile()">
                    <i class="fas fa-share-alt me-1" style="color: #000;"></i>Share
                </button>
                <button class="btn btn-dark" style="background-color: #000; border-color: #000;" onclick="previewProfile()">
                    <i class="fas fa-eye me-1" style="color: #fff;"></i>Preview
                </button>
            </div>
        </div>
    </div>
</div>
