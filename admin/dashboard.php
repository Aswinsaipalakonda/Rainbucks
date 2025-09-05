<?php
// Include authentication check
require_once 'auth_check.php';
require_once '../includes/db.php';

// Get current admin info
$admin = getCurrentAdmin();

// Get dashboard statistics
try {
    $packages_count = fetchOne("SELECT COUNT(*) as count FROM packages WHERE status = 'active'")['count'] ?? 0;
    $courses_count = fetchOne("SELECT COUNT(*) as count FROM courses WHERE status = 'active'")['count'] ?? 0;
    $testimonials_count = fetchOne("SELECT COUNT(*) as count FROM testimonials WHERE status = 'active'")['count'] ?? 0;
} catch (Exception $e) {
    $packages_count = 0;
    $courses_count = 0;
    $testimonials_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rainbucks Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            background: #34495e;
            border-bottom: 1px solid #4a5f7a;
        }

        .sidebar-header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header .logo-icon {
            width: 32px;
            height: 32px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .sidebar-header .logo-text {
            font-size: 18px;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #34495e;
            color: white;
            border-left-color: #3498db;
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        .nav-submenu {
            background: #34495e;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .nav-submenu.open {
            max-height: 200px;
        }

        .nav-submenu .nav-link {
            padding-left: 50px;
            font-size: 14px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            background: #f5f7fa;
        }

        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: #7f8c8d;
        }

        .user-info a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 500;
        }

        .dashboard-content {
            padding: 30px;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .dashboard-card.packages {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            color: white;
        }

        .dashboard-card.courses {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .dashboard-card.testimonials {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .card-number {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
            line-height: 1;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .card-action {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.3s ease;
        }

        .card-action:hover {
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
            }

            .dashboard-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon"><img src="../public/img/logo.png" alt="Rainbucks Logo" style="height:32px; width:auto;" onerror="this.style.display='none';this.parentNode.textContent='R';"></div>
                    <div class="logo-text">Rainbucks</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="toggleSubmenu('packages-menu')">
                        <i class="fas fa-box"></i>
                        Packages
                        <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <div class="nav-submenu" id="packages-menu">
                        <a href="packages.php?action=add" class="nav-link">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
                        <a href="packages.php" class="nav-link">
                            <i class="fas fa-list"></i>
                            List
                        </a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="toggleSubmenu('courses-menu')">
                        <i class="fas fa-graduation-cap"></i>
                        Courses
                        <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <div class="nav-submenu" id="courses-menu">
                        <a href="courses.php?action=add" class="nav-link">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
                        <a href="courses.php" class="nav-link">
                            <i class="fas fa-list"></i>
                            List
                        </a>
                    </div>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="toggleSubmenu('testimonials-menu')">
                        <i class="fas fa-comments"></i>
                        Testimonials
                        <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                    </a>
                    <div class="nav-submenu" id="testimonials-menu">
                        <a href="testimonials.php?action=add" class="nav-link">
                            <i class="fas fa-plus"></i>
                            Add
                        </a>
                        <a href="testimonials.php" class="nav-link">
                            <i class="fas fa-list"></i>
                            List
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-header">
                <h1 class="page-title">Dashboard</h1>
                <div class="user-info">
                    <span>User</span>
                    <span>|</span>
                    <a href="logout.php">Logout</a>
                </div>
            </div>

            <div class="dashboard-content">
                <div class="dashboard-cards">
                    <div class="dashboard-card packages">
                        <div class="card-number"><?php echo $packages_count; ?></div>
                        <div class="card-title">Packages</div>
                        <div class="card-subtitle">More info</div>
                        <a href="packages.php" class="card-action">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="dashboard-card courses">
                        <div class="card-number"><?php echo $courses_count; ?></div>
                        <div class="card-title">Courses</div>
                        <div class="card-subtitle">More info</div>
                        <a href="courses.php" class="card-action">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="dashboard-card testimonials">
                        <div class="card-number"><?php echo $testimonials_count; ?></div>
                        <div class="card-title">Testimonials</div>
                        <div class="card-subtitle">More info</div>
                        <a href="testimonials.php" class="card-action">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId);
            submenu.classList.toggle('open');
        }

        // Auto-redirect to public site after successful operations
        <?php if (isset($_GET['success'])): ?>
            setTimeout(function() {
                window.open('../index.php', '_blank');
            }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
