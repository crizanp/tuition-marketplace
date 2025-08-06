@extends('layouts.app')

@section('content')
<div class="dashboard">
    <div class="header">
        <h2>Admin Dashboard</h2>
        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
    
    <p>Welcome, {{ Auth::guard('admin')->user()->name }}!</p>
    <p>This is your admin dashboard where you can manage tutors, students, and the platform.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Total Students</h3>
            <p style="font-size: 24px; color: #007bff;">{{ \App\Models\User::count() }}</p>
        </div>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Total Tutors</h3>
            <p style="font-size: 24px; color: #28a745;">{{ \App\Models\Tutor::count() }}</p>
        </div>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
            <h3>Pending Tutors</h3>
            <p style="font-size: 24px; color: #ffc107;">{{ \App\Models\Tutor::where('status', 'pending')->count() }}</p>
        </div>
    </div>
</div>
@endsection