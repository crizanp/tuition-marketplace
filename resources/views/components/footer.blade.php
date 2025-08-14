<footer>
    <div class="footer-container">
        <div class="footer-flex">
            <!-- Logo & Contact -->
            <div class="footer-logo-contact">
                <img src="{{ asset('images/logo.png') }}" alt="GyanHub Logo">
                <p>Contact: <a href="mailto:info@gyanhub.com">info@gyanhub.com</a></p>
                <p>Phone: <a href="tel:+977-9800000000">+977-9800000000</a></p>
                <p>Address: Kathmandu, Nepal</p>
            </div>
            <!-- Quick Links -->
            <div class="footer-links">
                <div>
                    <h4>Marketplace</h4>
                    <ul>
                        <li><a href="#">Vacancy</a></li>
                        <li><a href="#">All Tutors</a></li>
                        <li><a href="#">Scam Lists</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">How it Works</a></li>
                        <li><a href="#">Login as Student</a></li>
                        <li><a href="#">Login as Tutor</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Study</h4>
                    <ul>
                        <li><a href="#">A Level Home Tuition</a></li>
                        <li><a href="#">+2 Home Tuition</a></li>
                        <li><a href="#">Engineering Classes</a></li>
                        <li><a href="#">Loksewa</a></li>
                        <li><a href="#">Other Courses</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Locations</h4>
                    <ul>
                        <li><a href="#">Tutors in Kathmandu</a></li>
                        <li><a href="#">Tutors in Biratnagar</a></li>
                        <li><a href="#">Tutors in Pokhara</a></li>
                        <li><a href="#">Tutors in Butwal</a></li>
                        <li><a href="#">Tutors in Dharan</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="footer-hr">
        <div style="display:flex;flex-direction:column;gap:0.5rem;align-items:center;">
            <div class="footer-copyright">
                &copy; {{ date('Y') }} GyanHub Tuition Marketplace. All rights reserved.
            </div>
            <div class="footer-policy">
                <span>Policies:</span>
                <a href="#">Privacy Policy</a>
                <span class="sep">|</span>
                <a href="#">Terms &amp; Conditions</a>
                <span class="sep">|</span>
                <a href="#">Payment Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Custom footer styles (falls back when Tailwind is not used or for overrides) -->
<style>
/* Custom Footer Styles */
footer {
    background-color: #1a202c;
    color: #e2e8f0;
    padding-top: 2.5rem;
    padding-bottom: 1.5rem;
    font-family: 'Segoe UI', Arial, sans-serif;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.footer-flex {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

@media (min-width: 768px) {
    .footer-flex {
        flex-direction: row;
        justify-content: space-between;
    }
}

.footer-logo-contact {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.footer-logo-contact img {
    width: 8rem;
    margin-bottom: 1rem;
}

.footer-logo-contact a {
    color: #e2e8f0;
    text-decoration: underline;
}

.footer-links {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

@media (min-width: 768px) {
    .footer-links {
        flex-direction: row;
    }
}

.footer-links h4 {
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #ffffff; /* headings white */
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.5rem;
}

.footer-links a {
    color: #a0aec0; /* muted gray links */
    text-decoration: none;
    transition: text-decoration 0.2s;
}

.footer-links a:hover {
    text-decoration: underline;
    color: #e2e8f0;
}

.footer-hr {
    margin: 1.5rem 0;
    border: none;
    border-top: 1px solid #4a5568;
}

.footer-copyright {
    text-align: center;
    font-size: 0.875rem;
}

.footer-policy {
    font-size: 0.75rem;
    color: #94a3b8; /* subtle gray */
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.footer-policy a {
    color: #9aa6b3;
    text-decoration: none;
}

.footer-policy a:hover {
    text-decoration: underline;
    color: #e2e8f0;
}

.footer-policy .sep {
    color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    .footer-logo-contact {
        margin-bottom: 1.5rem;
    }
    .footer-links {
        gap: 1.5rem;
    }
}
</style>