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
    <title>Disclaimer - Rainbucks</title>
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

        .disclaimer-section {
            max-width: 1000px;
            width: 90%;
            margin: 0 auto 40px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .disclaimer-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .disclaimer-header h1 {
            font-size: 2.2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .disclaimer-header p {
            color: #718096;
            font-size: 1rem;
        }

        .disclaimer-content {
            line-height: 1.8;
            color: #4a5568;
        }

        .disclaimer-content h2 {
            color: #2d3748;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .disclaimer-content p, .disclaimer-content ul {
            margin-bottom: 1.2rem;
        }

        .disclaimer-content ul {
            padding-left: 1.5rem;
        }

        .disclaimer-content li {
            margin-bottom: 0.8rem;
        }

        .highlight {
            background-color: #f0f7ff;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .disclaimer-section {
                padding: 20px;
                width: 95%;
            }

            .disclaimer-header h1 {
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

    <!-- Disclaimer Section -->
    <section class="disclaimer-section">
        <div class="disclaimer-container">
            <div class="disclaimer-header">
                <h1>Disclaimer</h1>
                <p>Effective Date: August 24, 2024</p>
            </div>
            
            <div class="disclaimer-content">
                <p>The information, courses, and materials provided on <strong><a href="https://rainbucks.org" target="_blank">rainbucks.org</a></strong> ("Platform") are for educational purposes only. By using our Platform, you acknowledge and agree to the following disclaimers:</p>

                <h2>1. Educational Purposes Only</h2>
                <ul>
                    <li>All courses, content, and resources offered by Rainbucks are intended solely to provide learning and skill development.</li>
                    <li>We do not guarantee specific career, income, or examination results from completing any course.</li>
                </ul>

                <h2>2. No Professional Advice</h2>
                <ul>
                    <li>Rainbucks courses do <strong>not</strong> constitute professional, legal, financial, medical, or other specialized advice.</li>
                    <li>You should consult a qualified professional before making decisions based on any information from our Platform.</li>
                </ul>

                <h2>3. Accuracy of Information</h2>
                <ul>
                    <li>While we strive to keep our content accurate and up-to-date, Rainbucks makes <strong>no warranties or representations</strong> regarding the completeness, accuracy, reliability, or suitability of the information provided.</li>
                    <li>Content may be updated, modified, or removed without notice.</li>
                </ul>

                <h2>4. External Links</h2>
                <ul>
                    <li>Our Platform may contain links to third-party websites or resources. These are provided for convenience only.</li>
                    <li>Rainbucks is <strong>not responsible</strong> for the availability, accuracy, or content of external sites, nor does linking imply endorsement.</li>
                </ul>

                <h2>5. User Responsibility</h2>
                <ul>
                    <li>You are solely responsible for how you use and apply the information obtained from our courses.</li>
                    <li>Rainbucks will not be liable for any direct, indirect, incidental, or consequential damages arising from your use of our Services.</li>
                </ul>

                <h2>6. No Warranty</h2>
                <ul>
                    <li>All Services are provided on an <strong>"as is"</strong> and <strong>"as available"</strong> basis without any warranties, express or implied.</li>
                    <li>We do not warrant that the Platform will be error-free, secure, or continuously available.</li>
                </ul>

                <h2>7. Changes to This Disclaimer</h2>
                <p>Rainbucks reserves the right to update or modify this Disclaimer at any time without prior notice. Updates will be posted on <strong><a href="https://rainbucks.org" target="_blank">rainbucks.org</a></strong> with a revised "Effective Date."</p>

                <h2>8. Contact Us</h2>
                <p>For questions about this Disclaimer, contact:<br>
                <strong>Email:</strong> <a href="mailto:support@rainbucks.org">support@rainbucks.org</a></p>
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

    <script>
        // Mobile menu toggle
        document.querySelector('.hamburger').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
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
