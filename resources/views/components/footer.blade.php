<!-- resources/views/components/footer.blade.php -->
<style>
 .custom-footer {
            width: 100%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
            position: relative;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .custom-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .footer-container {
            padding: 60px 40px 40px;
            max-width: 1250px;
            margin: 0 auto;
            position: relative;
        }

        .footer-columns {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            margin-bottom: 60px;
            text-align: center;
        }

        @media (min-width: 640px) {
            .footer-columns {
                grid-template-columns: repeat(2, 1fr);
                text-align: left;
            }
        }

        @media (min-width: 768px) {
            .footer-columns {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .footer-columns {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .footer-column {
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.8s ease forwards;
        }

        .footer-column:nth-child(1) { animation-delay: 0.1s; }
        .footer-column:nth-child(2) { animation-delay: 0.2s; }
        .footer-column:nth-child(3) { animation-delay: 0.3s; }
        .footer-column:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .footer-headline {
            font-weight: 700;
            font-size: 1.1rem;
            color: #f8fafc;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .footer-headline::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 1px;
            animation: expandLine 1s ease forwards;
            animation-delay: 0.8s;
        }

        @keyframes expandLine {
            to {
                width: 100%;
            }
        }

        .footer-column p {
            color: #cbd5e1;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            padding-left: 0;
        }

        .footer-column p:hover {
            color: #3b82f6;
            transform: translateX(8px);
            padding-left: 8px;
        }

        .footer-column p::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 1px;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .footer-column p:hover::before {
            width: 4px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .footer-title {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
            position: relative;
            display: inline-block;
            opacity: 0;
            transform: scale(0.8);
            animation: scaleIn 1s ease forwards;
            animation-delay: 1.2s;
        }

        @keyframes scaleIn {
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .footer-title::before {
            content: '';
            position: absolute;
            inset: -8px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
            border-radius: 16px;
            opacity: 0.1;
            z-index: -1;
            filter: blur(20px);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                opacity: 0.1;
                transform: scale(0.95);
            }
            to {
                opacity: 0.2;
                transform: scale(1.05);
            }
        }

        .footer-desc {
            color: #94a3b8;
            max-width: 400px;
            margin: 0 auto 30px;
            line-height: 1.6;
            opacity: 0;
            animation: fadeIn 1s ease forwards;
            animation-delay: 1.4s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .footer-socials {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            opacity: 0;
            transform: translateY(20px);
            animation: slideUpSocial 0.6s ease forwards;
        }

        .social-icon:nth-child(1) { animation-delay: 1.6s; }
        .social-icon:nth-child(2) { animation-delay: 1.7s; }
        .social-icon:nth-child(3) { animation-delay: 1.8s; }
        .social-icon:nth-child(4) { animation-delay: 1.9s; }

        @keyframes slideUpSocial {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .social-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.4s ease;
            z-index: -1;
        }

        .social-icon:hover {
            color: white;
            transform: translateY(-4px) scale(1.1);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .social-icon:hover::before {
            transform: scale(1);
        }

        /* Floating particles effect */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        .particle:nth-child(odd) {
            background: rgba(139, 92, 246, 0.3);
            animation-duration: 25s;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 1024px) {
            .hide-on-small {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .footer-container {
                padding: 40px 20px 30px;
            }
            
            .footer-title {
                font-size: 2.5rem;
            }
            
            .social-icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }
        }
</style>
<footer class="custom-footer">
  <div class="particles">
    <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
    <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
    <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
    <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
    <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
    <div class="particle" style="left: 70%; animation-delay: 2.5s;"></div>
    <div class="particle" style="left: 80%; animation-delay: 4.5s;"></div>
    <div class="particle" style="left: 90%; animation-delay: 1.5s;"></div>
  </div>

  <div class="footer-container">
    <div class="footer-columns">
      <div class="footer-column">
        <h3 class="footer-headline">Company</h3>
        <p>About Us</p>
        <p>Contact</p>
        <p>Location</p>
        <p>Careers</p>
      </div>
      <div class="footer-column">
        <h3 class="footer-headline">Services</h3>
        <p>Web Design</p>
        <p>Development</p>
        <p>Consulting</p>
        <p>Support</p>
      </div>
      <div class="footer-column">
        <h3 class="footer-headline">Resources</h3>
        <p>Documentation</p>
        <p>Help Center</p>
        <p>Blog</p>
        <p>Community</p>
      </div>
      <div class="footer-column hide-on-small">
        <h3 class="footer-headline">Legal</h3>
        <p>Privacy Policy</p>
        <p>Terms of Service</p>
        <p>Cookie Policy</p>
        <p>GDPR</p>
      </div>
    </div>

    <div class="footer-bottom">
      <h2 class="footer-title">GYANHUB</h2>
      <p class="footer-desc">
        Discover and connect with expert tutors for any subject, anytime. GYANHUB makes it easy for students to find qualified teachers, explore profiles, and book lessons tailored to their learning needs.
      </p>
      <div class="footer-socials">
        <div class="social-icon">
          <ion-icon name="logo-facebook"></ion-icon>
        </div>
        <div class="social-icon">
          <ion-icon name="logo-github"></ion-icon>
        </div>
        <div class="social-icon">
          <ion-icon name="logo-youtube"></ion-icon>
        </div>
        <div class="social-icon">
          <ion-icon name="logo-twitter"></ion-icon>
        </div>
      </div>
    </div>
  </div>
</footer>
<script>
  // Add some interactive sparkle effect on hover
  document.querySelectorAll('.footer-column p').forEach(item => {
    item.addEventListener('mouseenter', function () {
      this.style.textShadow = '0 0 8px rgba(59, 130, 246, 0.6)';
    });

    item.addEventListener('mouseleave', function () {
      this.style.textShadow = 'none';
    });
  });

  // Social icons click effect
  document.querySelectorAll('.social-icon').forEach(icon => {
    icon.addEventListener('click', function () {
      this.style.transform = 'translateY(-4px) scale(1.2)';
      setTimeout(() => {
        this.style.transform = 'translateY(-4px) scale(1.1)';
      }, 150);
    });
  });
</script>