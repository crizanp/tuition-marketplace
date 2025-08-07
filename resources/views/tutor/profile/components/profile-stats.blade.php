<!-- Quick Stats -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-chart-bar me-2"></i>Profile Stats</h5>
    </div>
    <div class="section-content">
        @php
            $completionPercentage = 0;
            if ($profile) {
                $completionPercentage = $profile->getCompletionPercentage();
            }
            // Fallback calculation if profile doesn't exist
            if ($completionPercentage == 0) {
                $fields = [
                    'basic_info' => !empty($tutor->name) && !empty($tutor->email),
                    'phone' => !empty($tutor->phone),
                    'hourly_rate' => !empty($tutor->hourly_rate),
                    'bio' => !empty($tutor->bio),
                    'kyc_approved' => $kyc && $kyc->status === 'approved',
                ];
                $completed = array_filter($fields);
                $completionPercentage = round((count($completed) / count($fields)) * 100);
            }
        @endphp
        <div class="stat-item">
            <span class="stat-value">{{ $completionPercentage }}%</span>
            <span class="stat-label">Profile Complete</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $profile ? $profile->total_students : 0 }}</span>
            <span class="stat-label">Students Taught</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $profile ? $profile->total_hours : 0 }}</span>
            <span class="stat-label">Hours Completed</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $profile ? $profile->profile_views : 0 }}</span>
            <span class="stat-label">Profile Views</span>
        </div>
    </div>
</div>
