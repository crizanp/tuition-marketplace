<!-- Profile Header -->
<div class="col-12">
    <div class="profile-header-card mb-4">
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
                        <span class="badge badge-{{ $tutor->status === 'active' ? 'success' : 'warning' }} me-2">
                            {{ ucfirst($tutor->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn btn-outline-primary me-2" onclick="shareProfile()">
                    <i class="fas fa-share-alt me-1"></i>Share
                </button>
                <button class="btn btn-primary" onclick="previewProfile()">
                    <i class="fas fa-eye me-1"></i>Preview
                </button>
            </div>
        </div>
    </div>
</div>
