<!-- Certifications Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-certificate me-2"></i>Certifications</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('certifications')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="certifications-content">
        <div class="certifications-list">
            @if($kyc && $kyc->certificate_file)
                <div class="certification-item">
                    <i class="fas fa-certificate me-2"></i>
                    <span>Teaching Certificate</span>
                    <a href="{{ Storage::url($kyc->certificate_file) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-auto">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @endif
            @if($profile && $profile->additional_certifications)
                @foreach($profile->additional_certifications as $cert)
                    <div class="certification-item">
                        <i class="fas fa-certificate me-2"></i>
                        <div class="flex-grow-1">
                            <span>{{ $cert['name'] }}</span>
                            @if(isset($cert['issuer']))
                                <small class="text-muted d-block">{{ $cert['issuer'] }}</small>
                            @endif
                        </div>
                        @if(isset($cert['file']))
                            <a href="{{ Storage::url($cert['file']) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-auto">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            @endif
            @if((!$kyc || !$kyc->certificate_file) && (!$profile || !$profile->additional_certifications))
                <div class="text-muted">No certifications added yet</div>
            @endif
        </div>
    </div>
</div>
