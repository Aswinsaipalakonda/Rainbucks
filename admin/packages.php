<?php
// Include authentication check
require_once 'auth_check.php';
require_once '../includes/db.php';

$message = '';
$message_type = '';
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_action = $_POST['action'] ?? '';
    
    switch ($form_action) {
        case 'add':
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $currency = $_POST['currency'] ?? 'INR';
            $features = trim($_POST['features'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $overview_title = trim($_POST['overview_title'] ?? '');
            $overview_content = trim($_POST['overview_content'] ?? '');
            $what_you_learn = trim($_POST['what_you_learn'] ?? '');
            $why_choose_title = trim($_POST['why_choose_title'] ?? '');
            $why_choose_content = trim($_POST['why_choose_content'] ?? '');
            $why_choose_points = trim($_POST['why_choose_points'] ?? '');
            $image = '';
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/packages/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'package_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $image;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $message = 'Failed to upload image.';
                    $message_type = 'error';
                    break;
                }
            }
            
            if (!empty($name) && $price > 0) {
                try {
                    // Get the current max sort_order
                    $max_sort_order = fetchOne("SELECT MAX(sort_order) AS max_order FROM packages");
                    $new_sort_order = ($max_sort_order && isset($max_sort_order['max_order'])) ? ((int)$max_sort_order['max_order'] + 1) : 1;

                    // Check if new fields exist in the table
                    $columns_check = fetchAll("SHOW COLUMNS FROM packages LIKE 'overview_title'");

                    if (!empty($columns_check)) {
                        // New fields exist, use full insert
                        $sql = "INSERT INTO packages (name, description, price, currency, features, image, status, overview_title, overview_content, what_you_learn, why_choose_title, why_choose_content, why_choose_points, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        executeQuery($sql, [$name, $description, $price, $currency, $features, $image, $status, $overview_title, $overview_content, $what_you_learn, $why_choose_title, $why_choose_content, $why_choose_points, $new_sort_order]);
                    } else {
                        // New fields don't exist, use basic insert
                        $sql = "INSERT INTO packages (name, description, price, currency, features, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        executeQuery($sql, [$name, $description, $price, $currency, $features, $image, $status, $new_sort_order]);

                        $message = 'Package added successfully! Note: New content fields are not available yet. <a href=\"migrate_package_fields.php\">Run database migration</a> to enable them.';
                        $message_type = 'warning';
                        break;
                    }
                    $message = 'Package added successfully!';
                    $message_type = 'success';
                    header('Location: packages.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to add package: ' . $e->getMessage();
                    $message_type = 'error';
                }
            } else {
                $message = 'Please fill in all required fields.';
                $message_type = 'error';
            }
            break;
            
        case 'edit':
            $id = $_POST['id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = floatval($_POST['price'] ?? 0);
            $currency = $_POST['currency'] ?? 'INR';
            $features = trim($_POST['features'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $overview_title = trim($_POST['overview_title'] ?? '');
            $overview_content = trim($_POST['overview_content'] ?? '');
            $what_you_learn = trim($_POST['what_you_learn'] ?? '');
            $why_choose_title = trim($_POST['why_choose_title'] ?? '');
            $why_choose_content = trim($_POST['why_choose_content'] ?? '');
            $why_choose_points = trim($_POST['why_choose_points'] ?? '');
            $current_image = $_POST['current_image'] ?? '';
            $image = $current_image;
            
            // Handle image upload for edit
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/packages/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'package_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $image;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if it exists
                    if (!empty($current_image) && file_exists($upload_dir . $current_image)) {
                        unlink($upload_dir . $current_image);
                    }
                } else {
                    $message = 'Failed to upload new image.';
                    $message_type = 'error';
                    break;
                }
            }
            
            if (!empty($id) && !empty($name) && $price > 0) {
                try {
                    // Check if new fields exist in the table
                    $columns_check = fetchAll("SHOW COLUMNS FROM packages LIKE 'overview_title'");

                    if (!empty($columns_check)) {
                        // New fields exist, use full update
                        $sql = "UPDATE packages SET name = ?, description = ?, price = ?, currency = ?, features = ?, image = ?, status = ?, overview_title = ?, overview_content = ?, what_you_learn = ?, why_choose_title = ?, why_choose_content = ?, why_choose_points = ? WHERE id = ?";
                        executeQuery($sql, [$name, $description, $price, $currency, $features, $image, $status, $overview_title, $overview_content, $what_you_learn, $why_choose_title, $why_choose_content, $why_choose_points, $id]);
                    } else {
                        // New fields don't exist, use basic update
                        $sql = "UPDATE packages SET name = ?, description = ?, price = ?, currency = ?, features = ?, image = ?, status = ? WHERE id = ?";
                        executeQuery($sql, [$name, $description, $price, $currency, $features, $image, $status, $id]);

                        $message = 'Package updated successfully! Note: New content fields are not available yet. <a href="migrate_package_fields.php">Run database migration</a> to enable them.';
                        $message_type = 'warning';
                        break;
                    }
                    $message = 'Package updated successfully!';
                    $message_type = 'success';
                    header('Location: packages.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to update package: ' . $e->getMessage();
                    $message_type = 'error';
                }
            } else {
                $message = 'Please fill in all required fields.';
                $message_type = 'error';
            }
            break;
            
        case 'delete':
            $id = $_POST['id'] ?? '';
            if (!empty($id)) {
                try {
                    // Get image filename before deleting
                    $package = fetchOne("SELECT image FROM packages WHERE id = ?", [$id]);
                    
                    // Delete from database
                    $sql = "DELETE FROM packages WHERE id = ?";
                    executeQuery($sql, [$id]);
                    
                    // Delete image file if it exists
                    if (!empty($package['image'])) {
                        $image_path = '../assets/images/packages/' . $package['image'];
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                    
                    $message = 'Package deleted successfully!';
                    $message_type = 'success';
                    header('Location: packages.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to delete package.';
                    $message_type = 'error';
                }
            }
            break;
    }
}

// Fetch packages for listing
if ($action === 'list') {
    try {
        $packages = fetchAll("SELECT * FROM packages ORDER BY sort_order ASC, created_at DESC");
    } catch (Exception $e) {
        $packages = [];
        $message = 'Failed to load packages.';
        $message_type = 'error';
    }
}

// Fetch single package for editing
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $package = fetchOne("SELECT * FROM packages WHERE id = ?", [$_GET['id']]);
        if (!$package) {
            header('Location: packages.php');
            exit();
        }

        // Fetch courses in this package (tolerant if bundle table missing)
        $package_courses = [];
        $bundle_warning = '';
        try {
            // Combine junction-based and legacy-based relationships, excluding duplicates
            $package_courses = fetchAll("
                SELECT c.id, c.title, c.category, c.duration, c.rating, c.image, pc.sort_order
                FROM courses c
                JOIN package_courses pc ON c.id = pc.course_id
                WHERE pc.package_id = ? AND c.status = 'active'
                UNION ALL
                SELECT c.id, c.title, c.category, c.duration, c.rating, c.image, c.sort_order AS sort_order
                FROM courses c
                WHERE c.status = 'active'
                  AND c.package_id = ?
                  AND NOT EXISTS (
                      SELECT 1 FROM package_courses pc
                      WHERE pc.package_id = ? AND pc.course_id = c.id
                  )
                ORDER BY sort_order ASC, title ASC
            ", [$package['id'], $package['id'], $package['id']]);
        } catch (Exception $e) {
            // As a last resort, keep the page usable without course list
            $package_courses = [];
            $bundle_warning = 'Unable to load courses for this package. You can still edit package details.';
        }

    } catch (Exception $e) {
        // If fetching the package itself fails, go back to list
        header('Location: packages.php');
        exit();
    }

    // Surface any non-blocking warnings
    if (!empty($bundle_warning)) {
        $message = $bundle_warning;
        if (empty($message_type)) { $message_type = 'warning'; }
    }
}

// Get current admin info
$admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages Management - Rainbucks Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin-styles.css">
    <style>
        /* Form Section Styles */
        .form-section {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .form-section h3 {
            color: #495057;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            font-size: 1.2rem;
        }

        .form-section .form-group {
            margin-bottom: 15px;
        }

        .form-section textarea {
            min-height: 100px;
        }

        /* Bundle Management Styles */
        .bundle-management {
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
            margin-top: 15px;
        }

        .current-courses, .add-courses {
            margin-bottom: 20px;
        }

        .current-courses h4, .add-courses h4 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .courses-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: white;
        }

        .course-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: move;
        }

        .course-item:last-child {
            border-bottom: none;
        }

        .course-item:hover {
            background: #f8f9fa;
        }

        .course-info {
            flex: 1;
        }

        .course-title {
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }

        .course-meta {
            font-size: 12px;
            color: #6c757d;
        }

        .course-actions {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }

        .search-container {
            position: relative;
            margin-bottom: 15px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 6px 6px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .search-result-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #e9ecef;
        }

        .search-result-item:hover {
            background: #f8f9fa;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .or-divider {
            text-align: center;
            margin: 15px 0;
            color: #6c757d;
            font-weight: 500;
        }

        .dropdown-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .dropdown-container select {
            flex: 1;
        }

        .drag-handle {
            color: #6c757d;
            cursor: move;
            margin-right: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Include Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-header">
                <h1 class="page-title">
                    <?php
                    switch($action) {
                        case 'add': echo 'Add New Package'; break;
                        case 'edit': echo 'Edit Package'; break;
                        default: echo 'Packages Management'; break;
                    }
                    ?>
                </h1>
                <div class="user-info">
                    <span>User</span>
                    <span>|</span>
                    <a href="logout.php">Logout</a>
                </div>
            </div>

            <div class="dashboard-content">
                <!-- Messages -->
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Migration Check -->
                <?php
                try {
                    $columns_check = fetchAll("SHOW COLUMNS FROM packages LIKE 'overview_title'");
                    if (empty($columns_check)):
                ?>
                    <div class="alert alert-warning">
                        <strong><i class="fas fa-exclamation-triangle"></i> Database Migration Required:</strong>
                        New content fields for dynamic course pages are not available yet.
                        <a href="migrate_package_fields.php" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                            <i class="fas fa-database"></i> Run Migration
                        </a>
                    </div>
                <?php
                    endif;
                } catch (Exception $e) {
                    // Ignore errors in migration check
                }
                ?>

                <?php if ($action === 'list'): ?>
                    <!-- Package List -->
                    <div class="content-header">
                        <h2>All Packages</h2>
                        <a href="packages.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Package
                        </a>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($packages)): ?>
                                    <?php foreach ($packages as $pkg): ?>
                                        <tr>
                                            <td><?php echo $pkg['id']; ?></td>
                                            <td>
                                                <?php if (!empty($pkg['image'])): ?>
                                                    <img src="../assets/images/packages/<?php echo htmlspecialchars($pkg['image']); ?>"
                                                         alt="Package Image" class="table-image">
                                                <?php else: ?>
                                                    <span class="no-image">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($pkg['name']); ?></td>
                                            <td><?php echo $pkg['currency'] . ' ' . number_format($pkg['price'], 2); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $pkg['status']; ?>">
                                                    <?php echo ucfirst($pkg['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($pkg['created_at'])); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="packages.php?action=edit&id=<?php echo $pkg['id']; ?>"
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this package?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $pkg['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No packages found. <a href="packages.php?action=add">Add your first package</a></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                    <!-- Package Form -->
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data" class="package-form">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo $package['id']; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $package['image']; ?>">
                            <?php endif; ?>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Package Name *</label>
                                    <input type="text" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($package['name'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="price">Price *</label>
                                    <input type="number" id="price" name="price" step="0.01" min="0" required
                                           value="<?php echo $package['price'] ?? ''; ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select id="currency" name="currency">
                                        <option value="INR" <?php echo ($package['currency'] ?? 'INR') === 'INR' ? 'selected' : ''; ?>>INR (₹)</option>
                                        <option value="USD" <?php echo ($package['currency'] ?? '') === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                        <option value="EUR" <?php echo ($package['currency'] ?? '') === 'EUR' ? 'selected' : ''; ?>>EUR (€)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active" <?php echo ($package['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($package['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($package['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="features">Features (one per line)</label>
                                <textarea id="features" name="features" rows="6"
                                          placeholder="Access to 4 courses&#10;Ideal for Beginners&#10;Basic Support"><?php echo htmlspecialchars($package['features'] ?? ''); ?></textarea>
                            </div>

                            <!-- Course Overview Section -->
                            <div class="form-section">
                                <h3>Course Overview Section</h3>

                                <div class="form-group">
                                    <label for="overview_title">Overview Title</label>
                                    <input type="text" id="overview_title" name="overview_title"
                                           placeholder="Course Overview"
                                           value="<?php echo htmlspecialchars($package['overview_title'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="overview_content">Overview Content</label>
                                    <textarea id="overview_content" name="overview_content" rows="6"
                                              placeholder="Describe what this package offers and why it's valuable..."><?php echo htmlspecialchars($package['overview_content'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="what_you_learn">What You'll Learn (one per line)</label>
                                    <textarea id="what_you_learn" name="what_you_learn" rows="6"
                                              placeholder="Business fundamentals and strategy&#10;Marketing and customer acquisition&#10;Financial planning and management"><?php echo htmlspecialchars($package['what_you_learn'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <!-- Why Choose Section -->
                            <div class="form-section">
                                <h3>Why Choose Section</h3>

                                <div class="form-group">
                                    <label for="why_choose_title">Why Choose Title</label>
                                    <input type="text" id="why_choose_title" name="why_choose_title"
                                           placeholder="Why Choose This Package?"
                                           value="<?php echo htmlspecialchars($package['why_choose_title'] ?? ''); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="why_choose_content">Why Choose Content</label>
                                    <textarea id="why_choose_content" name="why_choose_content" rows="6"
                                              placeholder="Explain what makes this package special and different from others..."><?php echo htmlspecialchars($package['why_choose_content'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="why_choose_points">Why Choose Points (one per line)</label>
                                    <textarea id="why_choose_points" name="why_choose_points" rows="6"
                                              placeholder="Expert instructors with industry experience&#10;Practical, hands-on learning approach&#10;Comprehensive curriculum designed for success"><?php echo htmlspecialchars($package['why_choose_points'] ?? ''); ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image">Package Image</label>
                                <input type="file" id="image" name="image" accept="image/*">
                                <?php if ($action === 'edit' && !empty($package['image'])): ?>
                                    <div class="current-image">
                                        <p>Current image:</p>
                                        <img src="../assets/images/packages/<?php echo htmlspecialchars($package['image']); ?>"
                                             alt="Current Package Image" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($action === 'edit'): ?>
                                <!-- Course Bundle Management -->
                                <div class="form-group">
                                    <label>Course Bundle Management</label>
                                    <div class="bundle-management">
                                        <!-- Current Courses in Package -->
                                        <div class="current-courses">
                                            <h4>Courses in this Package</h4>
                                            <div id="package-courses-list" class="courses-list">
                                                <!-- Will be populated by JavaScript -->
                                            </div>
                                        </div>

                                        <!-- Add New Courses -->
                                        <div class="add-courses">
                                            <h4>Add Courses to Package</h4>
                                            <div class="search-container">
                                                <input type="text" id="course-search" placeholder="Search courses..." class="form-control">
                                                <div id="search-results" class="search-results"></div>
                                            </div>
                                            <div class="or-divider">OR</div>
                                            <div class="dropdown-container">
                                                <select id="course-dropdown" class="form-control">
                                                    <option value="">Select a course to add...</option>
                                                </select>
                                                <button type="button" id="add-selected-course" class="btn btn-success">
                                                    <i class="fas fa-plus"></i> Add Course
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $action === 'edit' ? 'Update Package' : 'Add Package'; ?>
                                </button>
                                <a href="packages.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Bundle Management JavaScript
        class BundleManager {
            constructor(packageId) {
                this.packageId = packageId;
                this.searchTimeout = null;
                this.init();
            }

            init() {
                this.loadPackageCourses();
                this.loadAvailableCourses();
                this.setupEventListeners();
                this.setupSortable();
            }

            setupEventListeners() {
                // Course search
                const searchInput = document.getElementById('course-search');
                if (searchInput) {
                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            this.searchCourses(e.target.value);
                        }, 300);
                    });

                    // Hide search results when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!searchInput.contains(e.target)) {
                            document.getElementById('search-results').style.display = 'none';
                        }
                    });
                }

                // Add selected course button
                const addButton = document.getElementById('add-selected-course');
                if (addButton) {
                    addButton.addEventListener('click', () => {
                        const dropdown = document.getElementById('course-dropdown');
                        if (dropdown.value) {
                            this.addCourseToPackage(dropdown.value);
                        }
                    });
                }
            }

            setupSortable() {
                const coursesList = document.getElementById('package-courses-list');
                if (coursesList) {
                    new Sortable(coursesList, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onEnd: (evt) => {
                            this.updateCourseOrder();
                        }
                    });
                }
            }

            async loadPackageCourses() {
                try {
                    const response = await fetch(`api/bundle_management.php?action=get_package_courses&package_id=${this.packageId}`);
                    const data = await response.json();

                    if (data.success) {
                        this.renderPackageCourses(data.data);
                    }
                } catch (error) {
                    console.error('Error loading package courses:', error);
                }
            }

            async loadAvailableCourses() {
                try {
                    const response = await fetch(`api/bundle_management.php?action=get_all_courses&package_id=${this.packageId}`);
                    const data = await response.json();

                    if (data.success) {
                        this.populateDropdown(data.data);
                    }
                } catch (error) {
                    console.error('Error loading available courses:', error);
                }
            }

            async searchCourses(query) {
                if (query.length < 2) {
                    document.getElementById('search-results').style.display = 'none';
                    return;
                }

                try {
                    const response = await fetch(`api/bundle_management.php?action=search_courses&q=${encodeURIComponent(query)}&package_id=${this.packageId}`);
                    const data = await response.json();

                    if (data.success) {
                        this.renderSearchResults(data.data);
                    }
                } catch (error) {
                    console.error('Error searching courses:', error);
                }
            }

            renderPackageCourses(courses) {
                const container = document.getElementById('package-courses-list');

                if (courses.length === 0) {
                    container.innerHTML = '<div class="empty-state">No courses in this package yet.</div>';
                    return;
                }

                container.innerHTML = courses.map(course => `
                    <div class="course-item" data-course-id="${course.id}">
                        <div class="drag-handle">
                            <i class="fas fa-grip-vertical"></i>
                        </div>
                        <div class="course-info">
                            <div class="course-title">${course.title}</div>
                            <div class="course-meta">
                                ${course.category} • ${course.duration} • ⭐ ${course.rating}
                            </div>
                        </div>
                        <div class="course-actions">
                            <button type="button" class="btn btn-danger btn-sm" onclick="bundleManager.removeCourseFromPackage(${course.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }

            renderSearchResults(courses) {
                const container = document.getElementById('search-results');

                if (courses.length === 0) {
                    container.innerHTML = '<div class="search-result-item">No courses found</div>';
                } else {
                    container.innerHTML = courses.map(course => `
                        <div class="search-result-item" onclick="bundleManager.addCourseToPackage(${course.id})">
                            <div class="course-title">${course.title}</div>
                            <div class="course-meta">${course.category} • ${course.duration} • ⭐ ${course.rating}</div>
                        </div>
                    `).join('');
                }

                container.style.display = 'block';
            }

            populateDropdown(courses) {
                const dropdown = document.getElementById('course-dropdown');
                dropdown.innerHTML = '<option value="">Select a course to add...</option>';

                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = `${course.title} (${course.category})`;
                    dropdown.appendChild(option);
                });
            }

            async addCourseToPackage(courseId) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'add_course_to_package');
                    formData.append('package_id', this.packageId);
                    formData.append('course_id', courseId);

                    const response = await fetch('api/bundle_management.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.loadPackageCourses();
                        this.loadAvailableCourses();
                        document.getElementById('search-results').style.display = 'none';
                        document.getElementById('course-search').value = '';
                        document.getElementById('course-dropdown').value = '';
                        this.showMessage('Course added successfully!', 'success');
                    } else {
                        this.showMessage(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error adding course:', error);
                    this.showMessage('Error adding course', 'error');
                }
            }

            async removeCourseFromPackage(courseId) {
                if (!confirm('Are you sure you want to remove this course from the package?')) {
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('action', 'remove_course_from_package');
                    formData.append('package_id', this.packageId);
                    formData.append('course_id', courseId);

                    const response = await fetch('api/bundle_management.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.loadPackageCourses();
                        this.loadAvailableCourses();
                        this.showMessage('Course removed successfully!', 'success');
                    } else {
                        this.showMessage(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error removing course:', error);
                    this.showMessage('Error removing course', 'error');
                }
            }

            async updateCourseOrder() {
                const courseItems = document.querySelectorAll('#package-courses-list .course-item');
                const courseOrders = {};

                courseItems.forEach((item, index) => {
                    const courseId = item.dataset.courseId;
                    courseOrders[courseId] = index + 1;
                });

                try {
                    const formData = new FormData();
                    formData.append('action', 'update_course_order');
                    formData.append('package_id', this.packageId);
                    formData.append('course_orders', JSON.stringify(courseOrders));

                    const response = await fetch('api/bundle_management.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showMessage('Course order updated!', 'success');
                    }
                } catch (error) {
                    console.error('Error updating course order:', error);
                }
            }

            showMessage(message, type) {
                // Create a simple toast notification
                const toast = document.createElement('div');
                toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
                toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);';
                toast.textContent = message;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
        }

        // Initialize bundle manager if we're editing a package
        <?php if ($action === 'edit' && isset($package['id'])): ?>
        let bundleManager;
        document.addEventListener('DOMContentLoaded', function() {
            bundleManager = new BundleManager(<?php echo $package['id']; ?>);
        });
        <?php endif; ?>
    </script>
</body>
</html>
