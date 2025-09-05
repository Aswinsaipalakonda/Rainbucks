<?php
// Dynamic Course Details Page
require_once '../../includes/db.php';

// Get course ID from URL parameter
$course_id = $_GET['id'] ?? '';

if (empty($course_id) || !is_numeric($course_id)) {
    header('Location: ../../index.php');
    exit();
}

// Fetch course details with package information
try {
    $course = fetchOne("
        SELECT c.*, p.name as package_name, p.price as package_price, p.currency, p.features as package_features
        FROM courses c
        LEFT JOIN packages p ON c.package_id = p.id
        WHERE c.id = ? AND c.status = 'active'
    ", [$course_id]);
    
    if (!$course) {
        header('Location: ../../index.php');
        exit();
    }
} catch (Exception $e) {
    header('Location: ../../index.php');
    exit();
}

// Fetch related courses from the same package
try {
    $related_courses = fetchAll("
        SELECT * FROM courses 
        WHERE package_id = ? AND id != ? AND status = 'active' 
        ORDER BY sort_order ASC, id ASC 
        LIMIT 4
    ", [$course['package_id'], $course_id]);
} catch (Exception $e) {
    $related_courses = [];
}

// Fetch packages for navigation dropdown
try {
    $nav_packages = fetchAll("SELECT id, name FROM packages WHERE status = 'active' ORDER BY name ASC");
} catch (Exception $e) {
    $nav_packages = [];
}

// Generate course features based on package features
$course_features = [];
if (!empty($course['package_features'])) {
    $course_features = explode(',', $course['package_features']);
}

// Add some default course-specific features
$default_features = [
    'Lifetime Access',
    'Mobile & Desktop Compatible',
    'Certificate of Completion',
    '24/7 Support',
    'Practical Assignments',
    'Expert Instructor'
];

$course_features = array_merge($course_features, $default_features);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Rainbucks</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Course Details Page Styles */
        .course-hero-section {
            padding: 120px 0 60px 0;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }

        .course-hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 500px;
        }

        .course-info h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .course-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .course-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .course-rating .stars {
            color: #FFD700;
            font-size: 1.1rem;
        }

        .course-category {
            background: rgba(255,255,255,0.2);
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .course-description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .course-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .highlight-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .highlight-item i {
            color: #4CAF50;
            font-size: 1rem;
        }

        /* Purchase Card */
        .purchase-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            color: #333;
            max-width: 1200px;
            width: 100%;
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 3rem;
            align-items: start;
        }

        /* Left Column - Course Image */
        .course-image-container {
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 10px;
            background: #494a4bff;
        }

        .course-image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 10px;
            background: white;
        }

        /* Right Column - Course Details */
        .course-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .course-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin: 0 0 0.5rem 0;
            line-height: 1.3;
        }

        .course-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .rating-section {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .rating-badge {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            background: #4CAF50;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .rating-text {
            color: #666;
            font-size: 0.9rem;
        }

        .special-price-label {
            color: #4CAF50;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .price-section {
            margin-bottom: 1.5rem;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .package-name {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .buy-now-btn {
            width: 30%;
            padding: 1rem 2rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-bottom: 1rem;
        }

        .buy-now-btn:hover {
            background: #45a049;
        }

        .features-list {
            list-style: none;
            padding: 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.8rem;
            font-size: 0.9rem;
        }

        .features-list li i {
            color: #4CAF50;
            font-size: 0.8rem;
        }

        .offers-section {
            margin-bottom: 1.5rem;
        }

        .offers-section h4 {
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .offer-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.85rem;
        }

        .offer-icon {
            color: #4CAF50;
            font-size: 0.8rem;
            margin-top: 0.1rem;
        }

        .offer-text {
            color: #333;
            line-height: 1.4;
        }

        .offer-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .delivery-section {
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .delivery-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .delivery-label {
            font-size: 0.9rem;
            color: #666;
            min-width: 80px;
        }

        .pincode-input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .check-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Course Content Section */
        .course-content-section {
            padding: 60px 0;
            background: white;
        }

        .course-content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .content-tabs {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 2rem;
        }

        .tab-button {
            padding: 1rem 2rem;
            background: none;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            color: #4CAF50;
            border-bottom-color: #4CAF50;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Related Courses */
        .related-courses-section {
            padding: 60px 0;
            background: #f8f9fa;
        }

        .related-courses-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .related-courses-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
            margin-top: 3rem;
        }

        .related-course-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .related-course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .related-course-image {
            height: 250px;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
            position: relative;
        }

        .related-course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-course-card:hover .related-course-image img {
            transform: scale(1.08);
        }

        .related-course-content {
            padding: 1.8rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .related-course-content h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #333;
            font-weight: 600;
            line-height: 1.4;
        }

        .related-course-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-size: 0.95rem;
            color: #666;
            margin-top: auto;
        }

        .related-course-meta span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .related-course-meta i {
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .related-courses-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
        }

        @media (max-width: 1024px) {
            .course-hero-container {
                max-width: 1000px;
            }

            .purchase-card {
                grid-template-columns: 350px 1fr;
                gap: 2rem;
            }

            .course-image-container {
                height: 350px;
            }

            .related-courses-container {
                max-width: 1000px;
            }
        }

        @media (max-width: 768px) {
            .course-hero-section {
                min-height: auto;
                padding: 100px 0 40px 0;
            }

            .course-hero-container {
                padding: 0 10px;
                max-width: 100%;
            }

            .purchase-card {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                max-width: 100%;
            }

            .course-image-container {
                width: 100%;
                height: 300px;
            }

            .course-title {
                font-size: 1.5rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .content-tabs {
                overflow-x: auto;
            }

            .tab-button {
                white-space: nowrap;
                padding: 1rem 1.5rem;
            }

            .related-courses-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .related-course-image {
                height: 200px;
            }

            .related-course-content {
                padding: 1.5rem;
            }

            .related-course-content h3 {
                font-size: 1.1rem;
            }
        }
        @media (max-width: 480px) {
            .course-image-container {
                height: 250px;
            }

            .course-description {
                font-size: 0.95rem;
            }

            .buy-now-btn {
                width: 100%;
                font-size: 1rem;
                padding: 0.8rem 1rem;
            }
         }

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../../index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <div class="logo-icon">
                        <img src="../img/logo.png" alt="RainBucks Logo" style="height:32px; width:auto;">
                    </div>
                    <span>Rainbucks</span>
                </a>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="../../index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="../about.php" class="nav-link">About Us</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#courses" class="nav-link">All Courses</a>
                    <div class="dropdown-menu">
                        <?php if (!empty($nav_packages)) {
                            // Fixed order for main packages
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
                                        $file_path = "../package/dynamic.php?package=" . $url_name;
                                        echo '<a href="' . $file_path . '" class="dropdown-item">' . htmlspecialchars($fixed_name) . '</a>';
                                        $shown[] = $fixed_name;
                                        break;
                                    }
                                }
                            }
                            // Show any new packages (not in fixed order) alphabetically
                            $new_packages = array_filter($nav_packages, function($pkg) use ($shown) {
                                return !in_array($pkg['name'], $shown);
                            });
                            usort($new_packages, function($a, $b) {
                                return strcmp($a['name'], $b['name']);
                            });
                            foreach ($new_packages as $pkg) {
                                $url_name = strtolower(str_replace([' ', 'Package'], ['', ''], $pkg['name']));
                                $file_path = "../package/dynamic.php?package=" . $url_name;
                                echo '<a href="' . $file_path . '" class="dropdown-item">' . htmlspecialchars($pkg['name']) . '</a>';
                            }
                        } else { ?>
                            <a href="#courses" class="dropdown-item">No packages available</a>
                        <?php } ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="../contact.php" class="nav-link">Contact Us</a>
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

    <!-- Course Hero Section -->
    <section class="course-hero-section">
        <div class="course-hero-container">
            <!-- Purchase Card with Image Left, Content Right -->
            <div class="purchase-card">
                <!-- Course Image (Left Side) -->
                <div class="course-image-container">
                    <?php if (!empty($course['image'])): ?>
                        <img src="../../assets/images/courses/<?php echo htmlspecialchars($course['image']); ?>"
                             alt="<?php echo htmlspecialchars($course['title']); ?>">
                    <?php else: ?>
                        <div style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #666; border-radius: 10px;">
                            <i class="fas fa-book" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Course Content (Right Side) -->
                <div class="course-content">
                    <!-- Course Title -->
                    <h1 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h1>

                    <!-- Course Description -->
                    <div class="course-description">
                        <?php echo htmlspecialchars($course['description']); ?>
                    </div>

                    <!-- Rating Section -->
                    <div class="rating-section">
                        <div class="rating-badge">
                            <span>4.6</span>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="rating-text">2,812 Ratings & 208 Reviews</span>
                    </div>


                    <!-- Buy Now Button -->
                    <a href="https://course.Brainbucks.org/learn/account/signup" class="buy-now-btn">
                        <i class="fas fa-shopping-cart"></i> Buy Now
                    </a>

                </div>
            </div>
        </div>
    </section>



    <!-- Course Content Section -->
    <section class="course-content-section">
        <div class="course-content-container">
            <div class="content-tabs">
                <button class="tab-button active" onclick="showTab('overview')">Overview</button>
                <button class="tab-button" onclick="showTab('curriculum')">Curriculum</button>
                <button class="tab-button" onclick="showTab('reviews')">Reviews</button>
            </div>

            <div id="overview" class="tab-content active">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Course Overview</h3>
                <div style="line-height: 1.8; color: #555;">
                    <p>This comprehensive course is designed to provide you with practical skills and knowledge that you can immediately apply in your career. Our expert instructors have crafted a curriculum that combines theoretical foundations with hands-on experience.</p>

                    <h4 style="margin: 2rem 0 1rem 0; color: #333;">What You'll Learn:</h4>
                    <ul style="margin-left: 1.5rem; line-height: 2;">
                        <li>Master the fundamental concepts and principles</li>
                        <li>Apply practical techniques in real-world scenarios</li>
                        <li>Develop professional-level skills and expertise</li>
                        <li>Build a portfolio of projects to showcase your abilities</li>
                        <li>Understand industry best practices and standards</li>
                        <li>Gain confidence to advance your career</li>
                    </ul>

                    <h4 style="margin: 2rem 0 1rem 0; color: #333;">Who This Course Is For:</h4>
                    <ul style="margin-left: 1.5rem; line-height: 2;">
                        <li>Beginners looking to start their journey</li>
                        <li>Professionals wanting to upgrade their skills</li>
                        <li>Students seeking practical knowledge</li>
                        <li>Career changers exploring new opportunities</li>
                    </ul>
                </div>
            </div>

            <div id="curriculum" class="tab-content">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Course Curriculum</h3>
                <div style="line-height: 1.8; color: #555;">
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;">
                        <div style="background: #f8f9fa; padding: 1rem; border-bottom: 1px solid #e0e0e0;">
                            <h4 style="margin: 0; color: #333;">Module 1: Introduction & Fundamentals</h4>
                            <span style="color: #666; font-size: 0.9rem;">5 lessons • 2 hours</span>
                        </div>
                        <div style="padding: 1rem;">
                            <ul style="margin: 0; list-style: none; padding: 0;">
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Getting Started - Course Introduction
                                </li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Understanding the Basics
                                </li>
                                <li style="padding: 0.5rem 0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Setting Up Your Environment
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; margin-top: 1rem;">
                        <div style="background: #f8f9fa; padding: 1rem; border-bottom: 1px solid #e0e0e0;">
                            <h4 style="margin: 0; color: #333;">Module 2: Practical Applications</h4>
                            <span style="color: #666; font-size: 0.9rem;">8 lessons • 4 hours</span>
                        </div>
                        <div style="padding: 1rem;">
                            <ul style="margin: 0; list-style: none; padding: 0;">
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Hands-on Project 1
                                </li>
                                <li style="padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Advanced Techniques
                                </li>
                                <li style="padding: 0.5rem 0;">
                                    <i class="fas fa-play-circle" style="color: #4CAF50; margin-right: 0.5rem;"></i>
                                    Real-world Case Studies
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>



            <div id="reviews" class="tab-content">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Student Reviews</h3>
                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <span style="font-size: 2rem; font-weight: bold; color: #4CAF50;"><?php echo number_format($course['rating'], 1); ?></span>
                        <div>
                            <div style="color: #FFD700; font-size: 1.2rem; margin-bottom: 0.3rem;">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="color: <?php echo $i <= floor($course['rating']) ? '#FFD700' : '#ddd'; ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <span style="color: #666;">Based on <?php echo number_format(rand(100, 1000)); ?> reviews</span>
                        </div>
                    </div>
                </div>

                <!-- Sample Reviews -->
                <div>
                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h5 style="margin: 0; color: #333;">Priya Sharma</h5>
                                <span style="color: #666; font-size: 0.9rem;">2 weeks ago</span>
                            </div>
                            <div style="color: #FFD700;">★★★★★</div>
                        </div>
                        <p style="margin: 0; line-height: 1.6; color: #555;">
                            "Excellent course! The instructor explains everything clearly and the practical examples are very helpful.
                            I was able to apply what I learned immediately in my job."
                        </p>
                    </div>

                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h5 style="margin: 0; color: #333;">Rahul Kumar</h5>
                                <span style="color: #666; font-size: 0.9rem;">1 month ago</span>
                            </div>
                            <div style="color: #FFD700;">★★★★★</div>
                        </div>
                        <p style="margin: 0; line-height: 1.6; color: #555;">
                            "Great value for money! The course content is comprehensive and up-to-date.
                            Highly recommend for anyone looking to advance their skills."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Courses Section -->
    <?php if (!empty($related_courses)): ?>
    <section class="related-courses-section">
        <div class="related-courses-container">
            <h2 style="text-align: center; margin-bottom: 1rem; color: #333;">More Courses in <?php echo htmlspecialchars($course['package_name']); ?></h2>
            <p style="text-align: center; color: #666; margin-bottom: 3rem;">Expand your skills with these related courses</p>

            <div class="related-courses-grid">
                <?php foreach ($related_courses as $related_course): ?>
                    <a href="details.php?id=<?php echo $related_course['id']; ?>" class="related-course-card">
                        <div class="related-course-image">
                            <?php if (!empty($related_course['image'])): ?>
                                <img src="../../assets/images/courses/<?php echo htmlspecialchars($related_course['image']); ?>"
                                     alt="<?php echo htmlspecialchars($related_course['title']); ?>">
                            <?php else: ?>
                                <div style="height: 180px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #666;">
                                    <i class="fas fa-book" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="related-course-content">
                            <h3><?php echo htmlspecialchars($related_course['title']); ?></h3>
                            <div class="related-course-meta">
                                <span><i class="fas fa-star" style="color: #FFD700;"></i> <?php echo number_format($related_course['rating'], 1); ?></span>
                                <?php if (!empty($related_course['duration'])): ?>
                                <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($related_course['duration']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="custom-footer-container">
            <div class="custom-footer-section brand">
                <div class="custom-footer-logo">
                    <img src="../img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto; vertical-align:middle;">
                    <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;"><span>Rainbucks</span></a>
                </div>
                <p>Explore our high-value packages that unlock all courses, giving you the ultimate edge in today's competitive world.</p>
            </div>
            <div class="custom-footer-section links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="../privacy-policy.php">Privacy Policy</a></li>
                    <li><a href="../terms-conditions.php">Terms & Conditions</a></li>
                    <li><a href="../cancellation-refund.php">Cancellation & Refund</a></li>
                    <li><a href="../end-user-agreement.php">End User Agreement</a></li>
                    <li><a href="../disclaimer.php">Disclaimer</a></li>
                    
                </ul>
            </div>
            <div class="custom-footer-section company">
                <h4>Company</h4>
                <ul>
                    <li><a href="../../index.php">Home</a></li>
                    <li><a href="../about.php">About us</a></li>
                    <li><a href="../package/dynamic.php?package=starter">Courses</a></li>
                    <li><a href="../contact.php">Contact us</a></li>
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

    <!-- JavaScript for Tabs -->
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>

    <script src="../script.js"></script>
</body>
</html>
