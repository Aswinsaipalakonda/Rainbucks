<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Dynamic Package Page
try {
    require_once '../../includes/db.php';
    
    // Test database connection
    $test = fetchOne("SELECT 1");
    if (!$test) {
        throw new Exception("Database connection test failed");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Test database connection
try {
    $test_connection = fetchOne("SELECT 1");
    error_log("Database connection successful");
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please check the error logs.");
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Dynamic Package Page
require_once __DIR__ . '/../../includes/db.php';

// Get package name from URL parameter (normalize to lowercase)
$package_param = strtolower(trim($_GET['package'] ?? ''));

if (empty($package_param)) {
    error_log("No package name provided in URL");
    header('Location: ../../index.php');
    exit();
}

// Log the incoming request
error_log("Processing package request: " . $package_param);

// Prepare a normalized version to compare against DB names
$normalized_param = str_replace([' ', '-', '_'], '', $package_param);

// Convert URL-friendly name to database name
// First try the predefined mapping for existing packages
$package_map = [
    'starter' => 'Starter Package',
    'professional' => 'Professional Package',
    'advanced' => 'Advanced Package',
    'expert' => 'Expert Package',
    'ultimate' => 'Ultimate Package',
    'super-ultimate' => 'Super Ultimate Package'
];

$db_package_name = $package_map[$package_param] ?? '';

// If not found in predefined mapping, try to find by URL-friendly conversion
if (empty($db_package_name)) {
    try {
        // Get all active packages and try to match by URL-friendly name
        $all_packages = fetchAll("SELECT name FROM packages WHERE status = 'active'");

        foreach ($all_packages as $pkg) {
            // Build a slug from DB name similar to how links are generated, tolerant to -, _
            $url_friendly = strtolower(str_replace([' ', 'Package', '-', '_'], ['', '', '', ''], $pkg['name']));
            if ($url_friendly === $normalized_param) {
                $db_package_name = $pkg['name'];
                break;
            }
        }
    } catch (Exception $e) {
        error_log("Error finding package: " . $e->getMessage());
    }
}

if (empty($db_package_name)) {
    header('Location: ../../index.php');
    exit();
}

// Fetch package details first
try {
    $package = fetchOne("SELECT * FROM packages WHERE name = ? AND LOWER(status) = 'active'", [$db_package_name]);
    if (!$package) {
        error_log("Package not found in database: " . $db_package_name);
        header('Location: ../../index.php');
        exit();
    }
} catch (Exception $e) {
    error_log("Error loading package details: " . $e->getMessage());
    header('Location: ../../index.php');
    exit();
}

// Fetch courses with robust inclusive mapping (junction table or legacy package_id)
$courses = [];
try {
    $courses = fetchAll("
        SELECT c.*, COALESCE(pc.sort_order, c.sort_order) AS sort_order
        FROM courses c
        LEFT JOIN package_courses pc
          ON c.id = pc.course_id AND pc.package_id = ?
        WHERE c.status = 'active'
          AND (pc.package_id IS NOT NULL OR c.package_id = ?)
        ORDER BY sort_order ASC, c.title ASC
    ", [$package['id'], $package['id']]);
} catch (Exception $e) {
    // Fallback: legacy-only mapping (handles case when package_courses doesn't exist)
    error_log("Course bundle query failed, falling back to legacy mapping: " . $e->getMessage());
    try {
        $courses = fetchAll("
            SELECT c.*
            FROM courses c
            WHERE c.package_id = ? AND c.status = 'active'
            ORDER BY c.sort_order ASC, c.title ASC
        ", [$package['id']]);
    } catch (Exception $inner) {
        error_log("Legacy mapping query failed: " . $inner->getMessage());
        $courses = [];
    }
}

// Parse features
$features = !empty($package['features']) ? explode(',', $package['features']) : [];

// Fetch all packages for navigation
try {
    $nav_packages = fetchAll("SELECT id, name FROM packages WHERE status = 'active' ORDER BY name ASC");
} catch (Exception $e) {
    $nav_packages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($package['name']); ?> - RainBucks</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-brown: #8B4513;
            --light-brown: #D2B48C;
            --cream: #F5F5DC;
            --white: #FFFFFF;
            --text-dark: #333333;
            --green: #4CAF50;
            --orange: #FF8C00;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--white);
        }

        /* Navigation Styles */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: var(--text-light);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--header-footer);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--header-footer), var(--highlight));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        

        .nav-logo a {
            margin-right: 0.5rem;
            font-size: 1.6rem;
            background: linear-gradient(135deg, var(--header-footer), var(--highlight));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 2rem;
        }

        .nav-menu .nav-link {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .nav-menu .nav-link:hover {
            color: var(--header-footer);
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

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
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

        .dropdown-item:hover {
        background-color: var(--highlight);
        color: var(--text-light);
        }

        /* Hero Section */
        .hero-section {
            background: var(--white);
            padding: 120px 0 80px;
            margin-top: 0;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary-brown);
            margin-bottom: 2rem;
            line-height: 1.1;
        }

        .hero-text p {
            font-size: 1.1rem;
            color: var(--text-dark);
            line-height: 1.7;
            margin-bottom: 0;
            max-width: 90%;
        }

        .hero-image {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image-frame {
            position: relative;
            padding: 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .hero-image-frame::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 3px dashed var(--primary-brown);
            border-radius: 25px;
            opacity: 0.6;
        }

        .hero-image img {
            width: 100%;
            height: auto;
            border-radius: 15px;
            display: block;
        }

        /* Green accent dot */
        .hero-image::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--green);
            border-radius: 50%;
            bottom: 20px;
            left: 20px;
            z-index: 10;
        }

        /* Stats Banner */
        .stats-banner {
            background: var(--primary-brown);
            padding: 30px 0;
            margin: 40px 0;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            color: white;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            display: block;
        }

        .stat-label {
            font-size: 1.5rem;
        }

        /* Course Overview Section */
        .overview-section {
            background: white;
            padding: 80px 0;
        }

        .overview-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        .overview-content h2 {
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        .overview-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .benefits-list li {
            padding: 0.5rem 0;
            color: var(--text-dark);
        }

        .benefits-list li:before {
            content: "✓";
            color: var(--green);
            font-weight: bold;
            margin-right: 10px;
        }

        /* Package Info Card */
        .package-info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            position: sticky;
            top: 100px;
        }

        .package-icon {
            width: 80px;
            height: 80px;
            background: var(--light-brown);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: var(--primary-brown);
        }

        .package-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-brown);
            margin-bottom: 1rem;
        }

        .package-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-brown);
            margin-bottom: 1rem;
        }

        .enroll-btn {
            background: var(--green);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-bottom: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .enroll-btn:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .course-includes {
            text-align: left;
            margin-top: 2rem;
        }

        .course-includes h4 {
            color: var(--primary-brown);
            margin-bottom: 1rem;
        }

        .includes-list {
            list-style: none;
            padding: 0;
        }

        .includes-list li {
            padding: 0.5rem 0;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .includes-list li:before {
            content: "✓";
            color: var(--green);
            font-weight: bold;
            margin-right: 10px;
        }

        /* Why Choose Section */
        .why-choose-section {
            background: var(--cream);
            padding: 80px 0;
        }

        .why-choose-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }

        .why-choose-image {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .why-choose-image-frame {
            position: relative;
            padding: 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .why-choose-image-frame::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 3px dashed var(--primary-brown);
            border-radius: 25px;
            opacity: 0.6;
        }

        .why-choose-image img {
            width: 100%;
            height: auto;
            border-radius: 15px;
            display: block;
        }

        .why-choose-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-brown);
            margin-bottom: 2rem;
            line-height: 1.2;
        }

        .why-choose-content p, .why-choose-text {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .why-choose-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .why-choose-list li {
            padding: 0.8rem 0;
            padding-left: 2rem;
            position: relative;
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .why-choose-list li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--green);
            font-weight: bold;
            font-size: 1.3rem;
        }

        /* Green accent dot for why choose section */
        .why-choose-image::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--green);
            border-radius: 50%;
            bottom: 20px;
            right: 20px;
            z-index: 10;
        }

        /* Course Bundle Section */
        .course-bundle-section {
            background: linear-gradient(135deg, #4E944F, #7B3F00 );
            padding: 80px 0;
            color: white;
        }

        .course-bundle-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .course-bundle-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .course-bundle-header h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .course-bundle-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: white;
            margin: 20px auto 0;
        }

        .course-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .course-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .course-image {
            position: relative;
            z-index: 0;
            width: calc(100% - 30px);
            height: 180px;
            overflow: hidden;
            border-radius: 15px;
            margin: 15px;
            background: #f8f9fa;
        }

        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            transition: transform 0.3s ease;
            background: white;
        }

        .course-card:hover .course-image img {
            transform: scale(1.05);
        }

        .course-rating-top {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            padding: 5px 10px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            backdrop-filter: blur(10px);
            z-index: 2;
            pointer-events: none;
        }

        .course-rating-top .stars {
            color: #ffc107;
            font-size: 0.8rem;
        }

        .course-content {
            padding: 0 20px 20px 20px;
            text-align: left;
        }

        .course-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .course-students {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .course-students::before {
            content: "👥";
            font-size: 0.8rem;
        }

        /* Certificate Section */
        .certificate-section {
            background: #f5f0f0;
            padding: 80px 0;
        }

        .certificate-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .certificate-content h2 {
            font-size: 2.4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .certificate-content h2 .highlight {
            color: #4CAF50;
            font-weight: 700;
        }

        .certificate-content > p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 2rem;
        }

        .certificate-steps {
            list-style: none;
            padding: 0;
        }

        .certificate-steps li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .step-bullet {
            width: 12px;
            height: 12px;
            background: #4CAF50;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 0.5rem;
        }

        .step-content h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .step-content p {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
        }

        .certificate-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificate-image img {
            width: 100%;
            max-width: 450px;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .certificate-image img:hover {
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            background: var(--primary-brown);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: white;
        }

        .footer-section p {
            line-height: 1.6;
            color: #ddd;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #666;
            color: #ddd;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                left: -100%;
                top: 70px;
                flex-direction: column;
                background-color: var(--text-light);
                width: 100%;
                text-align: center;
                transition: 0.3s;
                box-shadow: 0 10px 27px rgba(0, 0, 0, 0.05);
                padding: 2rem 0;
                z-index: 1001;
            }

            .nav-menu.active { left: 0; }

            .hamburger { display: flex; }

            .nav-buttons {
                gap: 0.5rem;
            }

            .login-btn, .signup-btn {
                padding: 6px 16px;
                font-size: 0.85rem;
            }

            .hero-section {
                padding: 140px 0 60px;
            }

            .hero-container {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
                padding: 0 15px;
            }

            .hero-text h1 {
                font-size: 2.8rem;
            }

            .hero-text p {
                max-width: 100%;
            }

            .hero-image-frame {
                padding: 15px;
            }

            .hero-image-frame::before {
                top: -8px;
                left: -8px;
                right: -8px;
                bottom: -8px;
            }

            .overview-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .why-choose-container {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .why-choose-image-frame {
                padding: 15px;
            }

            .why-choose-image-frame::before {
                top: -8px;
                left: -8px;
                right: -8px;
                bottom: -8px;
            }

            .certificate-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .course-cards-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                flex-direction: column;
                gap: 1rem;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .nav-logo a {
                font-size: 1rem;
            }

            .login-btn, .signup-btn {
                padding: 5px 12px;
                font-size: 0.8rem;
            }

            .hero-text h1 {
                font-size: 2.2rem;
            }

            .overview-content h2 {
                font-size: 2rem;
            }

            .certificate-content h2 {
                font-size: 2rem;
            }

            .course-bundle-header h2 {
                font-size: 2rem;
            }

            .package-info-card {
                position: static;
            }

            .stat-number {
            font-size: 1rem;
            font-weight: 600;
            display: block;
            }

            .stat-label {
            font-size: 1rem;
            }
        }

        /* Ensure mobile navbar shows hamburger and hides auth buttons on this page */
        @media (max-width: 768px) {
            .nav-buttons { display: none; }
            .hamburger { display: flex; }
        }

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <div class="logo-icon">
                    <img src="../img/logo.png" alt="RainBucks Logo" style="height:32px; width:auto;">
                </div>
                <a href="../../index.php">Rainbucks</a>
            </div>
            <ul class="nav-menu">
                <li>
                    <a href="../../index.php" class="nav-link">Home</a>
                </li>
                <li>
                    <a href="../about.php" class="nav-link">About Us</a>
                </li>
                <li class="dropdown">
                    <a href="#courses" class="nav-link">All Courses</a>
                    <div class="dropdown-menu">
                        <?php
                        try {
                            // Get all active packages for navigation
                            $all_packages = fetchAll("SELECT name FROM packages WHERE status = 'active'");

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

                            // Separate fixed and new packages
                            $shown = [];
                            foreach ($fixed_order as $fixed_name) {
                                foreach ($all_packages as $pkg) {
                                    if ($pkg['name'] === $fixed_name) {
                                        $url_name = $package_url_map[$fixed_name];
                                        echo '<a href="dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($fixed_name) . '</a>';
                                        $shown[] = $fixed_name;
                                        break;
                                    }
                                }
                            }
                            // Show any new packages (not in fixed order) alphabetically
                            $new_packages = array_filter($all_packages, function($pkg) use ($shown) {
                                return !in_array($pkg['name'], $shown);
                            });
                            usort($new_packages, function($a, $b) {
                                return strcmp($a['name'], $b['name']);
                            });
                            foreach ($new_packages as $pkg) {
                                $url_name = strtolower(str_replace([' ', 'Package'], ['', ''], $pkg['name']));
                                echo '<a href="dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($pkg['name']) . '</a>';
                            }
                        } catch (Exception $e) {
                            // Fallback to static links if database fails
                        ?>
                            <a href="dynamic.php?package=starter" class="dropdown-item">Starter Package</a>
                            <a href="dynamic.php?package=professional" class="dropdown-item">Professional Package</a>
                            <a href="dynamic.php?package=advanced" class="dropdown-item">Advanced Package</a>
                            <a href="dynamic.php?package=expert" class="dropdown-item">Expert Package</a>
                            <a href="dynamic.php?package=ultimate" class="dropdown-item">Ultimate Package</a>
                            <a href="dynamic.php?package=super-ultimate" class="dropdown-item">Super Ultimate Package</a>
                        <?php } ?>
                    </div>
                </li>
                <li>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-text">
                <h1><?php echo htmlspecialchars(str_replace(' Package', '', $package['name'])); ?><br>Package</h1>
                <p><?php echo htmlspecialchars($package['description']); ?></p>
            </div>
            <div class="hero-image">
                <div class="hero-image-frame">
                    <?php if (!empty($package['image'])): ?>
                        <img src="../../assets/images/packages/<?php echo htmlspecialchars($package['image']); ?>"
                             alt="<?php echo htmlspecialchars($package['name']); ?>">
                    <?php else: ?>
                        <img src="../../assets/images/default-course.jpg" alt="Course Image">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-number"><?php echo count($courses); ?></span>
                <div class="stat-label">Courses</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">FIRST STEP TO SUCCESS</span>
                <div class="stat-label">JOURNEY</div>
            </div>
            <div class="stat-item">
                <span class="stat-number">1000+</span>
                <div class="stat-label">Students</div>
            </div>
        </div>
    </section>

    <!-- Course Overview Section -->
    <section class="overview-section">
        <div class="overview-container">
            <div class="overview-content">
                <h2><?php echo !empty($package['overview_title']) ? htmlspecialchars($package['overview_title']) : 'Course Overview'; ?></h2>

                <?php if (!empty($package['overview_content'])): ?>
                    <div class="overview-text">
                        <?php echo nl2br(htmlspecialchars($package['overview_content'])); ?>
                    </div>
                <?php else: ?>
                    <p>This <?php echo htmlspecialchars(str_replace(' Package', '', $package['name'])); ?> Package is your first step towards mastering business fundamentals. Our comprehensive curriculum covers essential topics that every entrepreneur needs to know.</p>
                    <p>You'll learn from industry experts who have years of real-world experience. Each course is designed to be practical and immediately applicable to your business journey.</p>
                <?php endif; ?>

                <?php if (!empty($package['what_you_learn'])): ?>
                    <h3>What You'll Learn</h3>
                    <ul class="benefits-list">
                        <?php
                        $learn_items = explode("\n", $package['what_you_learn']);
                        foreach ($learn_items as $item):
                            $item = trim($item);
                            if (!empty($item)):
                        ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                <?php endif; ?>

                <h3>Other Benefits</h3>
                <ul class="benefits-list">
                    <?php if (!empty($features)): ?>
                        <?php foreach ($features as $feature): ?>
                            <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="package-info-card">
                <div class="package-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="package-name"><?php echo htmlspecialchars($package['name']); ?></div>
                <div class="package-price">₹<?php echo number_format($package['price'], 0); ?></div>
                <a href="https://course.Brainbucks.org/learn/account/signup " class="enroll-btn">Enroll Now</a>

                <div class="course-includes">
                    <h4>This course includes:</h4>
                    <ul class="includes-list">
                        <li><?php echo count($courses); ?> comprehensive courses</li>
                        <li>Lifetime access to materials</li>
                        <li>Certificate of completion</li>
                        <li>Expert instructor support</li>
                        <li>Mobile and desktop access</li>
                        <li>30-day money-back guarantee</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="why-choose-section">
        <div class="why-choose-container">
            <div class="why-choose-image">
                <div class="why-choose-image-frame">
                    <img src="../../assets/images/why-choose-constant.jpg" alt="Why Choose Us">
                </div>
            </div>
            <div class="why-choose-content">
                <h2><?php echo !empty($package['why_choose_title']) ? htmlspecialchars($package['why_choose_title']) : 'Why Choose This Package?'; ?></h2>

                <?php if (!empty($package['why_choose_content'])): ?>
                    <div class="why-choose-text">
                        <?php echo nl2br(htmlspecialchars($package['why_choose_content'])); ?>
                    </div>
                <?php else: ?>
                    <p>Our <?php echo htmlspecialchars(str_replace(' Package', '', $package['name'])); ?> Package stands out from the competition with its comprehensive approach to learning and practical application.</p>
                    <p>We focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.</p>
                <?php endif; ?>

                <?php if (!empty($package['why_choose_points'])): ?>
                    <ul class="why-choose-list">
                        <?php
                        $points = explode("\n", $package['why_choose_points']);
                        foreach ($points as $point):
                            $point = trim($point);
                            if (!empty($point)):
                        ?>
                            <li><?php echo htmlspecialchars($point); ?></li>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </ul>
                <?php else: ?>
                    <ul class="why-choose-list">
                        <li>Expert instructors with industry experience</li>
                        <li>Practical, hands-on learning approach</li>
                        <li>Comprehensive curriculum designed for success</li>
                        <li>Lifetime access to all course materials</li>
                        <li>Certificate of completion</li>
                        <li>24/7 support and community access</li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Course Bundle Section -->
    <?php if (!empty($courses)): ?>
    <section class="course-bundle-section">
        <div class="course-bundle-container">
            <div class="course-bundle-header">
                <h2>Course Bundle</h2>
            </div>

            <div class="course-cards-grid">
                <?php foreach ($courses as $course): ?>
                    <a href="../course/details.php?id=<?php echo $course['id']; ?>" class="course-card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.3s ease;">
                        <div class="course-image">
                            <!-- Rating Badge inside image container -->
                            <div class="course-rating-top">
                                <span class="stars">★</span>
                                <span><?php echo number_format($course['rating'] ?? 4.9, 1); ?></span>
                                <span style="color: #999; font-size: 0.8rem;">(<?php echo number_format($course['students_enrolled'] ?? 1000); ?> students)</span>
                            </div>

                            <?php if (!empty($course['image'])): ?>
                                <img src="../../assets/images/courses/<?php echo htmlspecialchars($course['image']); ?>"
                                     alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <?php else: ?>
                                <div style="height: 180px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #666; border-radius: 15px;">
                                    <i class="fas fa-book" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="course-content">
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            <div class="course-students">
                                <?php echo number_format($course['students_enrolled'] ?? 1000); ?> students enrolled
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Certificate Section -->
    <section class="certificate-section">
        <div class="certificate-container">
            <div class="certificate-content">
                <h2>Earn Your <span class="highlight">Certificate</span> of Completion</h2>
                <p>Start your learning journey today and achieve your Certificate of Completion with our all-inclusive program. Follow these simple steps to success:</p>

                <ul class="certificate-steps">
                    <li>
                        <span class="step-bullet"></span>
                        <div class="step-content">
                            <h4>Explore the Course</h4>
                            <p>Discover engaging, expert-designed content that boosts your skills and knowledge in your chosen field.</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-bullet"></span>
                        <div class="step-content">
                            <h4>Learn and Grow</h4>
                            <p>Absorb practical insights, proven strategies, and industry best practices to enhance your expertise.</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-bullet"></span>
                        <div class="step-content">
                            <h4>Take the Test</h4>
                            <p>Validate your understanding through a well-structured assessment, ensuring you're prepared to apply what you've learned.</p>
                        </div>
                    </li>
                    <li>
                        <span class="step-bullet"></span>
                        <div class="step-content">
                            <h4>Receive Your Certificate</h4>
                            <p>Successfully complete the course and test to earn your Certificate of Completion. Use it to highlight your achievements and unlock new career opportunities.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="certificate-image">
                <img src="../img/courses/demo-certificate.png" alt="Certificate of Completion">
            </div>
        </div>
    </section>

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
    <script src="../script.js"></script>
</body>
</html>
