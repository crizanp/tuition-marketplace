<!-- FontAwesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<div class="site-header">
    <div class="header-content">
        <div class="logo-section">
            <div class="logo">
                <img src="/images/logo.png" alt="GyanHub Nepal Logo" class="logo-img">
            </div>
            <div class="site-title">
                <h1>GyanHub Nepal</h1>
                <p class="tagline">Helping Students Learn</p>
            </div>
        </div>
        
        <div class="contact-info">
            <div class="phone-left">
                <span class="fa-brands fa-whatsapp"></span>
                <span class="phone-number">8367682255</span>
            </div>
            <div class="phone-right">
                <span class="fa-solid fa-phone"></span>
                <span class="phone-number">8367682255</span>
            </div>
        </div>
    </div>
    
    <div class="slogan">
        <h2>We Help Students and Tutors Find Each Other</h2>
    </div>
</div>

<style>
.site-header {
    background: #111;
    color: #fff;
    padding: 10px 0 6px 0;
    margin-bottom: 18px;
    border-bottom: 2px solid #222;
    font-family: 'Segoe UI', Arial, sans-serif;
}

.header-content {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 12px;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo-img {
    width: 80px;
    height: 80px;
}

.site-title h1 {
    color: #fff;
    font-size: 1.35em;
    margin: 0;
    font-weight: 700;
    letter-spacing: 1px;
}

.tagline {
    color: #bbb;
    font-size: 0.95em;
    margin: 2px 0 0 0;
    font-style: italic;
    font-weight: 500;
}

.contact-info {
    display: flex;
    gap: 16px;
    align-items: center;
}

.phone-left, .phone-right {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #222;
    padding: 5px 10px;
    border-radius: 18px;
    box-shadow: none;
    font-size: 0.98em;
}

.phone-left .fa-whatsapp {
    color: #25d366;
    font-size: 1.1em;
}

.phone-right .fa-phone {
    color: #fff;
    font-size: 1.1em;
}

.phone-number {
    font-weight: 600;
    color: #fff;
    font-size: 1em;
}

.slogan {
    text-align: center;
    margin-top: 7px;
    padding: 0 10px;
}

.slogan h2 {
    color: #fff;
    font-size: 1.08em;
    margin: 0;
    font-style: italic;
    font-weight: 400;
    letter-spacing: 0.5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 7px;
    }
    
    .site-title h1 {
        font-size: 1.1em;
    }
    
    .slogan h2 {
        font-size: 0.95em;
    }
}

@media (max-width: 480px) {
    .logo-section {
        flex-direction: column;
        gap: 7px;
    }
    
    .site-title h1 {
        font-size: 0.98em;
    }
    
    .slogan h2 {
        font-size: 0.85em;
    }
    
    .phone-left, .phone-right {
        padding: 4px 7px;
    }
}
</style>
