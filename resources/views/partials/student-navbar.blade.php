@php
    $student = Auth::guard('web')->user();
@endphp
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    :root {
        --header-height: 4.5rem;
        --primary-color: #000000;
        --hover-color: #ff6b35;
        --hover-color-light: #ff8c5a;
        --white-color: #ffffff;
        --text-light: #666666;
        --border-light: #e5e7eb;
        --shadow-light: rgba(0, 0, 0, 0.08);
        --shadow-medium: rgba(0, 0, 0, 0.12);
        --body-font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        --font-weight-medium: 500;
        --font-weight-semibold: 600;
        --font-weight-bold: 700;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 8px;
        --z-fixed: 1000;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: var(--body-font);
        font-weight: var(--font-weight-medium);
        background: #f8fafc;
        padding-top: var(--header-height);
    }

    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--header-height);
        background: var(--white-color);
        z-index: var(--z-fixed);
        border-bottom: 1px solid var(--border-light);
        box-shadow: 0 4px 20px var(--shadow-light);
        backdrop-filter: blur(10px);
    }

    .header-container {
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo Section */
    .header-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        transition: var(--transition);
    }

    .header-logo:hover {
        transform: translateY(-1px);
    }

    .logo-image {
        height: 48px;
        width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .logo-title {
        font-size: 1.25rem;
        font-weight: var(--font-weight-bold);
        color: var(--primary-color);
        line-height: 1;
    }

    .logo-subtitle {
        font-size: 0.75rem;
        font-weight: var(--font-weight-medium);
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Navigation */
    .navbar-center {
        display: flex;
        gap: 2.5rem;
        align-items: center;
    }

    .nav-link {
        position: relative;
        font-size: 0.95rem;
        font-weight: var(--font-weight-semibold);
        color: var(--primary-color);
        text-decoration: none;
        padding: 0.5rem 0;
        transition: var(--transition);
        letter-spacing: 0.2px;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--hover-color), var(--hover-color-light));
        transition: var(--transition);
        border-radius: 1px;
    }

    .nav-link:hover {
        color: var(--hover-color);
        transform: translateY(-1px);
    }

    .nav-link:hover::before {
        width: 100%;
    }

    /* Auth Section */
    .auth-section {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .auth-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        font-weight: var(--font-weight-semibold);
        color: var(--primary-color);
        text-decoration: none;
        border: 2px solid transparent;
        border-radius: var(--border-radius);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .auth-link i {
        font-size: 1rem;
        transition: var(--transition);
    }

    .auth-link:hover {
        color: #333333;
        background: var(--hover-color);
        border-color: var(--hover-color);
        transform: translateY(-2px);
    }

    .auth-link:hover i {
        transform: scale(1.1);
    }

    .auth-link.student {
        background: rgba(255, 107, 53, 0.05);
        border-color: rgba(255, 107, 53, 0.2);
    }

    .auth-link.teacher {
        background: rgba(255, 107, 53, 0.05);
        border-color: rgba(255, 107, 53, 0.2);
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-container {
            padding: 0 1rem;
        }

        .navbar-center {
            display: none;
        }

        .logo-text {
            display: none;
        }

        .auth-section {
            gap: 0.5rem;
        }

        .auth-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

        .auth-link span {
            display: none;
        }
    }

    @media (max-width: 480px) {
        :root {
            --header-height: 3.5rem;
        }

        .auth-link {
            padding: 0.4rem 0.6rem;
        }
    }
</style>
<header class="header">
    <div class="container header-container">
        <!-- Logo Section -->
        <a href="/" class="header-logo">
            <div class="logo-image">
                <img src="/images/logo.png" alt="Logo" style="max-height: 65px; max-width: 64px; object-fit: contain; display: block; margin: 0 auto;" />
            </div>
            <div class="logo-text">
                <div class="logo-title">GyanHub</div>
                <div class="logo-subtitle">Learn Today Lead Tomorrow</div>
            </div>
        </a>
        <!-- Navigation Menu -->
        <nav class="navbar-center">
            <a href="/about" class="nav-link">About Us</a>
            <a href="/courses" class="nav-link">Courses</a>
            <a href="/contact" class="nav-link">Contact</a>
        </nav>
        <!-- Authentication Links -->
        <div class="auth-section">
            @if($student)
                <span class="auth-link student">
                    <i class="fas fa-user-graduate"></i>
                    {{ $student->name }}
                </span>
                <form method="POST" action="{{ route('student.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="auth-link" style="background:none;border:none;padding:0;color:#ff6b35;cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="/student/login" class="auth-link student">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student Login</span>
                </a>
            @endif
        </div>
    </div>
</header>
