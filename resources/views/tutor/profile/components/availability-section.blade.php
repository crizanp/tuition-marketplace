<!-- Availability Section -->
<div class="profile-section-card mb-4">
    <div class="section-header">
        <h5><i class="fas fa-clock me-2"></i>Availability</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="editSection('availability')">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="section-content" id="availability-content">
        <div class="availability-status mb-3">
            <div class="d-flex align-items-center">
                @if($profile && $profile->availability_status === 'unavailable')
                    <div class="status-indicator unavailable me-2"></div>
                    <span class="status-text">Currently Unavailable</span>
                    @if($profile->unavailable_until)
                        <small class="text-muted d-block">Available from {{ $profile->unavailable_until->format('M d, Y H:i') }}</small>
                    @endif
                @else
                    <div class="status-indicator available me-2"></div>
                    <span class="status-text">Available Now</span>
                @endif
            </div>
        </div>
        <div class="availability-schedule">
            @php
                $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            @endphp
            
            @if($profile)
                @php
                    $hourlyAvailability = $profile->getFormattedHourlyAvailability();
                @endphp
                @foreach($hourlyAvailability as $dayData)
                    <div class="schedule-item-detailed mb-3">
                        <div class="day-header">
                            <strong>{{ $dayData['day'] }}</strong>
                        </div>
                        <div class="time-slots">
                            @php
                                $availableSlots = collect($dayData['slots'])->where('available', true)->pluck('label')->toArray();
                            @endphp
                            @if(count($availableSlots) > 0)
                                <div class="available-times">
                                    @foreach($availableSlots as $timeSlot)
                                        <span class="time-badge available">{{ $timeSlot }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="time-badge unavailable">Unavailable</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                @foreach($daysOfWeek as $day)
                    <div class="schedule-item-detailed mb-3">
                        <div class="day-header">
                            <strong>{{ $day }}</strong>
                        </div>
                        <div class="time-slots">
                            @if($day === 'Sunday')
                                <span class="time-badge unavailable">Unavailable</span>
                            @elseif($day === 'Saturday')
                                <span class="time-badge available">10:00 AM</span>
                                <span class="time-badge available">11:00 AM</span>
                                <span class="time-badge available">12:00 PM</span>
                                <span class="time-badge available">1:00 PM</span>
                                <span class="time-badge available">2:00 PM</span>
                                <span class="time-badge available">3:00 PM</span>
                                <span class="time-badge available">4:00 PM</span>
                            @else
                                <span class="time-badge available">9:00 AM</span>
                                <span class="time-badge available">10:00 AM</span>
                                <span class="time-badge available">11:00 AM</span>
                                <span class="time-badge available">12:00 PM</span>
                                <span class="time-badge available">1:00 PM</span>
                                <span class="time-badge available">2:00 PM</span>
                                <span class="time-badge available">3:00 PM</span>
                                <span class="time-badge available">4:00 PM</span>
                                <span class="time-badge available">5:00 PM</span>
                                <span class="time-badge available">6:00 PM</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
