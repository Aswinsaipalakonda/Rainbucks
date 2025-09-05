<?php
// Include database connection
require_once 'includes/db.php';

// Fetch dynamic content from database
try {
    $dynamic_content = fetchAll("SELECT * FROM content WHERE status = 'active' ORDER BY created_at DESC");
} catch (Exception $e) {
    $dynamic_content = [];
    error_log("Error fetching content: " . $e->getMessage());
}

// Fetch packages for navigation dropdown
try {
    $nav_packages = fetchAll("SELECT id, name FROM packages WHERE status = 'active' ORDER BY name ASC");
} catch (Exception $e) {
    $nav_packages = [];
    error_log("Error fetching packages for navigation: " . $e->getMessage());
}

// Fetch featured testimonials from database
try {
    $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    // Debug: Log the count of testimonials found
    error_log("Featured testimonials found: " . count($featured_testimonials));

    // Temporary fix: If no testimonials found, add a sample one for testing
    if (empty($featured_testimonials)) {
        $featured_testimonials = [
            [
                'id' => 999,
                'name' => 'Priya Sharma',
                'designation' => 'Digital Marketing Manager',
                'company' => 'Tech Solutions Pvt Ltd',
                'testimonial' => 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing.',
                'rating' => 5,
                'image' => '',
                'featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        error_log("Using sample testimonial for testing");
    }
} catch (Exception $e) {
    // Fallback sample testimonial
    $featured_testimonials = [
        [
            'id' => 999,
            'name' => 'Sample Client',
            'designation' => 'Happy Customer',
            'company' => 'Delhi',
            'testimonial' => 'Great experience with Rainbucks courses. Highly recommended!',
            'rating' => 5,
            'image' => '',
            'featured' => 1,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    error_log("Error fetching testimonials, using fallback: " . $e->getMessage());
}

// Fetch dynamic hero content
try {
    $hero_content = fetchOne("SELECT * FROM content WHERE type = 'hero' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
} catch (Exception $e) {
    $hero_content = null;
    error_log("Error fetching hero content: " . $e->getMessage());
}

// Fetch featured courses
try {
    $featured_courses = fetchAll("SELECT * FROM content WHERE type = 'course' AND status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 3");
} catch (Exception $e) {
    $featured_courses = [];
    error_log("Error fetching featured courses: " . $e->getMessage());
}

// Fetch announcements/banners
try {
    $announcements = fetchAll("SELECT * FROM content WHERE type = 'announcement' AND status = 'active' ORDER BY created_at DESC LIMIT 3");
} catch (Exception $e) {
    $announcements = [];
    error_log("Error fetching announcements: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RainBucks - India's Leading Skill Tech Platform</title>
    <link rel="stylesheet" href="public/styles.css">
    <link rel="shortcut icon" href="public/img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        /* Additional styles for dynamic content section */
        .dynamic-content-section {
            padding: 60px 20px;
            background: #f8f9fa;
        }
        
        .dynamic-content-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dynamic-content-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .dynamic-content-header h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .dynamic-content-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .dynamic-content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .dynamic-content-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dynamic-content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        
        .dynamic-content-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        
        .dynamic-content-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .dynamic-content-card:hover .dynamic-content-image img {
            transform: scale(1.05);
        }
        
        .dynamic-content-body {
            padding: 1.5rem;
        }
        
        .dynamic-content-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .dynamic-content-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .dynamic-content-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #888;
        }
        

        
        .no-content {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .no-content i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        @media (max-width: 768px) {
            .dynamic-content-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .dynamic-content-header h2 {
                font-size: 2rem;
            }
            

        }
          /* Outer Section with curved gradient */
    .cta-section {
        background: var(--highlight);
        margin-top: 40px;
        padding: 100px 20px;
        border-radius: 150px 0 150px 0;
        margin-bottom: 40px;
        display: flex;
        justify-content: center;
    }
    
    /* Inner Card */
    .cta-box {
        background: var(--header-footer);
        color: white;
        border-radius: 40px;
        padding: 60px 40px;
        text-align: center;
        max-width: 800px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }
    
    .cta-box h2 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 30px;
    }
    
    /* CTA Button */
    .cta-button {
        background: white;
        color: black;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 30px;
        text-decoration: none;
        transition: background 0.3s ease, color 0.3s ease;
    }
    
    .cta-button:hover {
        background: var(--accent-green);
        color: white;
    }
  

/* Footer */
.footer {
    background: var(--header-footer);
    color: var(--text-light);
    padding: 4rem 0 2rem;
}

.footer-content {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.footer-logo i {
    margin-right: 0.5rem;
    font-size: 1.8rem;
    color: var(--highlight);
}

.footer-section p {
    color: var(--highlight);
    margin-bottom: 1.5rem;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    width: 40px;
    height: 40px;
    background: var(--accent-green);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: var(--highlight);
    transform: translateY(-2px);
}

.footer-section h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 0.5rem;
}

.footer-section ul li a {
    color: var(--highlight);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: var(--text-light);
}

.footer-bottom {
    border-top: 1px solid var(--highlight);
    padding-top: 2rem;
    text-align: center;
    color: var(--highlight);
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

    </style>
</head>
<body>


    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <div class="logo-icon">
                        <img src="public/img/logo.png" alt="RainBucks Logo" style="height:32px; width:auto;">
                    </div>
                    <span>Rainbucks</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="public/about.php" class="nav-link">About Us</a>
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
                                    $file_path = "public/package/dynamic.php?package=" . $url_name;
                                    echo '<a href="' . $file_path . '" class="dropdown-item">' . htmlspecialchars($fixed_name) . '</a>';
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
                            $file_path = "public/package/dynamic.php?package=" . $url_name;
                            echo '<a href="' . $file_path . '" class="dropdown-item">' . htmlspecialchars($pkg['name']) . '</a>';
                        }
                        ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="public/contact.php" class="nav-link">Contact Us</a>
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

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-container">
            <!-- Left Side - Text Content -->
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-icon">
                        <i class="fas fa"></i>
                    </div>
                    <h1>
                        <?php if (!empty($hero_content['title'])): ?>
                            <?php echo nl2br(htmlspecialchars($hero_content['title'])); ?>
                        <?php else: ?>
                            Learn New Skills,<br>
                            Build Your Future<br>
                            <span class="brand-highlight">with Rainbucks!</span>
                        <?php endif; ?>
                    </h1>

                    <?php if (!empty($hero_content['description'])): ?>
                    <p style="font-size: 1.2rem; color: #666; margin: 20px 0; max-width: 500px;">
                        <?php echo htmlspecialchars($hero_content['description']); ?>
                    </p>
                    <?php endif; ?>
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="btn btn-primary btn-large">Get Started</a>
                </div>

                <!-- Decorative Elements -->
                <div class="decorative-shapes">
                    <div class="shape shape-1"></div>
                    <div class="shape shape-2"></div>
                    <div class="shape shape-3"></div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="hero-image-section">
                <div class="image-container">
                    <div class="dashed-border">
                        <div class="circular-frame">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=776&q=80" alt="Student learning" class="hero-image">
                        </div>
                    </div>
                </div>

                <!-- Decorative Arc -->
                <div class="decorative-arc"></div>
            </div>
        </div>
    </section>



    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-item">
                <h3 class="counter" data-target="300">0</h3>
                <p>Happy Students</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="20">0</h3>
                <p>Courses</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="450">0</h3>
                <p>Hours Of Support</p>
            </div>
            <div class="stat-item">
                <h3 class="counter" data-target="98" data-suffix="%">0%</h3>
                <p>Positive Feedback</p>
            </div>
        </div>
    </section>

    <!-- Transform Skills Section -->
    <section class="transform-section">
        <div class="transform-container">
            <!-- Left Side - Text Content -->
            <div class="transform-content">
                <div class="transform-text">
                    <h2>
                        Transform Your Skills,<br>
                        <span class="transform-highlight">Transform Your Future</span>
                    </h2>
                    <p>
                        Rainbucks is your one-stop destination for mastering 21st-century skills and advancing your career. Based in India, we empower learners through carefully crafted online courses designed to help you excel in digital marketing, personal development, business communication, and much more. Whether you're looking to start a side hustle, grow your career, or learn something new, Rainbucks has you covered.
                    </p>
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="btn btn-primary btn-large">Get Started</a>
                </div>

                <!-- Decorative Elements -->
                <div class="transform-shapes">
                    <div class="transform-shape transform-shape-1"></div>
                    <div class="transform-shape transform-shape-2"></div>
                    <div class="transform-shape transform-shape-3"></div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="transform-image-section">
                <div class="transform-image-container">
                    <div class="transform-dashed-border">
                        <div class="transform-gradient-circle"></div>
                        <div class="transform-circular-frame">
                            <img src="https://m.media-amazon.com/images/I/719gu2DXdTL._AC_UF350,350_QL80_.jpg" alt="Student with materials" class="transform-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Featured Courses Section -->
    <section class="featured-courses">
        <div class="featured-courses-container">
            <div class="featured-courses-header">
                <h2>Featured Courses</h2>
                <div class="featured-courses-indicators">
                    <span class="indicator active"></span>
                    <span class="indicator"></span>
                    <span class="indicator"></span>
                </div>
                <p>Explore our high-value packages that unlock all courses, giving you the ultimate edge in today's competitive world.</p>
            </div>
            
            <div class="courses-scroll-container">
                <div class="courses-scroll">
                    <!-- Course Card 1 -->
                    <a href="public/course/details.php?id=1" class="course-card" style="text-decoration: none; color: inherit; display: block;">

                        <div class="course-image">
                            <img src="public/img/courses/SOCIAL MEDIA.jpg" alt="Social Media Marketing Book">
                        </div>
                        <div class="course-category">
                            <span>Social Media Marketing</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>Social Media Marketing</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>30 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </a>

                    <!-- Course Card 2 -->
                    <a href="public/course/details.php?id=2" class="course-card" style="text-decoration: none; color: inherit; display: block;">

                        <div class="course-image">
                            <img src="./public/img/courses/MS WORD HINDI.jpg" alt="MS Word Learning Book">
                        </div>
                        <div class="course-category">
                            <span>MS Word Learning</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>MS Word Learning</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>30 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </a>

                    <!-- Course Card 3 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/COPY WRITING HINDI.jpg" alt="Copy Writing">
                        </div>
                        <div class="course-category">
                            <span>Copy Writing</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>Copy Writing</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>25 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 4 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/E-MAIL MARKETING HINDI.jpg" alt="E-mail Marketing">
                        </div>
                        <div class="course-category">
                            <span>E-mail Marketing</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>E-mail Marketing</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>20 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 5 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/FACEBOOK MARKETING HINDI.jpg" alt="Facebook Marketing">
                        </div>
                        <div class="course-category">
                            <span>Facebook Marketing</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>Facebook Marketing</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>18 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 6 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/HOW TO CRACK  JOB INTERVIEW HINDI.jpg" alt="How to Crack Job Interviews">
                        </div>
                        <div class="course-category">
                            <span>Job Interviews</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>How to Crack Job Interviews</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>22 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 7 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/MS EXCEL  ADVANCED.jpg" alt="MS Excel Advanced">
                        </div>
                        <div class="course-category">
                            <span>MS Excel Advanced</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>MS Excel Advanced</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>30 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 8 -->
                    <div class="course-card">
                       
                        <div class="course-image">
                            <img src="./public/img/courses/PERSONAL FINANCE HINDI.jpg" alt="Personal Finance">
                        </div>
                        <div class="course-category">
                            <span>Personal Finance</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>Personal Finance</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>24 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 9 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/PERSONALITY DEVELOPMENT HINDI.jpg" alt="Personality Development">
                        </div>
                        <div class="course-category">
                            <span>Personality Development</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>Personality Development</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>26 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>

                    <!-- Course Card 10 -->
                    <div class="course-card">
                        
                        <div class="course-image">
                            <img src="./public/img/courses/POWER POINT HINDI.jpg" alt="PowerPoint">
                        </div>
                        <div class="course-category">
                            <span>PowerPoint</span>
                        </div>
                        <div class="course-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                        <div class="course-title">
                            <h3>PowerPoint</h3>
                        </div>
                        <div class="course-details">
                            <i class="fas fa-book-open"></i>
                            <span>18 Classes</span>
                        </div>
                        <div class="course-footer">
                            <span>Powered by Rainbucks</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <!-- Explore Our Courses Section -->
    <section class="explore-courses">
        <div class="explore-courses-container">
            <!-- Left Side - Text Content -->
            <div class="explore-content">
                <h2>
                    Explore Our <span class="courses-highlight">Courses</span>
                </h2>
                <p>At Rainbucks, we offer 20+ courses designed to boost your skills, confidence, and career opportunities.</p>
                
                <h3>Popular Categories</h3>
                <div class="categories-grid">
                    <div class="categories-column">
                        <div class="category-item">
                            <span class="category-bullet"></span>
                            <span>Digital Marketing Mastery</span>
                        </div>
                        <div class="category-item">
                            <span class="category-bullet"></span>
                            <span>Business & Productivity Tools</span>
                        </div>
                        <div class="category-item">
                            <span class="category-bullet"></span>
                            <span>Personal Development & Communication</span>
                        </div>
                    </div>
                    <div class="categories-column">
                        <div class="category-item">
                            <span class="category-bullet"></span>
                            <span>Creative Content Creation</span>
                        </div>
                        <div class="category-item">
                            <span class="category-bullet"></span>
                            <span>Career & Financial Growth</span>
                        </div>
                    </div>
                </div>
                
                <a href="public/package/dynamic.php?package=starter" class="btn btn-primary explore-btn">View All Courses</a>
            </div>
            
            <!-- Right Side - Image -->
            <div class="explore-image-section">
                <div class="explore-image-container">
                    <div class="explore-dashed-border">
                        <div class="explore-circular-frame">
                            <img src="https://images.rawpixel.com/image_800/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDI1LTAxL3Jhd3BpeGVsX29mZmljZV8zM19waG90b19yZWFsX2luZGlhbl9idXNpbmVzc193b21hbl90aGVfaW1hZ2VfaV85Y2E0ZTUwYy02Yzg4LTQwNTYtODg5Mi1kYTk0MDY2YTc4NDRfMi5qcGc.jpg" alt="Student with books" class="explore-image">
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </section>

    <!-- Second Hero Section -->
    <section class="hero-section-2">
        <div class="hero-2-container">
            <div class="hero-2-content">
                <h2>Why Choose Rainbucks?</h2>
                <p>At Rainbucks, we believe in the power of knowledge and its ability to open doors to endless possibilities.Founded in India, our mission is to make high-quality education accessible to everyone.</p>
                <div class="hero-2-stats">
                    <div class="stat">
                        <h3>100</h3>
                        <p>Happy Students</p>
                    </div>
                    <div class="stat">
                        <h3>98%</h3>
                        <p>Average Annual Return</p>
                    </div>
                    
                </div>
                <a href="about.php"  class="btn btn-primary">Know More</a>
            </div>
            <div class="hero-2-image">
                <div class="floating-card">
                    <i class="fas fa-coins"></i>
                    <h4>Affordable Pricing</h4>
                    <p>Learning that doesn't break the bank.</p>
                </div>
                <div class="floating-card">
                    <i class="fas fa-book-open"></i>
                    <h4>Expert-Led Courses</h4>
                    <p>Learn from top industry professionals.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Logo scroller removed -->
      
    <div class="logo-scroller-wrapper">
        <div class="logo-scroller">
          <div class="logo-track">
            <a href="https://example.com/hm" target="_blank"><img src="public/img/logos/hm.jpg" alt="Logo 1"></a>
            <a href="https://example.com/repni" target="_blank"><img src="./public/img/logos/repni.jpg" alt="Logo 2"></a>
            <a href="https://example.com/qoura" target="_blank"><img src="./public/img/logos/qoura.png" alt="Logo 3"></a>
            <a href="https://example.com/dailyhunt" target="_blank"><img src="./public/img/logos/Daily_hunt.jpg" alt="Logo 4"></a>
            <a href="https://example.com/fb" target="_blank"><img src="./public/img/logos/fb.jpg" alt="Logo 5"></a>
            <a href="https://example.com/medium" target="_blank"><img src="./public/img/logos/medium.jpg" alt="Logo 6"></a>
            <a href="https://example.com/googlenews" target="_blank"><img src="./public/img/logos/googlenews.jpg" alt="Logo 7"></a>
            <!-- Duplicate logos for seamless loop -->
            <a href="https://example.com/hm" target="_blank"><img src="./public/img/logos/hm.jpg" alt="Logo 1"></a>
            <a href="https://example.com/repni" target="_blank"><img src="./public/img/logos/repni.jpg" alt="Logo 2"></a>
            <a href="https://example.com/qoura" target="_blank"><img src="./public/img/logos/qoura.png" alt="Logo 3"></a>
            <a href="https://example.com/dailyhunt" target="_blank"><img src="./public/img/logos/Daily_hunt.jpg" alt="Logo 4"></a>
            <a href="https://example.com/fb" target="_blank"><img src="./public/img/logos/fb.jpg" alt="Logo 5"></a>
            <a href="https://example.com/medium" target="_blank"><img src="./public/img/logos/medium.jpg" alt="Logo 6"></a>
            <a href="https://example.com/googlenews" target="_blank"><img src="./public/img/logos/googlenews.jpg" alt="Logo 7"></a>
          </div>
        </div>
      </div>


<!-- WhatsApp Floating Button -->
<a href="https://wa.me/919876543210?text=Hii,%20Rainbucks.I'm%20interested%20in%20your%20courses." 
   class="whatsapp-float" target="_blank">
   <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" 
        alt="WhatsApp" width="50" height="50">
</a>

<style>
.whatsapp-float {
  position: fixed;
  width: 60px;
  height: 60px;
  bottom: 20px;
  right: 20px;
  background-color: #25d366;
  border-radius: 50%;
  text-align: center;
  box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.whatsapp-float img {
  width: 35px;
  height: 35px;
}
.whatsapp-float:hover {
  background-color: #1ebe5d;
}
</style>

    <!-- New Features Section -->
    <section id="features" class="new-features">
        <div class="container">
            <div class="section-header">
                <h2>New Features</h2>
            </div>
            
            <div class="features-grid">
                <!-- Row 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Interactive Learning</h3>
                    <p>Engage with quizzes, case studies, and hands-on projects.</p>
                    
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile-Friendly Platform</h3>
                    <p>Learn on the go with our fully optimized mobile platform.</p>
                    
                </div>
                
                <!-- Row 2 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Certification of Completion</h3>
                    <p>Boost your resume with certificates for every course completed.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3>Expert Mentorship</h3>
                    <p>Multi-factor authentication, biometric login, and bank-level encryption ensure your investments and personal data are always protected.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section">
        <div class="container">
            <div class="section-header">
                <h2>Packages</h2>
                <p>We understand that every learner is unique. That’s why Rainbucks offers a range of packages to suit your goals and budget.</p>
            </div>
            
            <div class="packages-grid">
                <!-- Basic Package -->
                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Starter Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>3,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Access to 4 courses</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Ideal for Beginners</span>
                        </li>
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Professional Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>5,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Access to 8 courses</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Ideal for Beginners</span>
                        </li>
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Advanced Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>7,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Access to 12 courses</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Designed for career-oriented learners</span>
                        </li>
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Expert Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>9,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Access to 16 courses</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Includes exclusive live session and mentorship</span>
                        </li>
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>

                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Ultimate Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>11,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Access to 20 courses + future updates</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span> Personalized career guidance</span>
                        </li>
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>
                
                <div class="package-card">
                    <div class="package-header">
                        <h3 class="package-name">Super Ultimate Package</h3>
                        <div class="package-price">
                            <span class="currency">₹</span>20,000
                        </div>
                        
                    </div>
                    
                    <ul class="package-features">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Get started with ChatGPT, from signup to smart conversations.</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Use it for tasks, chatbots, translation, and content creation.</span>
                        </li>
                        
                    </ul>
                    
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="package-btn">Get Started</a>
                </div>
                
            </div>
        </div>
    </section>

    <!-- What Our Clients Say Section -->
    <section class="testimonials-section" style="padding: 80px 20px; background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); position: relative;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; position: relative;">
            <div class="section-header" style="text-align: center; margin-bottom: 60px;">
                <h2 style="font-size: 2.5rem; color: white; margin-bottom: 1rem; font-weight: 700;">What Our Clients Say</h2>
                <p style="font-size: 1.1rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto;">
                    Hear from our successful students who have transformed their careers with our courses
                </p>
            </div>

    <?php if (!empty($featured_testimonials)): ?>
    <div class="swiper mySwiper" style="max-width: 800px; margin: 0 auto; padding: 20px;">
        <div class="swiper-wrapper">
            <?php foreach ($featured_testimonials as $testimonial): ?>
                <div class="swiper-slide">
                    <div style="background: white; border-radius: 15px; padding: 30px; margin: 20px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
                        <!-- Client Image -->
                        <div style="margin-bottom: 20px;">
                            <?php if (!empty($testimonial['image'])): ?>
                                <img src="assets/images/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>"
                                     alt="<?php echo htmlspecialchars($testimonial['name']); ?>"
                                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #4CAF50;">
                            <?php else: ?>
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: #4CAF50; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                                    <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Client Name -->
                        <h3 style="color: #333; margin-bottom: 10px; font-size: 1.3rem;">
                            <?php echo htmlspecialchars($testimonial['name']); ?>
                        </h3>

                        <!-- Company/Designation -->
                        <p style="color: #666; margin-bottom: 15px;">
                            <?php
                            $location_parts = [];
                            if (!empty($testimonial['company'])) {
                                $location_parts[] = $testimonial['company'];
                            } elseif (!empty($testimonial['designation'])) {
                                $location_parts[] = $testimonial['designation'];
                            }
                            echo htmlspecialchars(implode(', ', $location_parts) ?: 'Delhi');
                            ?>
                        </p>

                        <!-- Rating -->
                        <div style="margin-bottom: 20px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span style="color: <?php echo ($i <= $testimonial['rating']) ? '#FFD700' : '#ddd'; ?>; font-size: 20px;">★</span>
                            <?php endfor; ?>
                        </div>

                        <!-- Testimonial Text -->
                        <blockquote style="font-size: 1.1rem; line-height: 1.6; color: #555; font-style: italic; margin: 0;">
                            "<?php echo htmlspecialchars($testimonial['testimonial']); ?>"
                        </blockquote>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Swiper Pagination & Navigation -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
<?php else: ?>
    <div style="text-align: center; color: rgba(255,255,255,0.8); font-size: 1.1rem;">
        <p>No featured testimonials available yet.</p>
        <p style="font-size: 0.9rem; margin-top: 10px;">Check back soon for inspiring success stories from our students!</p>
    </div>
<?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-box">
            <h2>Join thousands of learners and start building your skills today!</h2>
            <a href="public/package/dynamic.php?package=starter" class="cta-button">Get Started Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="custom-footer-container">
            <div class="custom-footer-section brand">
                <div class="custom-footer-logo">
                    <img src="public/img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto; vertical-align:middle;">
                    <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;"><span>Rainbucks</span></a>
                </div>
                <p>Explore our high-value packages that unlock all courses, giving you the ultimate edge in today's competitive world.</p>
            </div>
            <div class="custom-footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="public/privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="public/terms-conditions.php">Terms & Conditions</a></li>
                    <li><a href="public/cancellation-refund.php">Cancellation & Refund</a></li>
                    <li><a href="public/end-user-agreement.php">End User Agreement</a></li>
                    <li><a href="public/disclaimer.php">Disclaimer</a></li>
                    
                </ul>
            </div>
            <div class="custom-footer-section company">
                <h4>Company</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="public/about.php">About us</a></li>
                    <li><a href="public/package/dynamic.php?package=starter">Courses</a></li>
                    <li><a href="public/contact.php">Contact us</a></li>
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



    <script src="public/script.js"></script>

    <!-- Testimonials Slider JavaScript -->
    <script>
        let currentTestimonial = 0;
        const totalTestimonials = <?php echo count($featured_testimonials); ?>;

        function updateTestimonialSlider() {
            const track = document.getElementById('testimonialsTrack');
            const dots = document.querySelectorAll('.testimonial-dot');

            if (track) {
                track.style.transform = `translateX(-${currentTestimonial * 100}%)`;
            }

            // Update dots
            dots.forEach((dot, index) => {
                dot.style.background = index === currentTestimonial ? 'white' : 'rgba(255,255,255,0.5)';
            });
        }

        function nextTestimonial() {
            if (totalTestimonials > 1) {
                currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
                updateTestimonialSlider();
            }
        }

        function previousTestimonial() {
            if (totalTestimonials > 1) {
                currentTestimonial = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
                updateTestimonialSlider();
            }
        }

        function goToTestimonial(index) {
            if (totalTestimonials > 1) {
                currentTestimonial = index;
                updateTestimonialSlider();
            }
        }

        // Auto-slide testimonials every 5 seconds
        if (totalTestimonials > 1) {
            setInterval(nextTestimonial, 5000);
        }

        // Add hover effects to navigation buttons
        document.addEventListener('DOMContentLoaded', function() {
            const navButtons = document.querySelectorAll('.testimonials-section button:not(.testimonial-dot)');
            navButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.background = 'white';
                    this.style.transform = this.style.transform.includes('translateY') ?
                        this.style.transform.replace('translateY(-50%)', 'translateY(-50%) scale(1.1)') :
                        'scale(1.1)';
                    this.style.boxShadow = '0 12px 35px rgba(0,0,0,0.2)';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.background = 'rgba(255,255,255,0.95)';
                    this.style.transform = this.style.transform.replace(' scale(1.1)', '');
                    this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
                });
            });

            // Add hover effects to dots
            const dots = document.querySelectorAll('.testimonial-dot');
            dots.forEach(dot => {
                dot.addEventListener('mouseenter', function() {
                    if (this.style.background !== 'white') {
                        this.style.background = 'rgba(255,255,255,0.8)';
                    }
                    this.style.transform = 'scale(1.2)';
                });

                dot.addEventListener('mouseleave', function() {
                    if (this.style.background !== 'white') {
                        this.style.background = 'rgba(255,255,255,0.5)';
                    }
                    this.style.transform = 'scale(1)';
                });
            });
        });
          var swiper = new Swiper(".mySwiper", {
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });

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
</body>
</html>
