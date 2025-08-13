@extends('layouts.app')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
@endpush

@section('content')
    <style>
        :root {
            --primary: #FF7A45;
            --primary-light: #FFA076;
            --primary-soft: #FFF5F2;
            --primary-dim: #FFEEE9;
            --text-dark: #000000ff;
            --text-medium: #5A6C7D;
            --text-light: #8B95A1;
            --white: #FFFFFF;
            --gray-50: #FAFBFC;
            --gray-100: #F5F7FA;
            --gray-200: #E4E9F0;
            --border: #E8ECF1;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--primary-dim) 0%, var(--white) 40%);
            min-height: 100vh;
        }

        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Hero Section */
        .hero {
            padding: 60px 0 80px;
            background: transparent;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .hero-text {
            max-width: 520px;
        }

        .hero-text h1 {
            font-size: 48px;
            font-weight: 600;
            line-height: 1.15;
            margin-bottom: 24px;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .highlight {
            color: var(--primary);
        }

        .hero-text p {
            font-size: 18px;
            color: var(--text-medium);
            margin-bottom: 36px;
            line-height: 1.7;
        }

        .cta-buttons {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: white;
            color: var(--text-medium);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        /* Features Section */
        .features {
            background: var(--white);
            padding-top: 0px;
            padding-bottom: 45px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
            max-width: 640px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-title {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-dark);
            letter-spacing: -0.01em;
        }

        .section-subtitle {
            font-size: 16px;
            color: var(--text-medium);
            line-height: 1.6;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .feature-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            /* Prevents icon from shrinking */
        }

        .feature-icon.students {
            background: black;
        }

        .feature-icon.tutors {
            background: black;
        }

        .feature-icon.quality {
            background: black;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
            /* Remove margin since we're using flexbox spacing */
        }

        .feature-description {
            color: var(--text-medium);
            line-height: 1.6;
            font-size: 15px;
        }

        /* Stats Section */
        .stats {
            padding: 80px 0;
            background: var(--gray-50);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-item {
            background: white;
            padding: 32px 20px;
            border-radius: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            display: block;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-medium);
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: white;
            text-align: center;
        }

        /* Footer */
        .footer {
            background: var(--gray-100);
            padding: 40px 0;
            text-align: center;
            border-top: 1px solid var(--border);
        }

        .footer p {
            color: var(--text-light);
            font-size: 14px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }

            .nav {
                height: 64px;
            }

            .hero {
                padding: 60px 0 80px;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 36px;
            }

            .hero-text p {
                font-size: 16px;
            }

            .cta-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }

            .features {
                padding: 80px 0;
            }

            .section-title {
                font-size: 28px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .stats {
                padding: 60px 0;
            }

            .nav-links {
                gap: 4px;
            }

            .nav-link {
                padding: 8px 16px;
                font-size: 13px;
            }
        }

        /* Smooth animations */
        .feature-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .feature-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .feature-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .feature-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Focus states for accessibility */
        .btn:focus,
        .nav-link:focus {
            outline: 2px solid var(--primary);
    </style>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Gyan Hub. Connect with <span class="highlight">Expert Tutors</span> </h1>
                    <p>Join thousands of students and tutors in our premium marketplace. Quality education, verified
                        professionals, and seamless connections.</p>

                    <div class="cta-buttons">
                        <a href="/jobs" class="btn btn-primary">
                            <i data-feather="search"></i>
                            Browse Tutors
                        </a>
                        <a href="/tutor" class="btn btn-secondary">
                            <i data-feather="user-plus"></i>
                            Become a Tutor
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-image">
                        <img src="https://gyanhub.com.np/wp-content/uploads/2025/08/front-img.png" alt="GyanHub Learning"
                            style="width: 100%; max-width: 500px; height: auto;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Search Bar Component -->
    <div class="search-container-wrapper" style="background-color: #ffffffff; padding: 2rem 0;">
        <div class="container" style="margin: 0 auto;">
            <x-search-bar action="/search/tutors" placeholder="Search for tutors, subjects, locations..." showFilters="true"
                size="large" />
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('searchForm');
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const keyword = document.getElementById('mainSearchInput').value;
                const district = document.getElementById('districtInput').value;
                const place = document.getElementById('placeInput').value;
                const query = `?keyword=${encodeURIComponent(keyword)}&district=${encodeURIComponent(district)}&place=${encodeURIComponent(place)}`;
                window.location.href = `/search/tutors${query}`;
            });
        });
    </script>
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon students">
                            <i data-feather="users"></i>
                        </div>
                        <h3 class="feature-title">For Students</h3>
                    </div>
                    <p class="feature-description">Discover qualified tutors in your area with advanced filtering by
                        subject, experience, ratings, and location. Read authentic reviews and connect instantly.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon tutors">
                            <i data-feather="briefcase"></i>
                        </div>
                        <h3 class="feature-title">For Tutors</h3>
                    </div>
                    <p class="feature-description">Build your professional profile, showcase expertise, and manage your
                        teaching opportunities. Set competitive rates and control your schedule.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon quality">
                            <i data-feather="check-circle"></i>
                        </div>
                        <h3 class="feature-title">Quality Assured</h3>
                    </div>
                    <p class="feature-description">Every tutor undergoes rigorous verification. Access detailed ratings,
                        qualifications, and reviews with secure payments and 24/7 support.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 GyanHub. All rights reserved. | Premium Home Tuition Marketplace</p>
        </div>
    </footer>
    <script>
        // Initialize Feather Icons with debugging logs
        console.log('Initializing Feather Icons');
        feather.replace();
        console.log('Feather Icons Initialized');

        // Smooth counter animation
        document.addEventListener('DOMContentLoaded', function () {
            const statsSection = document.querySelector('.stats');
            const statNumbers = document.querySelectorAll('.stat-number');
            let animated = false;

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !animated) {
                        animated = true;
                        statNumbers.forEach((stat) => {
                            const finalNumber = stat.textContent;
                            const number = parseInt(finalNumber.replace(/\D/g, ''));
                            animateCounter(stat, number, finalNumber.includes('+') ? '+' : '');
                        });
                    }
                });
            }, { threshold: 0.5 });

            observer.observe(statsSection);
        });

        function animateCounter(element, target, suffix = '') {
            let current = 0;
            const increment = target / 40;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString() + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString() + suffix;
                }
            }, 25);
        }
    </script>
@endsection