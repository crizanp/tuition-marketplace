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
    }

    .nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .nav__logo {
        display: flex;
        align-items: center;
        font-size: 1.25rem;
        font-weight: var(--font-weight-bold);
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .nav__logo:hover {
        color: var(--hover-color);
    }

    .nav__logo i {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }

    .nav__menu {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav__list {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        list-style: none;
    }

    .nav__link {
        font-size: 0.875rem;
        font-weight: var(--font-weight-medium);
        color: var(--primary-color);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        transition: var(--transition);
        position: relative;
    }

    .nav__link:hover {
        color: var(--hover-color);
        background: rgba(255, 107, 53, 0.1);
    }

    .nav__buttons {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav__button {
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        font-weight: var(--font-weight-semibold);
        border: none;
        border-radius: var(--border-radius);
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav__button--outline {
        color: var(--primary-color);
        background: transparent;
        border: 1px solid var(--border-light);
    }

    .nav__button--outline:hover {
        color: var(--hover-color);
        border-color: var(--hover-color);
        background: rgba(255, 107, 53, 0.05);
    }

    .nav__button--primary {
        color: var(--white-color);
        background: var(--primary-color);
    }

    .nav__button--primary:hover {
        background: var(--hover-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--shadow-medium);
    }

    /* Mobile menu toggle */
    .nav__toggle {
        display: none;
        flex-direction: column;
        gap: 0.25rem;
        cursor: pointer;
        padding: 0.5rem;
    }

    .nav__toggle span {
        width: 1.5rem;
        height: 2px;
        background: var(--primary-color);
        transition: var(--transition);
    }

    /* Responsive design */
    @media screen and (max-width: 768px) {
        .nav {
            padding: 0 1rem;
        }

        .nav__menu {
            display: none;
        }

        .nav__toggle {
            display: flex;
        }

        .nav__buttons {
            gap: 0.5rem;
        }

        .nav__button {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
    }
</style>

<header class="header">
    <nav class="nav">
        <a href="{{ url('/') }}" class="nav__logo">
            <i class="fas fa-graduation-cap"></i>
            Tuition Marketplace
        </a>

        <div class="nav__menu">
            <ul class="nav__list">
                <li><a href="{{ route('search.tutors') }}" class="nav__link">Find Tutors</a></li>
                <li><a href="{{ route('search.vacancies') }}" class="nav__link">Browse Jobs</a></li>
                <li><a href="{{ route('jobs.index') }}" class="nav__link">Tutor Jobs</a></li>
                <li><a href="#" class="nav__link">About</a></li>
                <li><a href="#" class="nav__link">Contact</a></li>
            </ul>
        </div>

        <div class="nav__buttons">
            <a href="{{ route('tutor.login') }}" class="nav__button nav__button--outline">
                <i class="fas fa-sign-in-alt"></i>
                Login
            </a>
            <a href="{{ route('tutor.register') }}" class="nav__button nav__button--primary">
                <i class="fas fa-user-plus"></i>
                Join as Tutor
            </a>
        </div>

        <div class="nav__toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>
