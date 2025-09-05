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
    <title>Terms & Conditions - Rainbucks</title>
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

        .terms-section {
            max-width: 1000px;
            width: 90%;
            margin: 0 auto 40px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .terms-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .terms-header h1 {
            font-size: 2.2rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .terms-content {
            line-height: 1.7;
            color: #4a5568;
        }

        .terms-content h2 {
            color: #2d3748;
            margin: 2rem 0 1rem;
            font-size: 1.4rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .terms-content h2:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .terms-content p,
        .terms-content ul {
            margin-bottom: 1.2rem;
        }

        .terms-content ul {
            padding-left: 1.5rem;
        }

        .terms-content li {
            margin-bottom: 0.5rem;
        }

        .terms-content a {
            color: #2d3748;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-content a:hover {
            text-decoration: underline;
        }

        .last-updated {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 2rem !important;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .terms-section {
                padding: 20px;
                width: 90%;
                margin: 0 auto 20px;
            }
            
            .terms-header h1 {
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

    <!-- Terms & Conditions Section -->
    <section class="terms-section">
        <div class="terms-container">
            <div class="terms-header">
                <h1>Terms & Conditions</h1>
                <p>Last updated: August 24, 2024</p>
            </div>
            <div class="terms-content">
                <p class="last-updated"><strong>Effective Date:</strong> August 24, 2024</p>
                
                <h2>1. Introduction</h2>
                <p>Welcome to <strong>Rainbucks</strong> ("we," "our," "us"). These Terms & Conditions ("Terms") govern your access to and use of our website <a href="https://rainbucks.org">rainbucks.org</a>, our courses, and any related services ("Services"). By using our Services, you agree to be bound by these Terms and our Privacy Policy. If you do not agree, please do not use our Services.</p>

                <h2>2. Eligibility</h2>
                <p>You must:</p>
                <ul>
                    <li>Be at least 18 years old, or have parental/guardian consent if under 18.</li>
                    <li>Provide accurate and complete registration information.</li>
                    <li>Comply with all applicable laws in your jurisdiction.</li>
                </ul>

                <h2>3. Account Registration & Security</h2>
                <ul>
                    <li>You are responsible for maintaining the confidentiality of your account login credentials.</li>
                    <li>Any activity under your account will be deemed as your responsibility.</li>
                    <li>Notify us immediately if you suspect unauthorized use of your account.</li>
                </ul>

                <h2>4. Courses & Content Usage</h2>
                <ul>
                    <li>Rainbucks grants you a <strong>limited, non-exclusive, non-transferable license</strong> to access and use purchased courses for personal, non-commercial purposes.</li>
                    <li>You <strong>may not</strong> share, copy, resell, or distribute any course content without written permission.</li>
                    <li>All intellectual property rights remain the property of Rainbucks or its licensors.</li>
                </ul>

                <h2>5. Payments & Refunds</h2>
                <ul>
                    <li>All course prices are listed in INR unless otherwise stated.</li>
                    <li>Payment must be made in full before accessing any paid course.</li>
                    <li>Refunds will be processed according to our <strong>Cancellation & Refund Policy</strong> available on <a href="https://rainbucks.org">rainbucks.org</a>.</li>
                    <li>We reserve the right to change prices at any time.</li>
                </ul>

                <h2>6. User Responsibilities</h2>
                <p>When using our Services, you agree <strong>not to</strong>:</p>
                <ul>
                    <li>Use the content for illegal purposes or in violation of any laws.</li>
                    <li>Post or transmit harmful, defamatory, or infringing material.</li>
                    <li>Attempt to hack, reverse engineer, or otherwise damage the platform.</li>
                </ul>

                <h2>7. Intellectual Property</h2>
                <ul>
                    <li>All course materials, website design, text, graphics, logos, and software are protected by copyright, trademark, and other laws.</li>
                    <li>Unauthorized use of any Rainbucks intellectual property is prohibited.</li>
                </ul>

                <h2>8. Disclaimer of Warranties</h2>
                <ul>
                    <li>All courses and services are provided <strong>"as is" and "as available"</strong> without warranties of any kind.</li>
                    <li>We do not guarantee specific results from course completion.</li>
                </ul>

                <h2>9. Limitation of Liability</h2>
                <ul>
                    <li>Rainbucks is not liable for any direct, indirect, incidental, or consequential damages arising from your use of our Services.</li>
                    <li>Our total liability for any claim will not exceed the amount paid by you for the course in question.</li>
                </ul>

                <h2>10. Indemnification</h2>
                <p>You agree to indemnify and hold harmless Rainbucks, its employees, and partners from any claims, damages, or losses arising from your use of our Services or breach of these Terms.</p>

                <h2>11. Termination</h2>
                <p>We reserve the right to suspend or terminate your account at our discretion if we believe you have violated these Terms or engaged in fraudulent activity.</p>

                <h2>12. Modifications to Terms</h2>
                <p>We may update these Terms at any time. Updates will be posted on <a href="https://rainbucks.org">rainbucks.org</a> with a revised "Effective Date." Continued use of our Services means you accept the updated Terms.</p>

                <h2>13. Governing Law & Dispute Resolution</h2>
                <ul>
                    <li>These Terms are governed by the laws of <strong>India</strong>.</li>
                    <li>Any disputes will be subject to the exclusive jurisdiction of the courts in <strong>Visakhapatnam, Andhra Pradesh</strong>, India.</li>
                </ul>

                <h2>14. Contact Us</h2>
                <p>For any questions about these Terms, contact:<br>
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
