<style>
/* Profile Page Styles */
.profile-container {
    background-color: #ffffffff;
    min-height: 100vh;
}

.profile-header-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    border: 1px solid #e9ecef;
}

.profile-avatar {
    position: relative;
}

.avatar-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e67e22;
}

.avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #6c757d;
}

.profile-section-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.section-header h5 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
}

.info-display {
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #2c3e50;
    font-weight: 500;
}

.skill-tag {
    display: inline-block;
    background: #e67e22;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    margin: 4px;
}

.language-tag {
    display: inline-block;
    background: #000000ff;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    margin: 4px;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.portfolio-item, .add-portfolio {
    aspect-ratio: 16/9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.add-portfolio {
    border: 2px dashed #dee2e6;
    background: #f8f9fa;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-portfolio:hover {
    border-color: #e67e22;
    color: #e67e22;
}

.video-upload-area {
    text-align: center;
    padding: 40px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background: #f8f9fa;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
}

.video-upload-area:hover {
    border-color: #e67e22;
    color: #e67e22;
}

.certification-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.availability-status {
    text-align: center;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.available {
    background: #27ae60;
}

.status-indicator.unavailable {
    background: #e74c3c;
}

.schedule-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.schedule-item:last-child {
    border-bottom: none;
}

.schedule-item-detailed {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.schedule-item-detailed:last-child {
    border-bottom: none;
}

.day-header {
    margin-bottom: 10px;
    color: #2c3e50;
}

.time-slots {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.time-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.time-badge.available {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.time-badge.unavailable {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.time-slot-checkbox {
    position: relative;
    display: inline-block;
    margin: 2px;
}

.time-slot-checkbox input[type="checkbox"] {
    display: none;
}

.time-slot-checkbox label {
    display: block;
    padding: 6px 10px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
}

.time-slot-checkbox input[type="checkbox"]:checked + label {
    background: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.time-slot-checkbox label:hover {
    background: #e9ecef;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 8px;
}

.day-schedule {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.day-schedule h6 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 600;
}

.hourly-schedule {
    max-height: 400px;
    overflow-y: auto;
}

/* Responsive grid for mobile */
@media (max-width: 768px) {
    .time-slots-grid {
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: 6px;
    }
    
    .time-slot-checkbox label {
        padding: 4px 6px;
        font-size: 11px;
    }
}

.hourly-schedule {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
}

.day-schedule {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.day-schedule:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.time-slots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 8px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #e67e22;
}

.stat-label {
    font-size: 14px;
    color: #6c757d;
}

.education-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.education-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-header-card {
        padding: 20px;
    }
    
    .profile-section-card {
        padding: 20px;
    }
    
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}
</style>
