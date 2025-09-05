<?php
// Fetch packages for navigation dropdown
require_once '../includes/db.php';

try {
    $nav_packages = fetchAll("SELECT name FROM packages WHERE status = 'active' ORDER BY name ASC");
} catch (Exception $e) {
    $nav_packages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Rainbucks</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* About Hero Section Specific Styles */
        .about-hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px 0;
            background-color: var(--primary-bg);
            position: relative;
            overflow: hidden;
        }

        .about-hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-hero-content {
            position: relative;
            z-index: 2;
        }

        .about-hero-text h2 {
            font-size: 1.2rem;
            font-weight: 400;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .about-hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--header-footer);
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        .about-hero-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .about-hero-image-section {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .about-image-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 1.5rem;
            width: 100%;
            max-width: 500px;
        }

        .about-image-block {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .about-image-block.pink {
            background: linear-gradient(135deg, #FFE5E5, #FFD1D1);
        }

        .about-image-block.purple {
            background: linear-gradient(135deg, #E5E5FF, #D1D1FF);
        }

        .about-circular-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .about-circular-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-decorative-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .about-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
        }

        .about-shape-1 {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #D1D1FF, #B8B8FF);
            top: 10%;
            left: 5%;
        }

        .about-shape-2 {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #FFD1D1, #FFB8B8);
            bottom: 15%;
            left: 8%;
        }

        .about-shape-3 {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #D1D1FF, #B8B8FF);
            top: 60%;
            right: 10%;
        }

        .about-shape-4 {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #FFD1D1, #FFB8B8);
            top: 20%;
            right: 15%;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .about-hero-container {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .about-hero-text h1 {
                font-size: 2.5rem;
            }

            .about-hero-text p {
                font-size: 1rem;
            }

            .about-image-grid {
                max-width: 400px;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .about-hero-text h1 {
                font-size: 2rem;
            }

            .about-image-grid {
                max-width: 300px;
                gap: 0.8rem;
            }

            .about-circular-image {
                 width: 85%;
                 height: 85%;
             }
         }

         /* What We Offer Section Styles */
         .what-we-offer-section {
             padding: 80px 0;
             background-color: var(--primary-bg);
         }

         .what-we-offer-container {
             max-width: 1200px;
             margin: 0 auto;
             padding: 0 20px;
         }

         .what-we-offer-header {
             text-align: center;
             margin-bottom: 4rem;
         }

         .what-we-offer-header h2 {
             font-size: 2.5rem;
             font-weight: 700;
             color: var(--text-dark);
             margin-bottom: 1rem;
         }

         .decorative-lines {
             display: flex;
             justify-content: center;
             gap: 0.5rem;
             margin-bottom: 1.5rem;
         }

         .decorative-lines .line {
             width: 40px;
             height: 3px;
             background: var(--accent-green);
             border-radius: 2px;
         }

         .what-we-offer-header p {
             font-size: 1.1rem;
             color: var(--text-dark);
             opacity: 0.8;
             max-width: 600px;
             margin: 0 auto;
             line-height: 1.6;
         }

         .feature-cards-grid {
             display: grid;
             grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
             gap: 2rem;
             margin-top: 3rem;
         }

         .feature-card {
             padding: 2.5rem;
             border-radius: 20px;
             transition: all 0.3s ease;
             position: relative;
             overflow: hidden;
         }

         .feature-card:hover {
             transform: translateY(-5px);
             box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
         }

         

         .feature-card.white-card {
             background: white;
             border: 1px solid rgba(123, 63, 0, 0.5);
             color: var(--text-dark);
         }

         .feature-icon {
             width: 60px;
             height: 60px;
             border-radius: 15px;
             display: flex;
             align-items: center;
             justify-content: center;
             margin-bottom: 1.5rem;
             font-size: 1.5rem;
         }

         .gradient-card .feature-icon {
             background: var(--accent-green);
             color: white;
         }

         .white-card .feature-icon {
             background: var(--accent-green);
             color: var(--text-dark);
         }

         .white-card:nth-child(3) .feature-icon {
             background: var(--accent-green);
             color: #FF6B6B;
         }

         .feature-card h3 {
             font-size: 1.5rem;
             font-weight: 600;
             margin-bottom: 1rem;
         }

         .feature-card p {
             font-size: 1rem;
             line-height: 1.6;
             opacity: 0.9;
         }

         /* Responsive Design for What We Offer Section */
         @media (max-width: 768px) {
             .what-we-offer-header h2 {
                 font-size: 2rem;
             }

             .feature-cards-grid {
                 grid-template-columns: 1fr;
                 gap: 1.5rem;
             }

             .feature-card {
                 padding: 2rem;
             }
         }

         @media (max-width: 480px) {
             .what-we-offer-header h2 {
                 font-size: 1.8rem;
             }

             .feature-card {
                 padding: 1.5rem;
             }

             .feature-icon {
                 width: 50px;
                 height: 50px;
                 font-size: 1.2rem;
             }
         }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--text-light);
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
            border: 1px solid var(--header-footer);
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid rgba(123, 63, 0, 0.1);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: var(--highlight);
            color: var(--text-light);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--header-footer), var(--highlight));
            color: var(--text-light);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(123, 63, 0, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--header-footer);
            border: 2px solid var(--header-footer);
        }

        .btn-secondary:hover {
            background: var(--header-footer);
            color: var(--text-light);
        }

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .bar {
            width: 25px;
            height: 3px;
            background: var(--text-dark);
            margin: 3px 0;
            transition: 0.3s;
        }

        /* Ensure mobile navbar shows hamburger and hides auth buttons on this page */
        @media (max-width: 768px) {
            .nav-buttons { display: none; }
            .hamburger { display: flex; }
        }
     </style>
</head>
<body>
    
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <div class="logo-icon">
                        <img src="img/logo.png" alt="RainBucks Logo" style="height:32px; width:auto;">
                    </div>
                    <span>Rainbucks</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="../index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link">About Us</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#courses" class="nav-link">All Courses</a>
                    <div class="dropdown-menu">
                        <?php
                        $fixed_order = [
                            'Starter Package',
                            'Professional Package',
                            'Advanced Package',
                            'Expert Package',
                            'Ultimate Package',
                            'Super Ultimate Package'
                        ];
                        $package_url_map = [
                            'Starter Package' => 'starter',
                            'Professional Package' => 'professional',
                            'Advanced Package' => 'advanced',
                            'Expert Package' => 'expert',
                            'Ultimate Package' => 'ultimate',
                            'Super Ultimate Package' => 'super-ultimate'
                        ];
                        $shown = [];
                        foreach ($fixed_order as $fixed_name) {
                            foreach ($nav_packages as $pkg) {
                                if ($pkg['name'] === $fixed_name) {
                                    $url_name = $package_url_map[$fixed_name];
                                    echo '<a href="package/dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($fixed_name) . '</a>';
                                    $shown[] = $fixed_name;
                                    break;
                                }
                            }
                        }
                        $new_packages = array_filter($nav_packages, function($pkg) use ($shown) {
                            return !in_array($pkg['name'], $shown);
                        });
                        usort($new_packages, function($a, $b) {
                            return strcmp($a['name'], $b['name']);
                        });
                        foreach ($new_packages as $pkg) {
                            $url_name = strtolower(str_replace([' ', 'Package'], ['', ''], $pkg['name']));
                            echo '<a href="package/dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($pkg['name']) . '</a>';
                        }
                        ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact Us</a>
                </li>
            </ul>
            <div class="nav-buttons">
                <a href="https://course.Brainbucks.org/learn/account/signin" class="btn btn-secondary">Login</a>
                <a href="https://course.Brainbucks.org/learn/account/signup" class="btn btn-primary">Signup</a>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- About Hero Section -->
    <section class="about-hero-section">
        <div class="about-hero-container">
            <!-- Left Side - Content -->
            <div class="about-hero-content">
                <div class="about-hero-text">
                    <h2>About</h2>
                    <h1>Rain Bucks</h1>
                    <p>At Rain Bucks, we believe in the power of knowledge and its ability to open doors to endless possibilities. Founded in India, our mission is to make high-quality education accessible to everyone. Our team of experts curates courses that are not only practical but also aligned with industry trends, ensuring you stay ahead in your personal and professional journey.</p>
                </div>
            </div>
            
            <!-- Right Side - Image Collage -->
            <div class="about-hero-image-section">
                <div class="about-image-grid">
                    <!-- Top Left - Pink Block -->
                    <div class="about-image-block pink">
                        <div class="about-circular-image">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Students collaborating on laptops">
                        </div>
                    </div>
                    
                    <!-- Top Right - Purple Block -->
                    <div class="about-image-block purple">
                        <div class="about-circular-image">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Group discussion around table">
                        </div>
                    </div>
                    
                    <!-- Bottom Left - Purple Block -->
                    <div class="about-image-block purple">
                        <div class="about-circular-image">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Two people discussing with laptop">
                        </div>
                    </div>
                    
                    <!-- Bottom Right - Pink Block -->
                    <div class="about-image-block pink">
                        <div class="about-circular-image">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Student studying with book and laptop">
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Shapes -->
                <div class="about-decorative-shapes">
                    <div class="about-shape about-shape-1"></div>
                    <div class="about-shape about-shape-2"></div>
                    <div class="about-shape about-shape-3"></div>
                    <div class="about-shape about-shape-4"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Offer Section -->
    <section class="what-we-offer-section">
        <div class="what-we-offer-container">
            <!-- Section Header -->
            <div class="what-we-offer-header">
                <h2>What We Offer</h2>
                <div class="decorative-lines">
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
                <p>With Rainbucks, learning is more than just gaining knowledge—it's about unlocking your true potential.</p>
            </div>
            
            <!-- Feature Cards -->
            <div class="feature-cards-grid">
                <!-- Card 1: Diverse Courses -->
                <div class="feature-card white-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Diverse Courses</h3>
                    <p>From Affiliate Marketing to Personality Development, our courses cater to various interests and career goals.</p>
                </div>
                
                <!-- Card 2: Affordable Packages -->
                <div class="feature-card white-card">
                    <div class="feature-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Affordable Packages</h3>
                    <p>Choose the package that fits your learning needs. Opt for our premium package to gain access to all 20+ courses.</p>
                </div>
                
                <!-- Card 3: Community Support -->
                <div class="feature-card white-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Community Support</h3>
                    <p>Engage with a vibrant community of learners and mentors.</p>
                </div>
            </div>
        </div>
    </section>

    
    <div class="decorative-lines">
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <!-- Testimonial slider removed on About page -->
    <div class="testimonial-container" style="display:none;">
        
        <div class="testimonial-card active">
          <img src="img/logos/avatar1.png" alt="User" class="testimonial-img">
          <h3>Arjun S</h3>
          <p class="location">Mumbai</p>
          <div class="stars">⭐⭐⭐⭐⭐</div>
          <p class="message">"The Personality Development course improved my confidence and communication skills."</p>
        </div>
      
        <!-- Add more cards below for carousel -->
        <div class="testimonial-card">
          <img src="img/logos/avatar2.png" alt="User" class="testimonial-img">
          <h3>Riya M</h3>
          <p class="location">Delhi</p>
          <div class="stars">⭐⭐⭐⭐</div>
          <p class="message">"Great learning experience with interactive sessions and practical tips. Loved it!"</p>
        </div>
      
        <div class="testimonial-card">
          <img src="img/logos/avatar3.png" alt="User" class="testimonial-img">
          <h3>Karthik R</h3>
          <p class="location">Hyderabad</p>
          <div class="stars">⭐⭐⭐⭐⭐</div>
          <p class="message">"Helped me a lot in improving public speaking. Instructors are amazing!"</p>
        </div>
      
        <!-- Navigation Buttons -->
        <button class="testimonial-btn left">&#10094;</button>
        <button class="testimonial-btn right">&#10095;</button>
      </div>
      
      <section class="cta-section">
        <div class="cta-box">
          <h2>Join thousands of learners and start building your skills today!</h2>
          <a href="https://course.Brainbucks.org/learn/account/signup" class="cta-button">Get Started Now</a>
        </div>
      </section>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="custom-footer-container">
            <div class="custom-footer-section brand">
                <div class="custom-footer-logo">
                    <img src="./img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto; vertical-align:middle;">
                    <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;"><span>Rainbucks</span></a>
                </div>
                <p>Explore our high-value packages that unlock all courses, giving you the ultimate edge in today's competitive world.</p>
            </div>
            <div class="custom-footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="terms-conditions.php">Terms & Conditions</a></li>
                    <li><a href="cancellation-refund.php">Cancellation & Refund</a></li>
                    <li><a href="end-user-agreement.php">End User Agreement</a></li>
                    <li><a href="disclaimer.php">Disclaimer</a></li>
                    
                </ul>
            </div>
            <div class="custom-footer-section company">
                <h4>Company</h4>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="about.php">About us</a></li>
                    <li><a href="../public/package/dynamic.php?package=starter">Courses</a></li>
                    <li><a href="contact.php">Contact us</a></li>
                </ul>
            </div>
            <div class="custom-footer-section follow">
                <h4>Follow Us</h4>
                <ul>
                    <li><a href="https://www.facebook.com/profile.php?id=61576408191969" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="https://www.instagram.com/rainbucks.official/?hl=en" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                    <li><a href="https://www.linkedin.com/company/106432687/admin/dashboard/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                    <li><a href="https://whatsapp.com/channel/0029Vb5fOJg4tRrkK1Q9sy0G" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                    <li><a href="https://in.pinterest.com/Rainbucksofficial/" target="_blank"><i class="fab fa-pinterest"></i> Pinterest</a></li>
                </ul>
            </div>
            <div class="custom-footer-section contact">
                <h4>Contact US</h4>
                <p>Email:<br>support@rainbucks.org</p>
            </div>
        </div>
        <div class="custom-footer-bottom">
            <span>&copy; 2025 Rainbucks. All rights reserved.</span>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
        <script>
    // Disable right click
    document.addEventListener("contextmenu", (e) => e.preventDefault());

    // Disable text selection
    document.addEventListener("selectstart", (e) => e.preventDefault());

    // Disable copy, cut, paste
    document.addEventListener("copy", (e) => e.preventDefault());
    document.addEventListener("cut", (e) => e.preventDefault());
    document.addEventListener("paste", (e) => e.preventDefault());

    // Disable common DevTools shortcuts
    document.addEventListener("keydown", (e) => {
    // F12
    if (e.key === "F12") {
    e.preventDefault();
    }
    // Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+Shift+C
    if (e.ctrlKey && e.shiftKey && ["I", "J", "C"].includes(e.key.toUpperCase())) {
    e.preventDefault();
    }
    // Ctrl+U (View Page Source)
    if (e.ctrlKey && e.key.toUpperCase() === "U") {
    e.preventDefault();
    }
    });
</script> 
    <script src="script.js"></script>
</body>
</html> 