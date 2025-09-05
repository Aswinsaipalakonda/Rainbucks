<?php
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
    <title>Cancellation & Refund Policy - Rainbucks</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .policy-section {
            max-width: 1000px;
            width: 90%;
            margin: 0 auto 40px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .policy-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .policy-header h1 {
            font-size: 2.2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .policy-header p {
            color: #718096;
            font-size: 1rem;
        }

        .policy-content {
            line-height: 1.8;
            color: #4a5568;
        }

        .policy-content h2 {
            color: #2d3748;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .policy-content p, .policy-content ul {
            margin-bottom: 1.2rem;
        }

        .policy-content ul {
            padding-left: 1.5rem;
        }

        .policy-content li {
            margin-bottom: 0.5rem;
        }

        .highlight {
            background-color: #f0f7ff;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .policy-section {
                padding: 20px;
                width: 95%;
            }

            .policy-header h1 {
                font-size: 1.8rem;
            }
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
            border-radius: 8px;
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
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: #D8A35D;
            color: #f8f9fa;
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
                <a href="../index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;"><span>Rainbucks</span></a>
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
                                        echo '<a href="package/dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($fixed_name) . '</a>';
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
                                echo '<a href="package/dynamic.php?package=' . $url_name . '" class="dropdown-item">' . htmlspecialchars($pkg['name']) . '</a>';
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

    <!-- Policy Section -->
    <section class="policy-section">
        <div class="policy-container">
            <div class="policy-header">
                <h1>Cancellation & Refund Policy</h1>
                <p>Last Updated: August 24, 2024</p>
            </div>
            
            <div class="policy-content">
                <p>At <strong>Rainbucks</strong>, we aim to provide valuable and high-quality online courses. This Cancellation & Refund Policy outlines the circumstances under which cancellations and refunds may be granted. By purchasing any course or service from  <strong><a href="https://rainbucks.org" target="_blank">rainbucks.org</a></strong>, you agree to the terms below.</p>

                <h2>1. Course Purchases</h2>
                <ul>
                    <li>All course purchases are final once access credentials or course content have been provided.</li>
                    <li>Please review all course details, previews, and descriptions carefully before making a purchase.</li>
                </ul>

                <h2>2. Cancellation Policy</h2>
                <ul>
                    <li><strong>Before Access is Granted:</strong> You may request a cancellation within <strong>24 hours</strong> of purchase if the course content has <strong>not</strong> yet been accessed.</li>
                    <li><strong>After Access is Granted:</strong> Once course materials are made available to you, cancellations are <strong>not</strong> permitted.</li>
                </ul>

                <h2>3. Refund Policy</h2>
                <p>Refunds will be granted only under the following conditions:</p>
                <ul>
                    <li>You were charged incorrectly due to a payment processing error.</li>
                    <li>The purchased course is <strong>permanently unavailable</strong> due to reasons on our side.</li>
                    <li>You qualify under our "Before Access is Granted" clause above.</li>
                </ul>
                
                <p>Refunds will <strong>not</strong> be granted if:</p>
                <ul>
                    <li>You have accessed or downloaded any part of the course.</li>
                    <li>You fail to complete the course for personal reasons.</li>
                    <li>You are dissatisfied after consuming significant course content (unless due to a proven technical fault).</li>
                </ul>

                <h2>4. Refund Request Process</h2>
                <p>To request a refund:</p>
                <ol>
                    <li>Email <a href="mailto:support@rainbucks.org">support@rainbucks.org</a> with your order details, payment receipt, and reason for the request.</li>
                    <li>Our team will verify eligibility within <strong>5 business days</strong>.</li>
                    <li>Approved refunds will be processed within <strong>7–10 business days</strong> to the original payment method.</li>
                </ol>

                <h2>5. Special Offers & Discounts</h2>
                <p>Courses purchased at a discounted price, as part of a bundle, or through promotional offers are <strong>non-refundable</strong>, unless due to technical or payment errors.</p>

                <h2>6. Changes to This Policy</h2>
                <p>We reserve the right to update this Cancellation & Refund Policy at any time. Changes will be posted on  <strong><a href="https://rainbucks.org" target="_blank">rainbucks.org</a></strong> with an updated "Effective Date."</p>

                <h2>7. Contact Us</h2>
                <p>For any questions about cancellations or refunds, contact:<br>
                <strong>Email:</strong> <a href="mailto:support@rainbucks.org">support@rainbucks.org</a></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="custom-footer-container">
            <div class="custom-footer-section brand">
                <div class="custom-footer-logo">
                    <img src="./img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto; vertical-align:middle;">
                    <a href="../index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;"><span>Rainbucks</span></a>
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
                    <li><a href="package/dynamic.php?package=starter">Courses</a></li>
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

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    </script>
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
</body>
</html>
