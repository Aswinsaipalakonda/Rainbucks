<?php
// Fetch packages for navigation dropdown
require_once '../includes/db.php';

try {
    $nav_packages = fetchAll("SELECT name FROM packages WHERE status = 'active'");
} catch (Exception $e) {
    $nav_packages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - RainBucks</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Contact Hero Section Specific Styles */
        .contact-hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px 0;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            position: relative;
            overflow: hidden;
        }

        .contact-hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .contact-hero-content {
            position: relative;
            z-index: 2;
        }

        .contact-hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: #4A4A4A;
            margin-bottom: 2rem;
            line-height: 1.2;
            position: relative;
        }

        .contact-hero-text h1::before {
            content: '';
            position: absolute;
            top: -10px;
            right: 60px;
            width: 30px;
            height: 8px;
            background: #8B5CF6;
            border-radius: 4px;
        }

        .contact-hero-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }

        .contact-cta-btn {
            display: inline-block;
            padding: 15px 30px;
            background: var(--accent-green);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }

        .contact-cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        .contact-hero-image-section {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .contact-us-text {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #EC4899;
            transform: rotate(15deg);
        }

        .customer-service-rep {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #8B5CF6, #A855F7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .customer-service-rep::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(135deg, #8B5CF6, #A855F7);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.3;
        }

        .rep-icon {
            font-size: 3rem;
            color: white;
        }

        .floating-icons {
            position: absolute;
            left: -80px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .floating-icon {
            width: 50px;
            height: 50px;
            background: #8B5CF6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }

        .speech-bubble {
            position: absolute;
            top: -20px;
            right: -60px;
            background: #8B5CF6;
            color: white;
            padding: 8px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 20px;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #8B5CF6;
        }

        .decorative-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #D1D1FF, #B8B8FF);
            top: 10%;
            left: 5%;
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFD1D1, #FFB8B8);
            bottom: 20%;
            left: 10%;
        }

        .shape-3 {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
            top: 20%;
            right: 15%;
        }

        .leaf-shape {
            position: absolute;
            width: 30px;
            height: 20px;
            background: #A0522D;
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        }

        .leaf-1 {
            bottom: 15%;
            right: 10%;
            transform: rotate(45deg);
        }

        .leaf-2 {
            bottom: 25%;
            right: 15%;
            transform: rotate(-30deg);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .contact-hero-container {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .contact-hero-text h1 {
                font-size: 2.5rem;
            }

            .contact-hero-text p {
                font-size: 1rem;
            }

            .contact-cta-btn {
                padding: 12px 25px;
                font-size: 1rem;
            }

            .phone-illustration {
                width: 250px;
                height: 350px;
            }

            .floating-icons {
                left: -60px;
            }

            .floating-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .speech-bubble {
                right: -40px;
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .contact-hero-text h1 {
                font-size: 2rem;
            }

            .contact-hero-text h1::before {
                width: 25px;
                height: 6px;
                right: 40px;
            }

            .phone-illustration {
                width: 200px;
                height: 300px;
            }

            .customer-service-rep {
                width: 100px;
                height: 100px;
            }

            .rep-icon {
                font-size: 2.5rem;
            }

            .floating-icons {
                left: -50px;
                gap: 15px;
            }

            .floating-icon {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .speech-bubble {
                right: -30px;
                padding: 5px 8px;
                font-size: 0.7rem;
            }
        }

        /* Contact Form Section Styles */
        .contact-form-section {
            padding: 80px 0;
            background: white;
        }

        .contact-form-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .contact-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4A4A4A;
            margin-bottom: 1rem;
        }

        .contact-underline {
            width: 60px;
            height: 3px;
            background: #EC4899;
            margin: 0 auto 1.5rem;
            border-radius: 2px;
        }

        .contact-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .contact-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            margin: 0 auto;
        }

        .contact-form-side {
            padding: 3rem;
            background: white;
        }

        .contact-form-side h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #4A4A4A;
            margin-bottom: 2rem;
            line-height: 1.4;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #4A4A4A;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #F8FAFC;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #8B5CF6;
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-btn {
            background: var(--accent-green);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        .contact-details-side {
            background: var(--accent-green);
            padding: 3rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .contact-section {
            margin-bottom: 2rem;
        }

        .contact-section h4 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .contact-section h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .contact-line {
            width: 40px;
            height: 2px;
            background: white;
            margin-bottom: 1.5rem;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1rem;
        }

        .contact-item i {
            width: 20px;
            text-align: center;
        }

        .social-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            margin-left: 60px;
            gap: 1rem;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            transform: translateX(5px);
        }

        .social-link i {
            width: 20px;
            text-align: center;
        }

        /* Responsive Design for Contact Form */
        @media (max-width: 768px) {
            .contact-card {
                grid-template-columns: 1fr;
            }

            .contact-form-side,
            .contact-details-side {
                padding: 2rem;
            }

            .contact-header h2 {
                font-size: 2rem;
            }

            .contact-form-side h3 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .contact-form-side,
            .contact-details-side {
                padding: 1.5rem;
            }

            .contact-header h2 {
                font-size: 1.8rem;
            }

            .contact-form-side h3 {
                font-size: 1.1rem;
            }

            .form-group input,
            .form-group textarea {
                padding: 10px 14px;
                font-size: 0.95rem;
            }

            .submit-btn {
                padding: 12px 25px;
                font-size: 1rem;
            }
        }

        .faq-container {
      max-width: 800px;
      margin: 50px auto;
      background-color: #f8f2fc;
      padding: 40px 20px;
      border-radius: 10px;
    }

    .faq-container h2 {
      text-align: center;
      color: #1d1d1f;
      font-size: 2rem;
      margin-bottom: 30px;
      position: relative;
    }

    .faq-container h2::after {
      content: "";
      display: block;
      width: 100px;
      height: 5px;
      background-color: #ff7597;
      border-radius: 5px;
      margin: 8px auto 0;
    }

    .faq-item {
      background-color: #ffffff;
      border-radius: 10px;
      margin-bottom: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      overflow: hidden;
      transition: 0.3s;
    }

    .faq-question {
      padding: 18px 20px;
      cursor: pointer;
      position: relative;
      font-weight: 600;
      font-size: 1rem;
      color: #333;
    }

    .faq-question.active {
      color: var(--accent-green);
    }

    .faq-question::after {
      content: '\25BC';
      position: absolute;
      right: 20px;
      transition: transform 0.3s ease;
    }

    .faq-question.active::after {
      transform: rotate(180deg);
    }

    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease;
      background-color: #fff;
      padding: 0 20px;
      font-size: 0.95rem;
      color: #555;
    }

    .faq-answer.open {
      padding: 25px 30px;
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
                        <img src="./img/logo.png" alt="RainBucks Logo" style="height:32px; width:auto;">
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



    <!-- Contact Form Section -->
    <section class="contact-form-section" id="contact">
        <div class="contact-form-container">
            <!-- Header -->
            <div class="contact-header">
                <h2>Contact Us</h2>
                <div class="contact-underline"></div>
                <p>Have questions? Need guidance? We're here to help! Reach out to us, and we'll ensure your learning journey with Rainbucks is smooth and successful.</p>
            </div>

            <!-- Contact Card -->
            <div class="contact-card">
                <!-- Left Side - Contact Form -->
                <div class="contact-form-side">
                    <h3>We're excited to help you achieve your goals. Let's build your future together!</h3>
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="fullName" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Submit</button>
                    </form>
                </div>

                <!-- Right Side - Contact Details -->
                <div class="contact-details-side">
                    <div class="contact-section">
                        <h4>Let's Connect</h4>
                        <h5>Contact Details</h5>
                        <div class="contact-line"></div>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>support@rainbucks.org</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>6301664137</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Krishna Gardens Visakhapatnam (H.O)</span>
                            </div>
                        </div>
                    </div>

                    <div class="contact-section">
                        <h5>Follow Us</h5>
                        <div class="contact-line"></div>
                        <div class="social-links">
                            <a href="https://www.facebook.com/Rainbucks.official/" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://www.instagram.com/rainbucks.official/" class="social-link">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            <a href="https://whatsapp.com/channel/0029Vb5fOJg4tRrkK1Q9sy0G" class="social-link">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                            <a href="https://www.linkedin.com/company/rainbucks-india/" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="faq-container">
    <h2>Frequently Asked Questions</h2>

    <div class="faq-item">
      <div class="faq-question active">What is Rainbucks?</div>
      <div class="faq-answer open">
        Rainbucks is an online learning platform based in India that offers industry-relevant courses in digital marketing, freelancing, personal development, business communication, and more. Our mission is to help individuals gain practical skills and grow their careers through flexible, affordable, and high-quality education.
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Who can use Rainbucks?</div>
      <div class="faq-answer">
        Anyone looking to upskill can use Rainbucks! Whether you're a student, working professional, freelancer, entrepreneur, or someone exploring new opportunities, our courses are designed to support learners at all stages.
        </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Are the courses certified?</div>
      <div class="faq-answer">
        Yes, all Rainbucks courses come with a certificate of completion. These certificates can be a valuable addition to your resume or professional portfolio.
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">How do I sign up for Rainbucks?</div>
      <div class="faq-answer">
        Signing up is easy! Simply visit our website, choose your preferred course package, create an account with your email and password, and start learning instantly from your desktop or mobile device.
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">What types of courses does Rainbucks offer?</div>
      <div class="faq-answer">
        Rainbucks offers over 20+ practical and career-focused courses, including Affiliate Marketing, Instagram Marketing, Copywriting, Spoken English, Email Marketing, Personal Finance, Public Speaking, and many more.
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Is my data safe on Rainbucks?</div>
      <div class="faq-answer">
        Absolutely. We take your privacy and security seriously. Rainbucks uses secure technology to protect your personal information and ensure a safe learning experience.
      </div>
    </div>

  </div>

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
        // Contact Form WhatsApp Integration
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const fullName = formData.get('fullName');
            const phone = formData.get('phone');
            const email = formData.get('email');
            const message = formData.get('message');

            // Create WhatsApp message (no leading/trailing line breaks)
            const whatsappMessage =
              "*New Contact Form Submission*\n" +
              "*Name:* " + fullName + "\n" +
              "*Phone:* " + phone + "\n" +
              "*Email:* " + email + "\n" +
              "*Message:* " + message + "\n" +
              "*Submitted from:* RainBucks Contact Form";

            // Encode message for WhatsApp URL
            const encodedMessage = encodeURIComponent(whatsappMessage);

            // WhatsApp number (no +)
            const whatsappNumber = '917702128915';

            // Create WhatsApp URL
            const whatsappURL = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
            
            // Open WhatsApp in new tab
            window.open(whatsappURL, '_blank');
            
            // Show success message
            alert('Thank you for your message! We will get back to you soon.');
            
            // Reset form
            this.reset();
        });
        const questions = document.querySelectorAll('.faq-question');

    questions.forEach(q => {
      q.addEventListener('click', () => {
        const isActive = q.classList.contains('active');
        questions.forEach(item => {
          item.classList.remove('active');
          const answer = item.nextElementSibling;
          answer.classList.remove('open');
          answer.style.maxHeight = null;
        });

        if (!isActive) {
          q.classList.add('active');
          const answer = q.nextElementSibling;
          answer.classList.add('open');
          answer.style.maxHeight = answer.scrollHeight + "px";
        }
      });
    });

    // Initialize first open
    document.querySelectorAll('.faq-answer').forEach((a, i) => {
      if (a.classList.contains('open')) {
        a.style.maxHeight = a.scrollHeight + "px";
      } else {
        a.style.maxHeight = null;
      }
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
