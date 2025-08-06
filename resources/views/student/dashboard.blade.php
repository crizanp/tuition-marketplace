@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div class="header">
        <h2>Student Dashboard</h2>
        <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <p>Find the perfect tutor for your learning needs!</p>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px;">
        <h3>Your Profile Information</h3>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Phone:</strong> {{ Auth::user()->phone ?? 'Not provided' }}</p>
        <p><strong>Grade Level:</strong> {{ Auth::user()->grade_level ?? 'Not specified' }}</p>
    </div>
    
    <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; margin-top: 20px;">
        <h3>Available Tutors</h3>
        <p>Browse through our qualified tutors and find the perfect match for your learning goals.</p>
        <button style="background: #28a745; margin-top: 10px;">Browse Tutors</button>
    </div>
</div>
@endsection