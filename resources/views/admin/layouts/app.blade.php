<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        :root{
            --bg: #070707; /* page background */
            --panel: #0f0f10; /* cards, sidebar */
            --muted: #bdbdbd; /* secondary text */
            --accent: #ff7a00; /* orange accent */
            --border: #1a1a1a;
            --glass: rgba(255,255,255,0.03);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg);
            color: #fff;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: var(--panel);
            box-shadow: 0 6px 30px rgba(0,0,0,0.6);
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid var(--border);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-brand {
            padding: 1.25rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }

        .sidebar-brand h3 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
            letter-spacing: 0.2px;
        }

        .sidebar.collapsed .sidebar-brand h3 {
            display: none;
        }

        .sidebar-nav {
            padding: 0.75rem 0;
        }

        .nav-item { margin-bottom: 0.35rem; }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1.25rem;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.15s ease;
            border-left: 3px solid transparent;
            border-radius: 6px;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.02);
            border-left-color: var(--accent);
            box-shadow: 0 4px 18px rgba(0,0,0,0.5) inset;
        }

        .nav-link i { margin-right: 0.75rem; width: 20px; text-align: center; color: var(--muted); }

        .sidebar.collapsed .nav-link span { display: none; }

        .main-content { margin-left: 250px; min-height: 100vh; transition: all 0.3s ease; }
        .main-content.expanded { margin-left: 80px; }

        .topbar {
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
            box-shadow: 0 6px 30px rgba(0,0,0,0.5);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-toggle { background: none; border: none; font-size: 1.2rem; color: #fff; cursor: pointer; }

        .admin-info { display: flex; align-items: center; gap: 0.8rem; }

        .admin-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #ff9a3a);
            display: flex; align-items: center; justify-content: center; color: #000; font-weight: 700;
        }

        .content-wrapper { padding: 1.75rem; }

        .card {
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.6);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.25rem;
            color: #fff;
        }

        .btn-primary {
            background: var(--accent);
            color: #000;
            border: none;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(255,122,0,0.12);
        }

        .btn-primary:hover { filter: brightness(0.95); transform: translateY(-1px); }

        .table { border-radius: 8px; overflow: hidden; color: #fff; }

        .table thead th {
            background: #0b0b0c;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            font-weight: 600; color: var(--muted);
        }

        .table tbody td { border-color: rgba(255,255,255,0.02); }

        .badge { border-radius: 6px; font-weight: 600; }

        .badge-status {
            background: rgba(255,255,255,0.03);
            color: var(--muted);
            border: 1px solid rgba(255,255,255,0.03);
        }

        .form-control {
            background: #0a0a0b; color: #fff; border: 1px solid #222; border-radius: 8px; padding: 0.6rem 0.75rem;
        }

        .form-control::placeholder { color: rgba(255,255,255,0.35); }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(255,122,0,0.08);
            outline: none;
        }

        .text-muted { color: var(--muted) !important; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .main-content.expanded { margin-left: 0; }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h3><i class="fas fa-graduation-cap"></i> <span>GyanHub Admin</span></h3>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                        <i class="fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.tutors.*') ? 'active' : '' }}" href="{{ route('admin.tutors.index') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Tutors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}" href="{{ route('admin.jobs.index') }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Jobs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.vacancies.*') ? 'active' : '' }}" href="{{ route('admin.vacancies.index') }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Vacancies</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link" href="{{ route('admin.logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="admin-info">
                <span class="text-muted">Welcome back, {{ auth('admin')->user()->name ?? 'Admin' }}!</span>
                <div class="admin-avatar">
                    {{ substr(auth('admin')->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                } else {
                    sidebar.classList.toggle('show');
                }
            });

            // Initialize DataTables
            if ($.fn.DataTable) {
                $('#dataTable').DataTable({
                    pageLength: 25,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }

            // Toastr configuration
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 5000
                };
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
