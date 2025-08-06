@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div class="header">
        <h2>Tutor Dashboard</h2>
        <form method="POST" action="{{ route('tutor.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    
    <p>Welcome, {{ Auth::guard('tutor')->user()->name }}!</p>
    <p>Status: <strong>{{ ucfirst(Auth::guard('tutor')->user()->status) }}</strong></p>
    
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px;">
        <h3>Your Profile Information</h3>
        <p><strong>Email:</strong> {{ Auth::guard('tutor')->user()->email }}</p>
        <p><strong>Phone:</strong> {{ Auth::guard('tutor')->user()->phone ?? 'Not provided' }}</p>
        <p><strong>Hourly Rate:</strong> ${{ Auth::guard('tutor')->user()->hourly_rate ?? 'Not set' }}</p>
        <p><strong>Bio:</strong> {{ Auth::guard('tutor')->user()->bio ?? 'Not provided' }}</p>
    </div>
</div>
@endsection