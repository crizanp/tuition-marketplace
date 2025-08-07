
@extends('layouts.app')

@section('navbar')
    @include('partials.tutor-navbar')
@endsection

@section('content')
<div class="dashboard py-5 container">
  
    <p>Welcome, {{ Auth::guard('tutor')->user()->name }}! teacher portal</p>
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