 <?php
// Connect to database with error handling
$conn = new mysqli("localhost", "root", "", "kp_bikes");

// Check connection
if ($conn->connect_error) {
    // If connection fails, show error but don't die (for demo purposes)
    $db_error = "Database connection failed: " . $conn->connect_error;
} else {
    // Get available bikes
    $bikes = $conn->query("SELECT * FROM bikes WHERE status='Available'");
    if (!$bikes) {
        $db_error = "Query failed: " . $conn->error;
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="KP Consulting - Expert bike buying, selling, and consulting services. Find your perfect bike or get top value for your current one.">
        <meta name="keywords" content="bike consulting, buy bike, sell bike, bike experts, bike valuation">
        <meta name="author" content="KP Consulting">
        <title>KP Consulting | Bike Buying, Selling & Consulting Experts</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Orbitron:wght@500;600;700&display=swap" rel="stylesheet">
        <style>
             :root {
                --primary: #00a8ff;
                --primary-dark: #0077b6;
                --secondary: #ff6b6b;
                --dark: #2d343
                --light: #f5f6fa;
                --gray: #636e72;
                --tech-gradient: linear-gradient(135deg, #00a8ff 0%, #0077b6 100%);
                --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                --transition: all 0.3s ease;
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Montserrat', sans-serif;
                color: var(--dark);
                background-color: var(--light);
                overflow-x: hidden;
                line-height: 1.6;
            }
            
            h1,
            h2,
            h3,
            h4 {
                font-family: 'Orbitron', sans-serif;
                font-weight: 600;
                letter-spacing: 1px;
            }
            
            a {
                text-decoration: none;
                color: inherit;
            }
            
            ul {
                list-style: none;
            }
            
            img {
                max-width: 100%;
                height: auto;
            }
            
            .container {
                width: 90%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: var(--tech-gradient);
                color: white;
                border: none;
                border-radius: 50px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                cursor: pointer;
                transition: var(--transition);
                box-shadow: 0 5px 15px rgba(0, 168, 255, 0.3);
            }
            
            .btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 168, 255, 0.4);
            }
            
            .btn-outline {
                background: transparent;
                border: 2px solid var(--primary);
                color: var(--primary);
                box-shadow: none;
            }
            
            .btn-outline:hover {
                background: var(--primary);
                color: white;
            }
            
            .section {
                padding: 100px 0;
            }
            
            .section-title {
                text-align: center;
                
                font-size: 2.5rem;
                position: relative;
                color: var(--dark);
            }
            
            .section-title::after {
                content: '';
                position: absolute;
                bottom: -15px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: var(--tech-gradient);
                border-radius: 2px;
            }
            
            .text-center {
                text-align: center;
            }
            /* Debug styles - can be removed after fixing */
            
            .debug-info {
                background: #ffeb3b;
                color: #000;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                font-family: monospace;
            }
            /* Header & Navigation */
            
            header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1000;
                background-color: rgba(245, 246, 250, 0.95);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: var(--transition);
            }
            
            header.scrolled {
                background-color: rgba(255, 255, 255, 0.98);
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                padding: 10px 0;
            }
            
            .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
                transition: var(--transition);
            }
            
            .logo {
                font-family: 'Orbitron', sans-serif;
                font-weight: 700;
                font-size: 1.8rem;
                color: var(--primary);
                display: flex;
                align-items: center;
            }
            
            .logo span {
                color: var(--dark);
            }
            
            .logo-icon {
                margin-right: 10px;
                font-size: 1.5rem;
            }
            
            .nav-links {
                display: flex;
                gap: 30px;
            }
            
            .nav-links a {
                font-weight: 600;
                position: relative;
                padding: 5px 0;
                transition: var(--transition);
            }
            
            .nav-links a::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--primary);
                transition: var(--transition);
            }
            
            .nav-links a:hover::after {
                width: 100%;
            }
            
            .nav-links a:hover {
                color: var(--primary);
            }
            
            .hamburger {
                display: none;
                cursor: pointer;
                font-size: 1.5rem;
            }
            /* Hero Section */
            
            .hero {
                height: 100vh;
                display: flex;
                align-items: center;
                background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('bck2.jpg') no-repeat center center/cover;
                color: white;
                text-align: center;
                position: relative;
                overflow: hidden;
            }
            
            .hero-content {
                max-width: 800px;
                margin: 0 auto;
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 1s ease forwards 0.5s;
            }
            
            .hero h1 {
                font-size: 3.5rem;
                margin-bottom: 20px;
                line-height: 1.2;
            }
            
            .hero p {
                font-size: 1.2rem;
                margin-bottom: 30px;
                opacity: 0.9;
            }
            
            .hero-btns {
                display: flex;
                gap: 20px;
                justify-content: center;
            }
            
            .hero-btns .btn-outline {
                border-color: white;
                color: white;
            }
            
            .hero-btns .btn-outline:hover {
                background: white;
                color: var(--dark);
            }
            /* About Section */
            
            .about {
                background-color: white;
            }
            
            .about-content {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 50px;
                align-items: center;
            }
            
            .about-text h2 {
                margin-bottom: 20px;
                color: var(--dark);
            }
            
            .about-text p {
                margin-bottom: 20px;
                color: var(--gray);
            }
            
            .about-image {
                position: relative;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: var(--shadow);
                transform: perspective(1000px) rotateY(-10deg);
                transition: var(--transition);
            }
            
            .about-image:hover {
                transform: perspective(1000px) rotateY(0deg);
            }
            
            .about-image img {
                display: block;
                width: 100%;
                height: auto;
                transition: var(--transition);
            }
            /* Services Section */
            
            .services {
                background-color: var(--light);
            }
            
            .services-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 30px;
            }
            
            .service-card {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: var(--shadow);
                transition: var(--transition);
                text-align: center;
                padding: 40px 30px;
            }
            
            .service-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .service-icon {
                font-size: 3rem;
                color: var(--primary);
                margin-bottom: 20px;
            }
            
            .service-card h3 {
                margin-bottom: 15px;
                font-size: 1.5rem;
            }
            
            .service-card p {
                color: var(--gray);
                margin-bottom: 20px;
            }
            /* Featured Bikes */
            
            .featured {
                background-color: white;
            }
            
            .bikes-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
            }
            
            .bike-card {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: var(--shadow);
                transition: var(--transition);
            }
            
            .bike-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .bike-image {
                height: 200px;
                overflow: hidden;
                background: #f5f5f5;
                /* Fallback if image doesn't load */
            }
            
            .bike-image img {
            
                height: 100%;
                object-fit: cover;
                transition: var(--transition);
            }
            
            .bike-card:hover .bike-image img {
                transform: scale(1.1);
            }
            
            .bike-info {
                padding: 20px;
            }
            
            .bike-info h3 {
                margin-bottom: 10px;
                font-size: 1.3rem;
            }
            
            .bike-info p {
                color: var(--gray);
                margin-bottom: 15px;
            }
            
            .bike-price {
                font-weight: 700;
                color: var(--primary);
                font-size: 1.2rem;
                margin-bottom: 15px;
            }
            
            .bike-specs {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
            }
            
            .bike-specs span {
                display: flex;
                align-items: center;
                font-size: 0.9rem;
                color: var(--gray);
            }
            
            .bike-specs i {
                margin-right: 5px;
                color: var(--primary);
            }
            /* Why Choose Us */
            
            .why-us {
                background-color: var(--light);
            }
            
            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }
            
            .feature-item {
                text-align: center;
                padding: 30px;
                background: white;
                border-radius: 10px;
                box-shadow: var(--shadow);
                transition: var(--transition);
            }
            
            .feature-item:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .feature-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--tech-gradient);
                color: white;
                border-radius: 50%;
                font-size: 2rem;
            }
            
            .feature-item h3 {
                margin-bottom: 15px;
                font-size: 1.3rem;
            }
            
            .feature-item p {
                color: var(--gray);
            }
            /* Contact Section */
            
            .contact {
                background-color: white;
            }
            
            .contact-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 50px;
            }
            
            .contact-info h3 {
                margin-bottom: 20px;
                font-size: 1.8rem;
            }
            
            .contact-details {
                margin-bottom: 30px;
            }
            
            .contact-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 20px;
            }
            
            .contact-icon {
                margin-right: 15px;
                color: var(--primary);
                font-size: 1.2rem;
            }
            
            .contact-text h4 {
                margin-bottom: 5px;
                font-size: 1.1rem;
            }
            
            .contact-text p,
            .contact-text a {
                color: var(--gray);
                transition: var(--transition);
            }
            
            .contact-text a:hover {
                color: var(--primary);
            }
            
            .social-links {
                display: flex;
                gap: 15px;
            }
            
            .social-links a {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: var(--light);
                color: var(--dark);
                border-radius: 50%;
                transition: var(--transition);
            }
            
            .social-links a:hover {
                background: var(--primary);
                color: white;
                transform: translateY(-3px);
            }
            
            .contact-form {
                background: var(--light);
                padding: 40px;
                border-radius: 10px;
                box-shadow: var(--shadow);
            }
            
            .contact-form h3 {
                margin-bottom: 20px;
                font-size: 1.8rem;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
            }
            
            .form-control {
                width: 100%;
                padding: 12px 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-family: 'Montserrat', sans-serif;
                transition: var(--transition);
            }
            
            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(0, 168, 255, 0.2);
            }
            
            textarea.form-control {
                min-height: 150px;
                resize: vertical;
            }
            /* Footer */
            
            footer {
                background-color: var(--dark);
            
                padding: 60px 0 20px;
            }
            
            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 40px;
                margin-bottom: 40px;
            }
            
            .footer-col h3 {
                margin-bottom: 20px;
                font-size: 1.3rem;
                position: relative;
                padding-bottom: 10px;
            }
            
            .footer-col h3::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 50px;
                height: 2px;
                background: var(--primary);
            }
            
            .footer-col p {
                margin-bottom: 20px;
                opacity: 0.8;
            }
            
            .footer-links li {
                margin-bottom: 10px;
            }
            
            .footer-links a {
                opacity: 0.8;
                transition: var(--transition);
            }
            
            .footer-links a:hover {
                opacity: 1;
                color: var(--primary);
                padding-left: 5px;
            }
            
            .footer-bottom {
                text-align: center;
                padding-top: 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .footer-bottom p {
                opacity: 0.7;
                font-size: 0.9rem;
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
            
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* Testimonials Styles */
.testimonials {
    background-color: var(--light);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.testimonial-card {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.testimonial-card:hover {
    transform: translateY(-5px);
}

.rating {
    color: #ffc107;
    margin-bottom: 15px;
}

.testimonial-content p {
    font-style: italic;
    margin-bottom: 20px;
    color: var(--gray);
}

.testimonial-author {
    display: flex;
    align-items: center;
}

.testimonial-author img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 15px;
}

.testimonial-author h4 {
    margin-bottom: 5px;
    font-size: 1.1rem;
}

.testimonial-author p {
    font-size: 0.9rem;
    color: var(--gray);
}

            /* Responsive Styles */
            
            @media (max-width: 992px) {
                .section-title {
                    font-size: 2.2rem;
                }
                .hero h1 {
                    font-size: 3rem;
                }
                .about-content {
                    grid-template-columns: 1fr;
                    gap: 30px;
                }
                .about-image {
                    max-width: 500px;
                    margin: 0 auto;
                }
            }
            
            /* Add to your CSS */
.search-container {
    margin: 0 auto 40px;
    max-width: 600px;
}

.search-input {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid #ddd;
    border-radius: 50px;
    font-family: 'Montserrat', sans-serif;
    font-size: 1rem;
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 168, 255, 0.2);
}
            @media (max-width: 768px) {
                .section {
                    padding: 80px 0;
                }
                .section-title {
                    font-size: 2rem;
                    margin-bottom: 50px;
                }
                .hero h1 {
                    font-size: 2.5rem;
                }
                .hero p {
                    font-size: 1rem;
                }
                .hero-btns {
                    flex-direction: column;
                    gap: 15px;
                }
                .nav-links {
                    position: fixed;
                    top: 80px;
                    left: -100%;
                    width: 100%;
                    height: calc(100vh - 80px);
                    background: white;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 30px;
                    transition: var(--transition);
                    z-index: 999;
                }
                .nav-links.active {
                    left: 0;
                }
                .hamburger {
                    display: block;
                }
            }
            
            @media (max-width: 576px) {
                .section {
                    padding: 60px 0;
                }
                .section-title {
                    font-size: 1.8rem;
                }
                .hero h1 {
                    font-size: 2rem;
                }
                .container {
                    width: 95%;
                    padding: 0 15px;
                }
                .contact-form {
                    padding: 30px 20px;
                }
            }
        </style>
    </head>

    <body>
        <!-- Header & Navigation -->
        <header id="header">
    <div class="container">
        <nav class="navbar">
            <a href="#" class="logo">
                <i class="fas fa-bike logo-icon"></i> KP <span>Consulting</span>
            </a>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#featured">Featured Bikes</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>

        <!-- Hero Section -->
        <!-- Update your hero section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <h1>Expert Bike Consulting Services</h1>
            <p>We help you buy the perfect bike, sell your current one for top value, and provide expert consulting to meet all your bike  needs.</p>
            <div class="hero-btns">
                <a href="#contact" class="btn">Get Started</a>
                <a href="#services" class="btn btn-outline">Our Services</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Bikes Section -->
        <section class="section featured" id="featured">
            <div class="container">
                <h2 class="section-title">Find Your Perfect Ride</h2>
<div class="search-container text-center">
        <input type="text" id="bikeSearch" placeholder="Search bikes by name..." 
               class="search-input">
    </div>
                <?php if(isset($db_error)): ?>
                <div class="debug-info">
                    <p>
                        <?php echo $db_error; ?>
                    </p>
                    <p>Showing sample bikes instead.</p>
                </div>
                <div class="bikes-grid">
                  
                    <!-- In your index.php or wherever you display bikes -->
                    <div class="bike-image">
                        <?php if (!empty($bike['image'])): ?>
                        <img src="bike3.jpg" loading="lazy">
                        <?php else: ?>
                        <img src="bike3.jpg" alt="Default bike image">
                        <?php endif; ?>
                    </div>
                    <div class="bike-info">
                        <h3>Hybrid Commuter</h3>
                        <p>Comfortable upright position with rack mounts</p>
                        <div class="bike-price">₹3,82,399.99</div>
                        <a href="#contact" class="btn">Inquire Now</a>
                    </div>
                </div>
            </div>
            <?php elseif($bikes->num_rows === 0): ?>
            <div class="container text-center">
                <p>No bikes currently available. Check back soon!</p>
            </div>
            <?php else: ?>
            <div class="bikes-grid">
                <?php while($bike = $bikes->fetch_assoc()): ?>
                <div class="bike-card">
                    <div class="bike-image">
                        <?php if(!empty($bike['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($bike['image']); ?>" alt="<?php echo htmlspecialchars($bike['name']); ?>">
                        <?php else: ?>
                            <img src="images/default-bike.jpg" alt="Default Bike Image">
                        <?php endif; ?>
                    </div>
                    <div class="bike-info">
                        <h3>
                            <?php echo htmlspecialchars($bike['name']); ?>
                        </h3>
                        <p>
                            <?php echo htmlspecialchars($bike['specs']); ?>
                        </p>
                        <div class="bike-price">₹
                            <?php echo number_format($bike['price'], 2); ?>
                        </div>
                        <a href="#contact" class="btn">Inquire Now</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
                            

        <!-- About Section -->
        <section class="section about" id="about">
            <div class="container">
                <h2 class="section-title">About Us</h2>
                <div class="about-content">
                    <div class="about-text">
                        <h2>Your Trusted Bike Experts</h2>
                        <p>KP Consulting was founded in 2015 with a simple mission: to make the bike buying and selling process easier, more transparent, and more rewarding for bikers of all levels.</p>
                        <p>Our team of certified bike consultants brings together decades of industry experience, technical knowledge, and a genuine passion for riding. We're not just consultants - we're bikers ourselves who understand what matters most
                            to riders.</p>
                        <p>Whether you're looking for your first bike, upgrading to a professional model, or selling a cherished ride, we provide the expertise and personalized service you deserve.</p>
                        <a href="#services" class="btn">Explore Our Services</a>
                    </div>
                    <div class="about-image">
                        <img src="kpteam.jpg" alt="KP Consulting Team">
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="section services" id="services">
            <div class="container">
                <h2 class="section-title">Our Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Bike Buying</h3>
                        <p>We'll help you find the perfect bike for your needs, budget, and riding style. From selection to negotiation, we handle the entire process.</p>
                        <a href="#contact" class="btn btn-outline">Learn More</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <h3>Bike Selling</h3>
                        <p>Get top Rupees for your bike with our professional selling service. We handle valuation, marketing, and negotiations to maximize your return.</p>
                        <a href="#contact" class="btn btn-outline">Learn More</a>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3>Bike Consulting</h3>
                        <p>Our expert consultations cover everything from bike fit and components to maintenance plans and upgrade strategies.</p>
                        <a href="#contact" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- Why Choose Us Section -->
        <section class="section why-us" id="why-us">
            <div class="container">
                <h2 class="section-title">Why Choose KP Consulting</h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Industry Expertise</h3>
                        <p>Our consultants have 10+ years experience in the bike industry with certifications from leading manufacturers.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Best Value Guarantee</h3>
                        <p>We negotiate the best prices and terms, saving you money whether you're buying or selling.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3>Personalized Service</h3>
                        <p>One-on-one consultations tailored to your specific needs and goals.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Trust & Transparency</h3>
                        <p>No hidden fees or pressure - just honest advice you can trust.</p>
                    </div>
                </div>
            </div>
        </section>

<!-- Testimonials Section -->
<section class="section testimonials" id="testimonials">
    <div class="container">
        <h2 class="section-title">Customer Feedback</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"KP Consulting helped me find the perfect road bike within my budget. Their expertise saved me from making an expensive mistake!"</p>
                </div>
                <div class="testimonial-author">
                    <img src="customer1.jpg" alt="Rajesh K.">
                    <h4>Rajesh K.</h4>
                    <p>Coimbatore</p>
                </div>
            </div>
            <div class="testimonial-card">
                <!-- Another testimonial -->
            </div>
            <div class="testimonial-card">
                <!-- Another testimonial -->
            </div>
        </div>
    </div>
</section>

        <!-- Contact Section -->
        <section class="section contact" id="contact">
            <div class="container">
                <h2 class="section-title">Contact Us</h2>
                <div class="contact-container">
                    <div class="contact-info">
                        <h3>Get In Touch</h3>
                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Location</h4>
                                    <p>KP Consulting,Cheran Nagar,</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Phone</h4>
                                    <p><a href="tel:+18005551234">+91 99443 39355</a></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Email</h4>
                                    <p><a href="mailto:info@kpconsulting.com">kpconsulting@gmail.com</a></p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Hours</h4>
                                    <p>Monday-Sunday: 10am-8pm<br></p>
                                </div>
                            </div>
                        </div>
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                     <div class="contact-form">
                <h3>Send Us a Message</h3>
                <form id="contactForm" action="https://formspree.io/f/xovwlodb" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="_replyto" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject" class="form-control" required>
                            <option value="">Select a subject</option>
                            <option value="buying">Bike Buying Inquiry</option>
                            <option value="selling">Bike Selling Inquiry</option>
                            <option value="consulting">Consulting Services</option>
                            <option value="other">Other Question</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>
                    <input type="hidden" name="_subject" value="New Contact Form Submission">
                    <input type="text" name="_gotcha" style="display:none">
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="footer-content">
                    <div class="footer-col">
                        <h3>KP Consulting</h3>
                        <p>Your trusted partner for all bike-related services. We help bikers make informed decisions when buying, selling, or upgrading their bikes.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="footer-col">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About Us</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#featured">Featured Bikes</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h3>Services</h3>
                        <ul class="footer-links">
                            <li><a href="#services">Bike Buying</a></li>
                            <li><a href="#services">Bike Selling</a></li>
                            <li><a href="#services">Bike Consulting</a></li>
                            <li><a href="#services">Valuation Services</a></li>
                            <li><a href="#services">Custom Builds</a></li>
                        </ul>
                    </div>
                   
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 KP Consulting. All Rights Reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Mobile navigation toggle
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');

            hamburger.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                hamburger.innerHTML = navLinks.classList.contains('active') ?
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });

            // Close mobile menu when clicking a link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    navLinks.classList.remove('active');
                    hamburger.innerHTML = '<i class="fas fa-bars"></i>';
                });
            });

            // Header scroll effect
            window.addEventListener('scroll', () => {
                const header = document.getElementById('header');
                header.classList.toggle('scrolled', window.scrollY > 50);
            });

            // Simple form validation
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const subject = document.getElementById('subject').value;
                    const message = document.getElementById('message').value;

                    if (!name || !email || !subject || !message) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
                });
            }

            // Animate elements when they come into view
            const animateOnScroll = () => {
                const elements = document.querySelectorAll('.service-card, .bike-card, .feature-item');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;

                    if (elementPosition < screenPosition) {
                        element.classList.add('animate');
                    }
                });
            };

            window.addEventListener('load', animateOnScroll);
            window.addEventListener('scroll', animateOnScroll);
        // Bike search functionality
const bikeSearch = document.getElementById('bikeSearch');
if (bikeSearch) {
    bikeSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const bikeCards = document.querySelectorAll('.bike-card');
        
        bikeCards.forEach(card => {
            const bikeName = card.querySelector('h3').textContent.toLowerCase();
            if (bikeName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });// Form submission handling
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;

        if (!name || !email || !subject || !message) {
            alert('Please fill in all required fields.');
            return;
        }

        // Submit the form
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Thank you! Your message has been sent.');
                contactForm.reset();
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .catch(error => {
            alert('There was a problem sending your message. Please try again later.');
            console.error('Error:', error);
        });
    });
}
}</script>
    </body>

    </html>