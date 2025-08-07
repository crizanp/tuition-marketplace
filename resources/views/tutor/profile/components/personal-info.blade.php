<!-- Personal Information Section -->
<div class="profile-section-card mb-4">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('personal')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="personal-content">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <div class="info-display">{{ $tutor->name }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address</label>
                <div class="info-display">{{ $tutor->email }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <div class="info-display">{{ $tutor->phone ?? 'Not provided' }}</div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Hourly Rate</label>
                <div class="info-display">Rs. {{ $tutor->hourly_rate ?? 'Not set' }}</div>
            </div>
        </div>
    </div>
</div>
