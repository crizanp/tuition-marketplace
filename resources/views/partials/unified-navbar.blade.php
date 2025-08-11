@php
    $tutor = Auth::guard('tutor')->user();
    $student = Auth::guard('web')->user();
    $admin = Auth::guard('admin')->user();
@endphp

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    :root {
        --top-menu-height: 2.5rem;
        --header-height: 4.5rem;
        --total-header-height: calc(var(--top-menu-height) + var(--header-height));
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
        --z-top-menu: 1001;
        --z-fixed: 1000;
        --z-dropdown: 1100;
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
        padding-top: var(--total-header-height);
        transition: padding-top 0.3s ease;
    }

    body.scrolled {
        padding-top: var(--header-height);
    }

    /* Top Menu Bar */
    .top-menu-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: var(--top-menu-height);
        background-color: #000000ff;
        color: #e0dadaff;
        padding: 0.25rem 1rem;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.75rem;
        z-index: var(--z-top-menu);
        transform: translateY(0);
        transition: transform 0.3s ease;
    }

    .top-menu-bar.hidden {
        transform: translateY(-100%);
    }

    .top-menu-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        height: 100%;
    }

    .contact-info {
        color: #e0dadaff;
        display: flex;
        align-items: center;
    }

    .contact-info i {
        margin-right: 0.5rem;
    }

    .dynamic-links {
        display: flex;
        gap: 1rem;
    }

    .top-auth-link {
        color: #e0dadaff;
        text-decoration: none;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .top-auth-link:hover {
        color: var(--hover-color);
        background: rgba(255, 107, 53, 0.1);
    }

    /* Main Header */
    .header {
        position: fixed;
        top: var(--top-menu-height);
        left: 0;
        width: 100%;
        height: var(--header-height);
        background: var(--white-color);
        z-index: var(--z-fixed);
        border-bottom: 1px solid var(--border-light);
        box-shadow: 0 4px 20px var(--shadow-light);
        backdrop-filter: blur(10px);
        transition: top 0.3s ease, box-shadow 0.3s ease;
    }

    .header.sticky {
        top: 0;
        box-shadow: 0 6px 30px var(--shadow-medium);
    }

    .header-container {
        margin: 0 auto;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        position: relative;
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
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
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

    /* User Dropdown */
    .user-dropdown {
        position: relative;
        display: inline-block;
    }

    .user-dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: rgba(255, 107, 53, 0.05);
        border: 2px solid rgba(255, 107, 53, 0.2);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        font-weight: var(--font-weight-semibold);
        color: var(--primary-color);
        text-decoration: none;
    }

    .user-dropdown-toggle:hover {
        background: var(--hover-color);
        border-color: var(--hover-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
    }

    .user-name {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dropdown-arrow {
        transition: var(--transition);
        font-size: 0.8rem;
    }

    .user-dropdown.active .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid var(--border-light);
        border-radius: var(--border-radius);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: var(--transition);
        z-index: var(--z-dropdown);
        margin-top: 0.5rem;
    }

    .user-dropdown.active .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
        font-size: 0.9rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-item:hover {
        background: var(--hover-color);
        color: white;
    }

    .dropdown-item i {
        font-size: 1rem;
        width: 20px;
    }

    .logout-form {
        margin: 0;
        width: 100%;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        color: var(--primary-color);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .logout-btn:hover {
        background: var(--hover-color);
        color: white;
    }

    .logout-btn i {
        font-size: 1rem;
        width: 20px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        :root {
            --top-menu-height: 2rem;
            --header-height: 3.5rem;
        }

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

        .user-name {
            max-width: 80px;
        }

        .top-menu-bar {
            padding: 0.125rem 0.5rem;
            font-size: 0.7rem;
        }

        .contact-info {
            font-size: 0.7rem;
        }

        .top-auth-link {
            font-size: 0.7rem;
            padding: 0.125rem 0.25rem;
        }
    }

    @media (max-width: 480px) {
        :root {
            --top-menu-height: 1.75rem;
            --header-height: 3rem;
        }

        .auth-link {
            padding: 0.4rem 0.6rem;
        }

        .user-dropdown-toggle {
            padding: 0.5rem 0.75rem;
        }
    }
</style>

<!-- Top Menu Bar -->
<div class="top-menu-bar" id="topMenuBar">
    <div class="top-menu-container">
        <!-- Left Side: Contact Information -->
        <div class="contact-info">
            <i class="fas fa-map-marker-alt"></i>
            Naya Thimi, Bhaktapur, Nepal
            <i class="fas fa-phone-alt" style="padding-left: 10px;"></i>
            +977 9810570014
        </div>

        <!-- Right Side: Dynamic Links -->
        <div class="dynamic-links">
            @if($student && !$tutor)
                <a href="/tutor/login" class="top-auth-link">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Teacher Login
                </a>
            @elseif($tutor && !$student)
                <a href="/student/login" class="top-auth-link">
                    <i class="fas fa-user-graduate"></i>
                    Student Login
                </a>
                 @elseif(!$tutor && !$student)
                <a href="/post-vacancy" class="top-auth-link">
                    <i class="fas fa-briefcase"></i>
                    Post Vacancy
                </a>
                <a href="/search" class="top-auth-link">
                    <i class="fas fa-search"></i>
                    Browse Tutors
                </a>
            @elseif($tutor && $student)
                <a href="/post-vacancy" class="top-auth-link">
                    <i class="fas fa-briefcase"></i>
                    Post Vacancy
                </a>
            @endif
        </div>
    </div>
</div>

<header class="header" id="mainHeader">
    <div class="header-container">
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
            @if($tutor)
                <a href="{{ route('tutor.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('tutor.jobs.index') }}" class="nav-link">Jobs</a>
            @elseif($student)
                <a href="{{ route('student.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="/search" class="nav-link">Find Tutors</a>
            @else
                <a href="/search" class="nav-link">Find Tutors</a>
                <a href="/become-tutor" class="nav-link">Become a Tutor</a>
            @endif
            <a href="/about" class="nav-link">About Us</a>
            <a href="/contact" class="nav-link">Contact</a>
        </nav>

        <!-- Authentication Links -->
        <div class="auth-section">
            @if($tutor)
                <!-- Tutor Dropdown -->
                <div class="user-dropdown">
                    <div class="user-dropdown-toggle">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="user-name">{{ explode(' ', $tutor->name)[0] }}...</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-menu">
                        <a href="{{ route('tutor.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('tutor.profile.index') }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>
                            Profile
                        </a>
                        <a href="{{ route('tutor.jobs.index') }}" class="dropdown-item">
                            <i class="fas fa-briefcase"></i>
                            Jobs
                        </a>
                        <a href="{{ route('tutor.kyc.show') }}" class="dropdown-item">
                            <i class="fas fa-shield-alt"></i>
                            KYC
                        </a>
                        <form method="POST" action="{{ route('tutor.logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($student)
                <!-- Student Dropdown -->
                <div class="user-dropdown">
                    <div class="user-dropdown-toggle">
                        <i class="fas fa-user-graduate"></i>
                        <span class="user-name">{{ explode(' ', $student->name)[0] }}...</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="dropdown-menu">
                        <a href="{{ route('student.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="/student/profile" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>
                            Profile
                        </a>
                        <a href="/search" class="dropdown-item">
                            <i class="fas fa-search"></i>
                            Find Tutors
                        </a>
                        <a href="/student/bookings" class="dropdown-item">
                            <i class="fas fa-calendar-alt"></i>
                            My Bookings
                        </a>
                        <form method="POST" action="{{ route('student.logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Guest Links -->
                <a href="/student/login" class="auth-link student">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student Login</span>
                </a>
                <a href="/tutor/login" class="auth-link teacher">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Tutor Login</span>
                </a>
            @endif
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sticky Header Functionality
    const topMenuBar = document.getElementById('topMenuBar');
    const mainHeader = document.getElementById('mainHeader');
    const body = document.body;
    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateHeader() {
        const scrollY = window.scrollY;
        const topMenuHeight = topMenuBar.offsetHeight;

        if (scrollY > topMenuHeight) {
            // Hide top menu and make main header sticky
            topMenuBar.classList.add('hidden');
            mainHeader.classList.add('sticky');
            body.classList.add('scrolled');
        } else {
            // Show top menu and return main header to normal position
            topMenuBar.classList.remove('hidden');
            mainHeader.classList.remove('sticky');
            body.classList.remove('scrolled');
        }

        lastScrollY = scrollY;
        ticking = false;
    }

    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }

    // Throttled scroll event listener
    window.addEventListener('scroll', requestTick, { passive: true });

    console.log('🔍 DOM loaded, starting dropdown debug...');
    
    // Handle dropdown functionality
    const userDropdowns = document.querySelectorAll('.user-dropdown');
    console.log('📊 Found dropdowns:', userDropdowns.length);
    
    if (userDropdowns.length === 0) {
        console.error('❌ No dropdowns found! Check if .user-dropdown elements exist in DOM');
        return;
    }
    
    userDropdowns.forEach((dropdown, index) => {
        console.log(`🎯 Processing dropdown ${index + 1}:`, dropdown);
        
        const toggle = dropdown.querySelector('.user-dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        console.log(`  - Toggle element:`, toggle);
        console.log(`  - Menu element:`, menu);
        
        if (!toggle) {
            console.error(`❌ No toggle found in dropdown ${index + 1}`);
            return;
        }
        
        if (!menu) {
            console.error(`❌ No menu found in dropdown ${index + 1}`);
            return;
        }
        
        // Click to toggle dropdown
        toggle.addEventListener('click', function(e) {
            console.log('🖱️ Toggle clicked!');
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns
            userDropdowns.forEach((otherDropdown, otherIndex) => {
                if (otherDropdown !== dropdown) {
                    console.log(`🔒 Closing dropdown ${otherIndex + 1}`);
                    otherDropdown.classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            const wasActive = dropdown.classList.contains('active');
            console.log(`📋 Dropdown was active: ${wasActive}`);
            
            dropdown.classList.toggle('active');
            
            const isNowActive = dropdown.classList.contains('active');
            console.log(`📋 Dropdown is now active: ${isNowActive}`);
            
            // Debug: Log current classes
            console.log('🏷️ Current dropdown classes:', dropdown.classList.toString());
            console.log('🏷️ Current menu classes:', menu.classList.toString());
            
            // Debug: Check menu styles
            const menuStyles = window.getComputedStyle(menu);
            console.log('🎨 Menu computed styles:');
            console.log('  - opacity:', menuStyles.opacity);
            console.log('  - visibility:', menuStyles.visibility);
            console.log('  - transform:', menuStyles.transform);
            console.log('  - display:', menuStyles.display);
        });
        
        console.log(`✅ Dropdown ${index + 1} setup complete`);
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        console.log('🖱️ Document clicked, target:', e.target);
        
        if (!e.target.closest('.user-dropdown')) {
            console.log('🔒 Click outside dropdown, closing all');
            userDropdowns.forEach((dropdown, index) => {
                if (dropdown.classList.contains('active')) {
                    console.log(`🔒 Closing dropdown ${index + 1}`);
                    dropdown.classList.remove('active');
                }
            });
        } else {
            console.log('🎯 Click inside dropdown, keeping open');
        }
    });
    
    // Prevent dropdown from closing when clicking inside the menu
    document.querySelectorAll('.dropdown-menu').forEach((menu, index) => {
        console.log(`🛡️ Adding click protection to menu ${index + 1}`);
        menu.addEventListener('click', function(e) {
            console.log('🛡️ Menu clicked, preventing close');
            e.stopPropagation();
        });
    });
    
    // Initialize header state
    updateHeader();
    
    // Debug: Log initial state
    setTimeout(() => {
        console.log('🔍 Initial state check:');
        userDropdowns.forEach((dropdown, index) => {
            const menu = dropdown.querySelector('.dropdown-menu');
            const menuStyles = window.getComputedStyle(menu);
            console.log(`Dropdown ${index + 1}:`);
            console.log('  - Active class:', dropdown.classList.contains('active'));
            console.log('  - Menu opacity:', menuStyles.opacity);
            console.log('  - Menu visibility:', menuStyles.visibility);
        });
    }, 100);
});
</script>