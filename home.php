<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Hospital Home</title>
    <style>
        /* BASE STYLES & RESET */
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        .body-base {
            background-color: #f9fafb; /* Light background */
            color: #1f2937;
        }

        .container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px; /* px-6 equivalent */
            padding-right: 15px; /* px-6 equivalent */
        }

        .text-center {
            text-align: center;
        }

        /* Utility Spacing (Converted to PX) */
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 16px; }
        .mb-12 { margin-bottom: 48px; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .mt-12 { margin-top: 48px; }

        .section-padding {
            padding-top: 64px; /* py-16 */
            padding-bottom: 64px; /* py-16 */
        }

        .section-heading {
            font-size: 30px; /* text-3xl */
            font-weight: 600;
        }

        .section-paragraph {
            color: #374151;
        }

        /* --- NAVBAR --- */
        .header-navbar {
            background-color: #1d4ed8;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .logo-title {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 24px; /* space-x-6 */
            align-items: center;
            
        }

        .nav-item {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-item:hover {
            color: #c0d0f0; /* custom lighter blue */
        }

        /* Dropdown styling */
        .nav-dropdown {
            position: relative;
            display: inline-block;

        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #1d4ed8;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            margin-top: 1px;
            z-index: 1000;
        }

        .nav-dropdown:hover .dropdown-menu {
            display: flex;
            flex-direction: column;
        }

        .dropdown-item {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-item:hover {
            background-color: #0d47a1;
        }

        /* --- HERO SECTION --- */
        .hero-section {
            background-color: #eff6ff;
            padding-top: 80px; /* py-20 */
            padding-bottom: 80px; /* py-20 */
            text-align: center;
        }

        .hero-title {
            font-size: 36px; /* text-4xl */
            font-weight: bold;
            color: #1e40af;
        }

        .hero-text {
            font-size: 18px; /* text-lg */
            color: #4b5563;
        }

        /* --- SERVICES SECTION --- */
        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px; /* 2rem gap */
        }

        @media (min-width: 768px) {
            .services-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .card-service {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease-in-out, box-shadow 0.5s;
            height: 100%; /* Ensures cards are same height */
        }

        .card-service:hover {
            box-shadow: -10px 16px 20px 0px rgba(0, 100, 255, 0.3); /* Blue shadow on hover */
            transform: scale(1.03); /* Slight scale up for better effect */
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .card-text {
            color: #4b5563;
        }

        /* --- ABOUT SECTION --- */
        .about-section {
            background-color: #f3f4f6;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
            align-items: center;
        }

        @media (min-width: 768px) {
            .about-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .imga {
            border-radius: 8px;
            height: auto; /* Auto height to maintain aspect ratio */
            width: 100%;
            max-width: 700px; /* Max width constraint */
            display: block;
            margin: 0 auto;
            transition: transform 0.6s ease-in-out;
            
        }
.imga:hover {
            transform: scale(1.02);
        }
        /* --- TESTIMONIAL/CHAIRMAN SECTION --- */
        .testanoly {
            background-color: whitesmoke;
            padding: 64px 24px;
        }

        .chari {
            display: flex;
            max-width: 1280px;
            margin: 0 auto;
            gap: 32px;
            align-items: flex-start; /* Align items to top */
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .chairman-text-wrapper {
            flex: 1 1 55%; 
            min-width: 300px;
            max-width: 700px;
        }

        .chari h4 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 16px;
            color: #1d4ed8;
        }

        #chp {
            margin: 0;
            margin-bottom: 16px;
            font-style: italic;
            line-height: 1.6;
            color: #4b5563;
        }

        .chairman {
            flex: 0 0 auto; /* Do not grow, base size */
            max-width: 350px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow: hidden;
            
            padding-bottom: 15px;
        }

        .chairman img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.6s ease-in-out;
        }
        
        .chairman img:hover {   
            transform: scale(1.05);
    
        }
        
        .bhn {
            /* Chairman name below image */
            position: static;
            font-size: 18px;
            font-weight: bold;
            color: #1d4ed8;
            margin-top: 15px;
            padding: 0 10px;
        }

        @media (max-width: 768px) {
            .chari {
                flex-direction: column;
            }
            .chairman {
                max-width: 100%;
                width: 100%;
                margin-top: 16px;
            }
        }
        
        /* --- NEWS & ARTICLES SECTION (New Content) --- */
        .news-section-bg {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8eeff 100%);
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px; /* 2rem */
        }
        
        .news-card {
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            transition: transform 0.3s;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.15);
        }
        
        .news-card-image {
            width: 100%; 
            height: 200px; 
            object-fit: cover;
            display: block;
        }
        
        .card-content-padding {
            padding: 20px;
        }
        
        .news-title {
            color: #1d4ed8; 
            margin-bottom: 8px; 
            font-size: 18px; /* 1.1rem */
        }
        
        .news-text {
            color: #4b5563; 
            line-height: 1.6; 
            margin-bottom: 12px;
        }
        
        .news-link {
            color: #1d4ed8; 
            text-decoration: none; 
            font-weight: 600;
        }

        /* --- WHY CHOOSE US SECTION --- */
        .feature-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 32px;
        }

        .feature-box {
            text-align: center; 
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            transition: transform 0.3s;
        }
        .feature-box:hover{
            
transform: translateY(-7px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.15);
        }

        .feature-icon {
            font-size: 48px; /* 3rem */
            margin-bottom: 12px;
        }

        .feature-title {
            color: #1d4ed8; 
            margin-bottom: 8px; 
            font-weight: 600;
        }

        .feature-text {
            color: #4b5563;
        }

        /* --- TESTIMONIALS SECTION --- */
        .testimonial-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 32px;
        }

        .testimonial-card {
            background: white; 
            padding: 24px; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
             transition: transform 0.3s;
        }
.testimonial-card:hover{
    transform: translateY(-7px);
            box-shadow: 0 12px 24px rgba(29, 78, 216, 0.15);
}
        .testimonial-rating {
            color: #ffc107; 
            margin-bottom: 12px; 
            font-size: 19px; /* 1.2rem */
        }

        .testimonial-quote {
            color: #4b5563; 
            margin-bottom: 16px; 
            font-style: italic;
            flex-grow: 1;
        }

        .testimonial-author {
            color: #1d4ed8; 
            font-weight: 600;
        }

        .testimonial-subtext {
            color: #999; 
            font-size: 14px; /* 0.9rem */
        }

        /* --- CTA SECTION --- */
        .cta-section-style {
            background: linear-gradient(135deg, #1d4ed8 0%, #0d47a1 100%); 
            color: white; 
            text-align: center;
        }

        .cta-text {
            font-size: 18px; /* 1.1rem */
            margin-bottom: 24px; 
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block; 
            background: white; 
            color: #1d4ed8; 
            padding: 12px 32px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: transform 0.3s;
        }
        .cta-button:hover {
            transform: scale(1.05);
        }

        /* --- FOOTER --- */
        .footer-base {
            background-color: #1d4ed8;
            color: white;
            padding-top: 24px;
            padding-bottom: 24px;
        }
        
    </style>
</head>

<body class="body-base">

    <header class="header-navbar shadow-md">
        <div class="container navbar-container">
            <h1 class="logo-title"> PC Hospital</h1>
            <nav class="nav-links">
                <a href="#about" class="nav-item">About</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-item">Logins</a>
                    <div class="dropdown-menu">
                        <a href="patient_login.php" class="dropdown-item">Patient Login</a>
                        <a href="donor.php" class="dropdown-item">Blood Donor Login</a>
                        <a href="donor.php" class="dropdown-item">Blood Recipient Login</a>
                        <a href="staff_login.php" class="dropdown-item">Staff Login</a>
                        
                    </div>
                </div>
                <a href="#contact" class="nav-item">Contact</a>
                <a href="#appoint" class="nav-item">Appointments</a>
            </nav>
        </div>
    </header>

    <section class="hero-section">
        <div class="container hero-content">
            <h2 class="hero-title">We treat, He cures.</h2>
            <p class="hero-text mt-4">Providing world-class healthcare with compassion and excellence.</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding">
        <div class="container text-center">
            <h3 class="section-heading mb-12">Our Services</h3>
            <div class="services-grid">
                <div class="card-service">
                    <h4 class="card-title">Emergency Care</h4>
                    <p class="card-text">24/7 emergency services with advanced facilities.</p>
                </div>
                <div class="card-service">
                    <h4 class="card-title">Specialist in Cardiology</h4>
                    <p class="card-text">Expert heart specialists and modern equipment.</p>
                </div>
                <div class="card-service">
                    <h4 class="card-title">Blood Bank</h4>
                    <p class="card-text">Blood reserve for all types of blood.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section section-padding">
        <div id="about">
        <div class="container about-grid">
            <div class="about-text-content">
                <h3 class="section-heading mb-4">About Us</h3>
                <p class="section-paragraph mb-4">
                    Our hospital is dedicated to providing exceptional healthcare services with a patient-first approach.
                    With state-of-the-art facilities and experienced doctors, we ensure the best treatment for every patient. Lorem ipsum dolor sit amet consectetur adipisicing elit. Culpa unde libero incidunt modi delectus sit quas praesentium doloremque assumenda repellat officia, illo odio, porro id dolores esse ducimus dolorum quidem. Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio quas, illum corrupti error a reiciendis ipsam neque porro, cupiditate at excepturi laborum incidunt? Incidunt ex reiciendis maiores quas omnis labore debitis quidem vel magnam quasi sapiente autem suscipit commodi qui totam, eligendi doloremque quam dicta eius. Repellendus distinctio recusandae quos, explicabo temporibus sint accusamus fugiat aut debitis accusantium alias voluptates, rem cum eveniet facere nam, perspiciatis quas quidem sequi. Magni laboriosam maxime modi libero sit nam excepturi odit, facilis cum totam voluptas officiis sunt in possimus. Corporis, natus velit.
                </p>
            </div>
            <div class="about-image-placeholder">
                <!-- Using a specific class for image container -->
                <img src="assests/hosp1.png" alt="Hospital interior image" class="imga">
            </div>
        </div>
        </div>
    </section>

    <!-- Chairman/Testimony Section -->
    <section class="testanoly">
        <div class="container chari">
            <div class="chairman-text-wrapper">
                <h4>From Chairman</h4>
                <p id="chp">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga voluptates quaerat aliquid sit laudantium itaque tempora reiciendis sed illum impedit sunt animi, asperiores, perferendis recusandae natus libero soluta. Porro asperiores neque omnis nemo ab incidunt iste necessitatibus sapiente.
                </p>
            </div>

            <div class="chairman">
                <img src="assests/chairman.jpg" alt="Chairman's Photo" style="max-width: 100%;">
                <p class="bhn">!??</p>
            </div>
        </div>
    </section>


    <!-- Latest News & Articles Section -->
    <section class="section-padding news-section-bg">
        <div class="container">
            <h3 class="section-heading text-center mb-12">Latest News & Articles</h3>
            <div class="news-grid">
                
                <div class="news-card">
                    <img src="assests/news1.png" alt="Blood Donation" class="news-card-image">
                    <div class="card-content-padding">
                        <h4 class="news-title">The Importance of Regular Blood Donations</h4>
                        <p class="news-text">Regular blood donations save lives and help maintain a healthy blood supply for hospitals worldwide.</p>
                        <a href="#" class="news-link">Read More →</a>
                    </div>
                </div>
                
                <div class="news-card">
                    <img src="assests/news2.png" alt="Hospital Care" class="news-card-image">
                    <div class="card-content-padding">
                        <h4 class="news-title">Advanced Healthcare Technology</h4>
                        <p class="news-text">Our hospital features cutting-edge medical equipment and technology for better patient outcomes.</p>
                        <a href="#" class="news-link">Read More →</a>
                    </div>
                </div>
                
                <div class="news-card">
                    <img src="assests/news3.png" alt="Health Tips" class="news-card-image">
                    <div class="card-content-padding">
                        <h4 class="news-title">Healthy Living Tips for You</h4>
                        <p class="news-text">Simple and effective ways to maintain good health and prevent common diseases.</p>
                        <a href="#" class="news-link">Read More →</a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Why Choose Us Section -->
    <section class="section-padding" style="background: white;">
        <div class="container">
            <h3 class="section-heading text-center mb-12">Why Choose PC Hospital?</h3>
            <div class="feature-grid">
                
                <div class="feature-box">
                    <div class="feature-icon">🏥</div>
                    <h4 class="feature-title">State-of-the-Art Facilities</h4>
                    <p class="feature-text">Modern equipment and comfortable patient rooms for the best treatment experience.</p>
                </div>
                
                <div class="feature-box">
                    <div class="feature-icon">👨‍⚕️</div>
                    <h4 class="feature-title">Expert Medical Team</h4>
                    <p class="feature-text">Highly qualified doctors and nurses with years of experience in their fields.</p>
                </div>
                
                <div class="feature-box">
                    <div class="feature-icon">⏰</div>
                    <h4 class="feature-title">24/7 Emergency Services</h4>
                    <p class="feature-text">Round-the-clock emergency department ready to handle any medical situation.</p>
                </div>
                
                <div class="feature-box">
                    <div class="feature-icon">💝</div>
                    <h4 class="feature-title">Compassionate Patient Care</h4>
                    <p class="feature-text">Compassionate care and personalized treatment plans for every patient.</p>
                </div>

                <div class="feature-box">
                    <div class="feature-icon">🩸</div>
                    <h4 class="feature-title">Blood Bank Services</h4>
                    <p class="feature-text">Reliable blood reserve and testing facilities for all blood types.</p>
                </div>
                
                <div class="feature-box">
                    <div class="feature-icon">💰</div>
                    <h4 class="feature-title">Affordable Healthcare</h4>
                    <p class="feature-text">Quality healthcare at competitive prices with insurance support.</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section-padding" style="background: #f3f4f6;">
        <div class="container">
            <h3 class="section-heading text-center mb-12">Patient Testimonials</h3>
            <div class="testimonial-grid">
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-quote">"Excellent service and caring staff. The doctors are very professional and the facilities are top-notch."</p>
                    <h5 class="testimonial-author">Rajesh Kumar</h5>
                    <p class="testimonial-subtext">Patient, 2024</p>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐</div>
                    <p class="testimonial-quote">"I have serious issue in my stomach thanxxx for doctor Flex ."</p>
                    <h5 class="testimonial-author">sabun chor</h5>
                    <p class="testimonial-subtext">Patient, 1969</p>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
                    <p class="testimonial-quote">"Great experience! The medical team is highly skilled and the patient care is exceptional."</p>
                    <h5 class="testimonial-author">Amit Patel</h5>
                    <p class="testimonial-subtext">Patient, 2024</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="section-padding cta-section-style">
        <div class="container">
            <div id="appoint">
            <h3 class="section-heading" style="color: white; margin-bottom: 16px;">Ready to Book an Appointment?</h3>
            <p class="cta-text">Book your appointment with us today to schedule your visit with our expert medical team.</p>
            <a href="patient_login.php" class="cta-button">Book Appointment</a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer-base">
        <div id="contact">
        <div class="container section-padding" style="padding-top: 24px; padding-bottom: 24px;">
            <div class="text-center"> 
                
                <h3 class="section-heading mb-6 contact" style="color: white;">Contact Us</h3>
                <p class="section-paragraph mb-4" style="color: white; opacity: 0.9;">📍 V.N Purav Marg, Deonar, Mumbai-400088</p>
                <p class="section-paragraph mb-4" style="color: white; opacity: 0.9;">📞 +91 9650219912</p>
                <p class="section-paragraph mb-4" style="color: white; opacity: 0.9;">✉️ Pc@hospital.com</p>
            </div>
        </div>
        <div class="container text-center" style="padding-top: 10px; padding-bottom: 10px; border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <p style="font-size: 14px; color: rgba(255, 255, 255, 0.8);">&copy; 2012 Pc Hospital. All rights reserved.</p>
        </div>
        </div>
    </footer>
</body>
</html>