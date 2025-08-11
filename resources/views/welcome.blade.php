<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TutorConnect - Premium Home Tuition Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <style>
        :root {
            --primary-orange: #FF8A50;
            --primary-orange-light: #FFB088;
            --primary-orange-dark: #E66A30;
            --secondary-orange: #FFF4F0;
            --accent-blue: #4F46E5;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --bg-primary: #FFFFFF;
            --bg-secondary: #F9FAFB;
            --border-color: #E5E7EB;
            --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            display: none;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .logo-title {
            font-size: 1.25rem;
            font-weight: var(--font-weight-bold);
            color: black;
            line-height: 1;
        }

        .logo-subtitle {
            font-size: 0.75rem;
            font-weight: var(--font-weight-medium);
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, rgba(255, 138, 80, 0.05) 0%, rgba(255, 244, 240, 0.8) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: var(--shadow-medium);
        }

        .nav-links {
            display: flex;
            gap: 8px;
        }

        .nav-link {
            padding: 10px 20px;
            text-decoration: none;
            color: var(--text-primary);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background-color: var(--secondary-orange);
            border-color: var(--primary-orange-light);
            transform: translateY(-1px);
        }

        .nav-link.primary {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            color: white;
            box-shadow: var(--shadow-medium);
        }

        .nav-link.primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
        }

        /* Hero Section */
        .hero {
            background: white;
            padding: 80px 0 120px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23FF8A50" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23FF8A50" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23FFB088" opacity="0.15"/><circle cx="10" cy="90" r="0.5" fill="%23FFB088" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.6;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background:black;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 20px;
            color: var(--text-secondary);
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .cta-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 16px 32px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            color: white;
            box-shadow: var(--shadow-medium);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-large);
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            border-color: var(--primary-orange);
            background: var(--secondary-orange);
            transform: translateY(-1px);
        }

        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow-large);
            transform: rotate(-2deg);
            position: relative;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(135deg, var(--primary-orange-light) 0%, var(--primary-orange) 100%);
            border-radius: 28px;
            z-index: -1;
            opacity: 0.1;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: var(--bg-secondary);
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        .section-subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-large);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: var(--shadow-medium);
        }

        .feature-icon.students {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
        }

        .feature-icon.tutors {
            background: linear-gradient(135deg, #10B981 0%, #047857 100%);
            color: white;
        }

        .feature-icon.quality {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            color: white;
        }

        .feature-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Stats Section */
        .stats {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            color: white;
            position: relative;
        }

        .stats::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="stats-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23stats-pattern)"/></svg>');
        }

        .stats-content {
            position: relative;
            z-index: 2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 8px;
            display: block;
        }

        .stat-label {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            text-align: center;
        }

        /* Footer */
        .footer {
            background: var(--text-primary);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero-text h1 {
                font-size: 40px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .nav {
                flex-direction: column;
                gap: 20px;
            }

            .cta-buttons {
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card {
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

        /* Hero Search Container */
        .hero-search-container {
            margin: 40px 0;
            position: relative;
            z-index: 10;
        }

        /* Quick Stats */
        .quick-stats {
            display: flex;
            gap: 30px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.2);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-orange-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Enhanced CTA buttons */
        .cta-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        /* Responsive adjustments for hero search */
        @media (max-width: 768px) {
            .hero-search-container {
                margin: 30px 0;
            }
            
            .quick-stats {
                gap: 15px;
                justify-content: center;
            }
            
            .stat-item {
                min-width: 100px;
                padding: 15px;
            }
            
            .stat-number {
                font-size: 24px;
            }
            
            .stat-label {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="/" class="header-logo">
                    <div class="logo-image">
                        <img src="/images/logo.png" alt="Logo"
                            style="max-height: 65px; max-width: 64px; object-fit: contain; display: block; margin: 0 auto;" />
                    </div>
                    <div class="logo-text">
                        <div class="logo-title">GyanHub</div>
                        <div class="logo-subtitle">Learn Today Lead Tomorrow</div>
                    </div>
                </a>
                <div class="nav-links">
                    <a href="/student/login" class="nav-link">Student Login</a>
                    <a href="/tutor/login" class="nav-link primary">Tutor Login</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 style="color: black;">Connect with Expert Tutors for Personalized Learning</h1>
                    <p>Join thousands of students and tutors in our premium marketplace. Quality education, verified
                        professionals, and seamless connections.</p>
                    
                    
                    
                    
                    
                    <div class="cta-buttons">
                        <a href="/jobs" class="btn btn-primary">
                            <i data-feather="search"></i>
                            Browse All Tutors
                        </a>
                        <a href="/tutor" class="btn btn-secondary">
                            <i data-feather="user-plus"></i>
                            Become a Tutor
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <video autoplay muted loop style="max-width: 100%; ">
                        <source src="/images/icon/home-teacher.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Why Choose TutorConnect?</h2>
                <p class="section-subtitle">Experience the future of personalized education with our comprehensive
                    platform designed for success.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon students">
                        <i data-feather="users"></i>
                    </div>
                    <h3 class="feature-title">For Students</h3>
                    <p class="feature-description">Discover qualified tutors in your area with advanced filtering by
                        subject, experience, ratings, and location. Read authentic reviews and connect instantly with
                        your perfect learning partner.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon tutors">
                        <i data-feather="briefcase"></i>
                    </div>
                    <h3 class="feature-title">For Tutors</h3>
                    <p class="feature-description">Build your professional profile, showcase expertise, and manage your
                        teaching opportunities. Set competitive rates, control your schedule, and connect with motivated
                        students.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon quality">
                        <i data-feather="shield-check"></i>
                    </div>
                    <h3 class="feature-title">Quality Assured</h3>
                    <p class="feature-description">Every tutor undergoes rigorous verification. Access detailed ratings,
                        qualifications, and reviews. Enjoy secure payments and 24/7 customer support for complete peace
                        of mind.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-content">
                <div class="section-header">
                    <h2 class="section-title" style="color: white;">Join Our Growing Community</h2>
                    <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.9);">Thousands of successful
                        connections and counting</p>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">2,500+</span>
                        <div class="stat-label">Verified Tutors</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">15,000+</span>
                        <div class="stat-label">Happy Students</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100+</span>
                        <div class="stat-label">Subjects Covered</div>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <div class="stat-label">Cities Served</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Ready to Transform Your Learning Journey?</h2>
                <p class="section-subtitle">Join thousands of students and tutors who trust TutorConnect for quality
                    education connections.</p>
            </div>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">
                    <i data-feather="arrow-right"></i>
                    Get Started Today
                </a>
                <a href="#" class="btn btn-secondary">
                    <i data-feather="compass"></i>
                    Explore Opportunities
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 TutorConnect. All rights reserved. | Premium Home Tuition Marketplace</p>
        </div>
    </footer>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Add smooth scrolling and interaction effects
        document.addEventListener('DOMContentLoaded', function () {
            // Animate stats on scroll
            const statsSection = document.querySelector('.stats');
            const statNumbers = document.querySelectorAll('.stat-number');

            const observerOptions = {
                threshold: 0.7
            };

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        statNumbers.forEach((stat, index) => {
                            const finalNumber = stat.textContent;
                            const number = parseInt(finalNumber.replace(/\D/g, ''));
                            animateCounter(stat, number, finalNumber.includes('+') ? '+' : '');
                        });
                    }
                });
            }, observerOptions);

            observer.observe(statsSection);
        });

        function animateCounter(element, target, suffix = '') {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString() + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString() + suffix;
                }
            }, 30);
        }
    </script>
</body>

</html>