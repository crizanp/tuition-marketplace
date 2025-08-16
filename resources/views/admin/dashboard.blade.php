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
                            <h3 class="stats-number">{{ $stats['total_students'] ?? 0 }}</h3>
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
                            <h3 class="stats-number">{{ $stats['total_tutors'] ?? 0 }}</h3>
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
                            <h3 class="stats-number">{{ $stats['pending_tutors'] ?? 0 }}</h3>
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
                            <h3 class="stats-number">{{ $stats['active_tutors'] ?? 0 }}</h3>
                            <p class="stats-label">Active Tutors</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-primary">
                        <div class="stats-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ $stats['total_jobs'] ?? 0 }}</h3>
                            <p class="stats-label">Total Jobs</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-success">
                        <div class="stats-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ $stats['featured_jobs'] ?? 0 }}</h3>
                            <p class="stats-label">Featured Jobs</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-warning">
                        <div class="stats-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ $stats['total_vacancies'] ?? 0 }}</h3>
                            <p class="stats-label">Total Vacancies</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card stats-info">
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stats-content">
                            <h3 class="stats-number">{{ $stats['unread_messages'] ?? 0 }}</h3>
                            <p class="stats-label">Unread Messages</p>
                        </div>
                    </div>
                </div>
            </div>

           
            <!-- Quick Actions & Management -->
            <div class="row mb-5">
                <div class="col-lg-6 mb-4">
                    <div class="management-panel">
                        <h5 class="management-title">
                            <i class="fas fa-users"></i> User Management
                        </h5>
                        <div class="management-actions">
                            <a href="{{ route('admin.students.index') }}" class="management-link">
                                <i class="fas fa-user-graduate"></i>
                                Manage Students
                                <span class="badge badge-primary">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.tutors.index') }}" class="management-link">
                                <i class="fas fa-chalkboard-teacher"></i>
                                Manage Tutors
                                <span class="badge badge-success">{{ $stats['total_tutors'] ?? 0 }}</span>
                            </a>
                            <a href="/admin/kyc" class="management-link">
                                <i class="fas fa-id-card"></i>
                                Review KYC
                                <span class="badge badge-warning">{{ $stats['pending_kyc'] ?? 0 }}</span>
                            </a>
                            @if($stats['pending_tutors'] > 0)
                            <a href="{{ route('admin.tutors.index', ['status' => 'pending']) }}" class="management-link urgent">
                                <i class="fas fa-clock"></i>
                                Pending Approvals
                                <span class="badge badge-warning">{{ $stats['pending_tutors'] }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <div class="management-panel">
                        <h5 class="management-title">
                            <i class="fas fa-briefcase"></i> Content Management
                        </h5>
                        <div class="management-actions">
                            <a href="{{ route('admin.jobs.index') }}" class="management-link">
                                <i class="fas fa-briefcase"></i>
                                Manage Jobs
                                <span class="badge badge-primary">{{ $stats['total_jobs'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.vacancies.index') }}" class="management-link">
                                <i class="fas fa-list-alt"></i>
                                Student Vacancies
                                <span class="badge badge-info">{{ $stats['total_vacancies'] ?? 0 }}</span>
                            </a>
                            <a href="{{ route('admin.vacancy-applications.index') }}" class="management-link">
                                <i class="fas fa-file-alt"></i>
                                Vacancy Applications
                                <span class="badge badge-secondary">{{ $stats['total_applications'] ?? 0 }}</span>
                            </a>
                            @if($stats['unread_messages'] > 0)
                            <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" class="management-link urgent">
                                <i class="fas fa-envelope"></i>
                                New Messages
                                <span class="badge badge-danger">{{ $stats['unread_messages'] }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="activity-panel">
                        <h5 class="activity-title">
                            <i class="fas fa-clock"></i> Recent Tutor Registrations
                        </h5>
                        <div class="activity-list">
                            @if(!empty($recent_tutors))
                                @foreach($recent_tutors as $tutor)
                                <div class="activity-item">
                                    <div class="activity-content">
                                        <strong>{{ $tutor->name }}</strong>
                                        <span class="activity-meta">{{ $tutor->email }}</span>
                                        <small class="activity-time">{{ $tutor->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="activity-actions">
                                        @if($tutor->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                        @else
                                        <span class="badge badge-success">Active</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="no-activity">No recent tutor registrations</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <div class="activity-panel">
                        <h5 class="activity-title">
                            <i class="fas fa-star"></i> Featured Jobs
                        </h5>
                        <div class="activity-list">
                            @if(!empty($featured_jobs))
                                @foreach($featured_jobs as $job)
                                <div class="activity-item">
                                    <div class="activity-content">
                                        <strong>{{ $job->title }}</strong>
                                        <span class="activity-meta">by {{ $job->tutor->name ?? 'Unknown' }}</span>
                                        <small class="activity-time">{{ $job->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="activity-actions">
                                        <span class="badge badge-primary">Featured</span>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="no-activity">No featured jobs</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

</div>style>
<style>
    /* Dark/Black theme overrides */
    .admin-dashboard {
        min-height: 100vh;
        background: #000; /* pure black */
        color: #e6eef6;
    }

    .dashboard-header {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(8px);
        padding: 2rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .dashboard-title {
        color: #f5f7fb;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 12px rgba(0,0,0,0.9);
    }

    .dashboard-subtitle {
        color: rgba(230, 238, 255, 0.7);
        font-size: 1.1rem;
        margin: 0;
        margin-top: 0.5rem;
    }

    .dashboard-content {
        padding: 3rem 0;
    }

    .stats-card {
        background: #0b0b0b;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.6);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
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
        background: linear-gradient(135deg,#3a3f8a,#5b3e87);
    }

    .stats-success .stats-icon {
        background: linear-gradient(135deg,#0f8b73,#1fbf9c);
    }

    .stats-warning .stats-icon {
        background: linear-gradient(135deg,#ab5fbf,#e3496b);
    }

    .stats-info .stats-icon {
        background: linear-gradient(135deg,#1f78a3,#00c6ff);
    }

    .stats-content {
        flex-grow: 1;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        color: #f1f6fb;
    }

    .stats-label {
        font-size: 1rem;
        color: rgba(230,230,240,0.6);
        margin: 0;
        font-weight: 500;
    }

    .action-card {
        background: #0b0b0b;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid rgba(255,255,255,0.03);
        color: #e6eef6;
    }

    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.75);
    }

    .action-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #33384f, #4a2f55);
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
        color: #f1f6fb;
        margin-bottom: 1rem;
    }

    .action-description {
        color: rgba(230,230,240,0.65);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-primary {
        background: linear-gradient(135deg,#3a3f8a,#5b3e87);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(58,63,138,0.3);
    }

    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        transition: all 0.3s ease;
        color: #e9eef6;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    .management-panel, .activity-panel {
        background: #0b0b0b;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        height: 100%;
        border: 1px solid rgba(255,255,255,0.03);
        color: #e9eef6;
    }

    .management-title, .activity-title {
        color: #f1f6fb;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .management-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .management-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 2px solid rgba(255,255,255,0.03);
        border-radius: 10px;
        text-decoration: none;
        color: #e9eef6;
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.01);
    }

    .management-link:hover {
        border-color: rgba(58,63,138,0.8);
        background: rgba(58,63,138,0.06);
        text-decoration: none;
        color: #f5f7fb;
        transform: translateX(5px);
    }

    .management-link.urgent {
        border-color: rgba(243, 156, 18, 0.9);
        background: rgba(243, 156, 18, 0.04);
        animation: pulse 2s infinite;
    }

    .management-link.urgent:hover {
        border-color: #e67e22;
        background: rgba(230, 126, 34, 0.08);
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.12); }
        70% { box-shadow: 0 0 0 10px rgba(243, 156, 18, 0); }
        100% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0); }
    }

    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(58,63,138,0.04);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-content strong {
        color: #f1f6fb;
        display: block;
        margin-bottom: 0.25rem;
    }

    .activity-meta {
        color: rgba(230,230,240,0.6);
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        color: rgba(190,200,215,0.7);
        font-size: 0.8rem;
    }

    .activity-actions {
        flex-shrink: 0;
        margin-left: 1rem;
    }

    .no-activity {
        text-align: center;
        color: rgba(200,200,210,0.6);
        font-style: italic;
        padding: 2rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        color: #fff;
    }

    .badge-primary { background: linear-gradient(135deg,#3a3f8a,#5b3e87); }
    .badge-success { background: linear-gradient(135deg,#0f8b73,#1fbf9c); }
    .badge-warning { background: linear-gradient(135deg,#ab5fbf,#e3496b); }
    .badge-info { background: linear-gradient(135deg,#1f78a3,#00c6ff); }

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

        .management-link {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .activity-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .activity-actions {
            margin-left: 0;
        }
    }

</style>
@endsection