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
            // Normalize package_id: use NULL if empty to avoid FK issues
            $package_id = isset($_POST['package_id']) && $_POST['package_id'] !== '' ? (int)$_POST['package_id'] : null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $rating = isset($_POST['rating']) && $_POST['rating'] !== '' ? floatval($_POST['rating']) : 4.9;
            $status = $_POST['status'] ?? 'active';
            $image = '';
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/courses/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'course_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $image;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $message = 'Failed to upload image.';
                    $message_type = 'error';
                    break;
                }
            }
            
            if (!empty($title)) {
                try {
                    $sql = "INSERT INTO courses (package_id, title, description, duration, category, rating, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    executeQuery($sql, [$package_id, $title, $description, $duration, $category, $rating, $image, $status]);
                    $message = 'Course added successfully!';
                    $message_type = 'success';
                    header('Location: courses.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to add course: ' . $e->getMessage();
                    $message_type = 'error';
                }
            } else {
                $message = 'Please fill in all required fields.';
                $message_type = 'error';
            }
            break;
            
        case 'edit':
            $id = $_POST['id'] ?? '';
            // Normalize package_id: use NULL if empty
            $package_id = isset($_POST['package_id']) && $_POST['package_id'] !== '' ? (int)$_POST['package_id'] : null;
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $rating = isset($_POST['rating']) && $_POST['rating'] !== '' ? floatval($_POST['rating']) : 4.9;
            $status = $_POST['status'] ?? 'active';
            $current_image = $_POST['current_image'] ?? '';
            $image = $current_image;
            
            // Handle image upload for edit
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/courses/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'course_' . uniqid() . '.' . $file_extension;
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
            
            if (!empty($id) && !empty($title)) {
                try {
                    $sql = "UPDATE courses SET package_id = ?, title = ?, description = ?, duration = ?, category = ?, rating = ?, image = ?, status = ? WHERE id = ?";
                    executeQuery($sql, [$package_id, $title, $description, $duration, $category, $rating, $image, $status, $id]);
                    $message = 'Course updated successfully!';
                    $message_type = 'success';
                    header('Location: courses.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to update course: ' . $e->getMessage();
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
                    $course = fetchOne("SELECT image FROM courses WHERE id = ?", [$id]);
                    
                    // Delete from database
                    $sql = "DELETE FROM courses WHERE id = ?";
                    executeQuery($sql, [$id]);
                    
                    // Delete image file if it exists
                    if (!empty($course['image'])) {
                        $image_path = '../assets/images/courses/' . $course['image'];
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                    
                    $message = 'Course deleted successfully!';
                    $message_type = 'success';
                    header('Location: courses.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to delete course: ' . $e->getMessage();
                    $message_type = 'error';
                }
            }
            break;
    }
}

// Fetch packages for dropdown
try {
    $packages = fetchAll("SELECT id, name FROM packages WHERE status = 'active' ORDER BY name");
} catch (Exception $e) {
    $packages = [];
}

// Pagination settings
$items_per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch courses for listing with pagination
if ($action === 'list') {
    try {
        // Get total count
        $total_courses = fetchOne("SELECT COUNT(*) as count FROM courses")['count'];
        $total_pages = ceil($total_courses / $items_per_page);

        // Fetch courses with proper ordering (oldest first, newest at bottom)
        $courses = fetchAll("
            SELECT c.*, p.name as package_name
            FROM courses c
            LEFT JOIN packages p ON c.package_id = p.id
            ORDER BY c.id ASC
            LIMIT $items_per_page OFFSET $offset
        ");
    } catch (Exception $e) {
        $courses = [];
        $total_courses = 0;
        $total_pages = 0;
        $message = 'Failed to load courses: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Fetch single course for editing
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $course = fetchOne("SELECT * FROM courses WHERE id = ?", [$_GET['id']]);
        if (!$course) {
            header('Location: courses.php');
            exit();
        }
    } catch (Exception $e) {
        header('Location: courses.php');
        exit();
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
    <title>Courses Management - Rainbucks Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin-styles.css">
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
                        case 'add': echo 'Add New Course'; break;
                        case 'edit': echo 'Edit Course'; break;
                        default: echo 'Courses Management'; break;
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
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($action === 'list'): ?>
                    <!-- Course List -->
                    <div class="content-header">
                        <h2>All Courses</h2>
                        <div class="header-controls">
                            <div class="pagination-controls">
                                <label for="per_page">Show:</label>
                                <select id="per_page" onchange="changePerPage(this.value)">
                                    <option value="10" <?php echo $items_per_page == 10 ? 'selected' : ''; ?>>10 rows</option>
                                    <option value="25" <?php echo $items_per_page == 25 ? 'selected' : ''; ?>>25 rows</option>
                                    <option value="50" <?php echo $items_per_page == 50 ? 'selected' : ''; ?>>50 rows</option>
                                    <option value="100" <?php echo $items_per_page == 100 ? 'selected' : ''; ?>>100 rows</option>
                                </select>
                                <span class="total-info">Total: <?php echo $total_courses; ?> courses</span>
                            </div>
                            <a href="courses.php?action=add" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Course
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Package</th>
                                    <th>Duration</th>
                                    <th>Category</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($courses)): ?>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td><?php echo $course['id']; ?></td>
                                            <td>
                                                <?php if (!empty($course['image'])): ?>
                                                    <img src="../assets/images/courses/<?php echo htmlspecialchars($course['image']); ?>" 
                                                         alt="Course Image" class="table-image">
                                                <?php else: ?>
                                                    <span class="no-image">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($course['title']); ?></td>
                                            <td><?php echo htmlspecialchars($course['package_name'] ?? 'No Package'); ?></td>
                                            <td><?php echo htmlspecialchars($course['duration']); ?></td>
                                            <td><?php echo htmlspecialchars($course['category']); ?></td>
                                            <td><?php echo $course['rating']; ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $course['status']; ?>">
                                                    <?php echo ucfirst($course['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="courses.php?action=edit&id=<?php echo $course['id']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
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
                                        <td colspan="9" class="text-center">No courses found. <a href="courses.php?action=add">Add your first course</a></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $items_per_page, $total_courses); ?> of <?php echo $total_courses; ?> entries
                        </div>
                        <div class="pagination">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=1&per_page=<?php echo $items_per_page; ?>" class="page-link">First</a>
                                <a href="?page=<?php echo $current_page - 1; ?>&per_page=<?php echo $items_per_page; ?>" class="page-link">Previous</a>
                            <?php endif; ?>

                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);

                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                                <a href="?page=<?php echo $i; ?>&per_page=<?php echo $items_per_page; ?>"
                                   class="page-link <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo $current_page + 1; ?>&per_page=<?php echo $items_per_page; ?>" class="page-link">Next</a>
                                <a href="?page=<?php echo $total_pages; ?>&per_page=<?php echo $items_per_page; ?>" class="page-link">Last</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                    <!-- Course Form -->
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data" class="course-form">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $course['image']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="title">Course Title *</label>
                                    <input type="text" id="title" name="title" required 
                                           value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="package_id">Package</label>
                                    <select id="package_id" name="package_id">
                                        <option value="">Select Package (Optional)</option>
                                        <?php foreach ($packages as $pkg): ?>
                                            <option value="<?php echo $pkg['id']; ?>" 
                                                    <?php echo ($course['package_id'] ?? '') == $pkg['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($pkg['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="duration">Duration</label>
                                    <input type="text" id="duration" name="duration" 
                                           placeholder="e.g., 30 Classes, 2 Hours"
                                           value="<?php echo htmlspecialchars($course['duration'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <input type="text" id="category" name="category" 
                                           placeholder="e.g., Digital Marketing"
                                           value="<?php echo htmlspecialchars($course['category'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" 
                                           value="<?php echo $course['rating'] ?? '4.9'; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active" <?php echo ($course['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($course['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Course Image</label>
                                <input type="file" id="image" name="image" accept="image/*">
                                <?php if ($action === 'edit' && !empty($course['image'])): ?>
                                    <div class="current-image">
                                        <p>Current image:</p>
                                        <img src="../assets/images/courses/<?php echo htmlspecialchars($course['image']); ?>" 
                                             alt="Current Course Image" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 
                                    <?php echo $action === 'edit' ? 'Update Course' : 'Add Course'; ?>
                                </button>
                                <a href="courses.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination JavaScript -->
    <script>
    function changePerPage(value) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    }
    </script>

    <!-- Pagination CSS -->
    <style>
    .header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .pagination-controls select {
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
    }

    .total-info {
        color: #666;
        font-size: 13px;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 0;
        border-top: 1px solid #eee;
    }

    .pagination-info {
        color: #666;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        gap: 5px;
    }

    .page-link {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: #f8f9fa;
        border-color: #007bff;
        color: #007bff;
    }

    .page-link.active {
        background: #007bff;
        border-color: #007bff;
        color: white;
    }

    .page-link.active:hover {
        background: #0056b3;
        border-color: #0056b3;
    }
    </style>
</body>
</html>
