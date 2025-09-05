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
    <title>Privacy Policy - Rainbucks</title>
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
            padding-top: 80px; /* Add top padding to prevent content from hiding behind fixed navbar */
        }

        /* Navbar styles */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        /* Content area */
        .privacy-section {
            max-width: 1000px;
            width: 90%;
            margin: 0 auto 40px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .privacy-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .privacy-header h1 {
            font-size: 2.2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .privacy-content {
            line-height: 1.7;
            color: #4a5568;
        }

        .privacy-content h2 {
            color: #2d3748;
            margin: 2rem 0 1rem;
            font-size: 1.4rem;
        }

        .privacy-content p,
        .privacy-content ul {
            margin-bottom: 1.2rem;
        }

        .privacy-content ul {
            padding-left: 1.5rem;
        }

        .privacy-content li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .privacy-section {
                padding: 20px;
                width: 90%;
                margin: 0 auto 20px;
            }
            
            .privacy-header h1 {
                font-size: 1.8rem;
            }
        }

        /* Dropdown Styles */
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
            background-color: var(--highlight);
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

    <!-- Privacy Policy Section -->
    <section class="privacy-section">
        <div class="privacy-container">
            <div class="privacy-header">
                <h1>Privacy Policy</h1>
                <p>Last updated: August 24, 2024</p>
            </div>
            <div class="privacy-content">
                <p class="last-updated"><strong>Effective Date:</strong> August 24, 2024</p>
                
                <h2>1. Information We Collect</h2>
                <ul>
                    <li><strong>Personal Information</strong>: Name, email address, phone number, billing/payment details, and any additional information you provide during registration or course enrollment.</li>
                    <li><strong>Non-Personal Information</strong>: IP address, browser type/version, device details, pages visited, time spent on the site, etc.</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>We use collected data to:</p>
                <ul>
                    <li>Provide and maintain our course services</li>
                    <li>Process payments and manage subscriptions</li>
                    <li>Communicate updates, promotions, and new course offerings</li>
                    <li>Improve our website, content, and user experience</li>
                    <li>Meet legal and compliance requirements</li>
                </ul>

                <h2>3. Cookies & Tracking Technologies</h2>
                <p>We may use cookies, web beacons, and similar technologies to:</p>
                <ul>
                    <li>Enhance user experience</li>
                    <li>Analyze site performance and visitor behavior</li>
                    <li>Deliver personalized content or advertisements</li>
                </ul>
                <p>You can manage or disable cookies via your browser settings; note this could affect site functionality.</p>

                <h2>4. Third-Party Services & Advertising Partners</h2>
                <p>Some third parties (e.g., ad partners or analytics tools) may use cookies or similar mechanisms. We encourage you to review their respective privacy policies.</p>

                <h2>5. Log Files</h2>
                <p>We record standard log data such as IP addresses, timestamps, referring/exit pages, and click activity for analytics and website optimization.</p>

                <h2>6. Children's Privacy</h2>
                <p>Rainbucks does <strong>not knowingly</strong> collect any personal data from children under the age of 13 (or under 16 in certain regions). If you suspect that a minor's information has been submitted, please contact us immediately.</p>

                <h2>7. Your Rights</h2>
                <p>If you are based in:</p>
                <ul>
                    <li><strong>India (DPDP Act)</strong>: You have the right to access, correct your data, withdraw consent, and lodge complaints with the Data Protection Board.</li>
                    <li><strong>European Economic Area (GDPR)</strong>: You may access, rectify, delete data, restrict processing, request portability, or lodge complaints with data authorities.</li>
                    <li><strong>California (CCPA)</strong>: You can know what data is collected, request deletion, opt out of sales, and expect no discrimination for exercising these rights.</li>
                </ul>
                <p>To exercise any rights, please reach out via <a href="mailto:support@rainbucks.org">support@rainbucks.org</a>.</p>

                <h2>8. Data Retention</h2>
                <p>We retain your personal information only as long as necessary to fulfill the purposes mentioned, meet legal obligations, enforce agreements, or resolve disputes.</p>

                <h2>9. Data Security</h2>
                <p>We employ industry-standard security measures to protect your data but cannot fully guarantee absolute security due to inherent limitations.</p>

                <h2>10. Policy Updates</h2>
                <p>This Privacy Policy may change periodically. The latest version will always be posted here with an updated "Effective Date."</p>

                <h2>11. Contact Us</h2>
                <p>For questions, concerns, or to exercise your rights, contact us at:</p>
                <p><strong>Email</strong>: <a href="mailto:support@rainbucks.org">support@rainbucks.org</a></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="custom-footer">
        <div class="custom-footer-container">
            <div class="custom-footer-section brand">
                <div class="custom-footer-logo">
                    <img src="img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto; vertical-align:middle;">
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

    <script src="script.js"></script>
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
