@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="dashboard-title">
                        <i class="fas fa-user-shield me-3"></i>Admin Dashboard
                    </h1>
                    <p class="dashboard-subtitle">Welcome back, {{ Auth::guard('admin')->user()->name }}!</p>
                </div>
                <div class="col-md-4 text-end">
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="dashboard-content">
        <div class="container">
            <!-- Stats Cards -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-primary">
                        <div class="stats-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ \App\Models\User::count() }}</h3>
                            <p class="stats-label">Total Students</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-success">
                        <div class="stats-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ \App\Models\Tutor::count() }}</h3>
                            <p class="stats-label">Total Tutors</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-warning">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ \App\Models\Tutor::where('status', 'pending')->count() }}</h3>
                            <p class="stats-label">Pending Tutors</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-info">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ \App\Models\Tutor::where('status', 'active')->count() }}</h3>
                            <p class="stats-label">Active Tutors</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="action-title">Manage Students</h4>
                        <p class="action-description">View and manage all registered students</p>
                        <a href="/admin/students" class="btn btn-primary">View Students</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4 class="action-title">Manage Tutors</h4>
                        <p class="action-description">Approve, manage and monitor tutors</p>
                        <a href="/admin/tutors" class="btn btn-primary">View Tutors</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="action-title">KYC Verification</h4>
                        <p class="action-description">Review and approve tutor KYC documents</p>
                        <a href="/admin/kyc" class="btn btn-primary">Review KYC</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 class="action-title">Analytics</h4>
                        <p class="action-description">View platform analytics and reports</p>
                        <a href="/admin/analytics" class="btn btn-primary">View Analytics</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h4 class="action-title">Settings</h4>
                        <p class="action-description">Configure platform settings</p>
                        <a href="/admin/settings" class="btn btn-primary">Settings</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4 class="action-title">Messages</h4>
                        <p class="action-description">Manage platform communications</p>
                        <a href="/admin/messages" class="btn btn-primary">Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .dashboard-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        padding: 2rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dashboard-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .dashboard-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin: 0;
        margin-top: 0.5rem;
    }

    .dashboard-content {
        padding: 3rem 0;
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        flex-shrink: 0;
    }

    .stats-primary .stats-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stats-success .stats-icon {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .stats-warning .stats-icon {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .stats-info .stats-icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stats-content {
        flex-grow: 1;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }

    .stats-label {
        font-size: 1rem;
        color: #7f8c8d;
        margin: 0;
        font-weight: 500;
    }

    .action-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .action-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto 1.5rem auto;
    }

    .action-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .action-description {
        color: #7f8c8d;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem;
        }

        .stats-card {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .stats-number {
            font-size: 2rem;
        }

        .action-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endsection