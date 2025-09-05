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
            $designation = trim($_POST['designation'] ?? '');
            $company = trim($_POST['company'] ?? '');
            $testimonial = trim($_POST['testimonial'] ?? '');
            $rating = intval($_POST['rating'] ?? 5);
            $status = $_POST['status'] ?? 'active';
            $featured = isset($_POST['featured']) ? 1 : 0;
            $image = '';
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/testimonials/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'testimonial_' . uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $image;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $message = 'Failed to upload image.';
                    $message_type = 'error';
                    break;
                }
            }
            
            if (!empty($name) && !empty($testimonial)) {
                try {
                    $sql = "INSERT INTO testimonials (name, designation, company, testimonial, rating, featured, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    executeQuery($sql, [$name, $designation, $company, $testimonial, $rating, $featured, $image, $status]);
                    $message = 'Testimonial added successfully!';
                    $message_type = 'success';
                    header('Location: testimonials.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to add testimonial: ' . $e->getMessage();
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
            $designation = trim($_POST['designation'] ?? '');
            $company = trim($_POST['company'] ?? '');
            $testimonial = trim($_POST['testimonial'] ?? '');
            $rating = intval($_POST['rating'] ?? 5);
            $status = $_POST['status'] ?? 'active';
            $featured = isset($_POST['featured']) ? 1 : 0;
            $current_image = $_POST['current_image'] ?? '';
            $image = $current_image;
            
            // Handle image upload for edit
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../assets/images/testimonials/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image = 'testimonial_' . uniqid() . '.' . $file_extension;
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
            
            if (!empty($id) && !empty($name) && !empty($testimonial)) {
                try {
                    $sql = "UPDATE testimonials SET name = ?, designation = ?, company = ?, testimonial = ?, rating = ?, featured = ?, image = ?, status = ? WHERE id = ?";
                    executeQuery($sql, [$name, $designation, $company, $testimonial, $rating, $featured, $image, $status, $id]);
                    $message = 'Testimonial updated successfully!';
                    $message_type = 'success';
                    header('Location: testimonials.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to update testimonial: ' . $e->getMessage();
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
                    $testimonial = fetchOne("SELECT image FROM testimonials WHERE id = ?", [$id]);
                    
                    // Delete from database
                    $sql = "DELETE FROM testimonials WHERE id = ?";
                    executeQuery($sql, [$id]);
                    
                    // Delete image file if it exists
                    if (!empty($testimonial['image'])) {
                        $image_path = '../assets/images/testimonials/' . $testimonial['image'];
                        if (file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                    
                    $message = 'Testimonial deleted successfully!';
                    $message_type = 'success';
                    header('Location: testimonials.php?success=1');
                    exit();
                } catch (Exception $e) {
                    $message = 'Failed to delete testimonial: ' . $e->getMessage();
                    $message_type = 'error';
                }
            }
            break;
    }
}

// Pagination settings
$items_per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch testimonials for listing with pagination
if ($action === 'list') {
    try {
        // Get total count
        $total_testimonials = fetchOne("SELECT COUNT(*) as count FROM testimonials")['count'];
        $total_pages = ceil($total_testimonials / $items_per_page);

        // Fetch testimonials with proper ordering (oldest first, newest at bottom)
        $testimonials = fetchAll("
            SELECT * FROM testimonials
            ORDER BY id ASC
            LIMIT $items_per_page OFFSET $offset
        ");
    } catch (Exception $e) {
        $testimonials = [];
        $total_testimonials = 0;
        $total_pages = 0;
        $message = 'Failed to load testimonials: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Fetch single testimonial for editing
if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $testimonial = fetchOne("SELECT * FROM testimonials WHERE id = ?", [$_GET['id']]);
        if (!$testimonial) {
            header('Location: testimonials.php');
            exit();
        }
    } catch (Exception $e) {
        header('Location: testimonials.php');
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
    <title>Testimonials Management - Rainbucks Admin</title>
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
                        case 'add': echo 'Add New Testimonial'; break;
                        case 'edit': echo 'Edit Testimonial'; break;
                        default: echo 'Testimonials Management'; break;
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
                    <!-- Testimonial List -->
                    <div class="content-header">
                        <h2>All Testimonials</h2>
                        <div class="header-controls">
                            <div class="pagination-controls">
                                <label for="per_page">Show:</label>
                                <select id="per_page" onchange="changePerPage(this.value)">
                                    <option value="10" <?php echo $items_per_page == 10 ? 'selected' : ''; ?>>10 rows</option>
                                    <option value="25" <?php echo $items_per_page == 25 ? 'selected' : ''; ?>>25 rows</option>
                                    <option value="50" <?php echo $items_per_page == 50 ? 'selected' : ''; ?>>50 rows</option>
                                    <option value="100" <?php echo $items_per_page == 100 ? 'selected' : ''; ?>>100 rows</option>
                                </select>
                                <span class="total-info">Total: <?php echo $total_testimonials; ?> testimonials</span>
                            </div>
                            <a href="testimonials.php?action=add" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Testimonial
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Company</th>
                                    <th>Rating</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($testimonials)): ?>
                                    <?php foreach ($testimonials as $test): ?>
                                        <tr>
                                            <td><?php echo $test['id']; ?></td>
                                            <td>
                                                <?php if (!empty($test['image'])): ?>
                                                    <img src="../assets/images/testimonials/<?php echo htmlspecialchars($test['image']); ?>" 
                                                         alt="Testimonial Image" class="table-image">
                                                <?php else: ?>
                                                    <span class="no-image">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($test['name']); ?></td>
                                            <td><?php echo htmlspecialchars($test['designation']); ?></td>
                                            <td><?php echo htmlspecialchars($test['company']); ?></td>
                                            <td>
                                                <div class="rating-stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $test['rating'] ? 'active' : ''; ?>"></i>
                                                    <?php endfor; ?>
                                                    <span>(<?php echo $test['rating']; ?>)</span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($test['featured']): ?>
                                                    <span class="featured-badge">⭐ Featured</span>
                                                <?php else: ?>
                                                    <span class="not-featured">Regular</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $test['status']; ?>">
                                                    <?php echo ucfirst($test['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="testimonials.php?action=edit&id=<?php echo $test['id']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?php echo $test['id']; ?>">
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
                                        <td colspan="9" class="text-center">No testimonials found. <a href="testimonials.php?action=add">Add your first testimonial</a></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $items_per_page, $total_testimonials); ?> of <?php echo $total_testimonials; ?> entries
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
                    <!-- Testimonial Form -->
                    <div class="form-container">
                        <form method="POST" enctype="multipart/form-data" class="testimonial-form">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $testimonial['image']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Customer Name *</label>
                                    <input type="text" id="name" name="name" required 
                                           value="<?php echo htmlspecialchars($testimonial['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="designation">Designation</label>
                                    <input type="text" id="designation" name="designation" 
                                           placeholder="e.g., Marketing Manager"
                                           value="<?php echo htmlspecialchars($testimonial['designation'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company">Company</label>
                                    <input type="text" id="company" name="company" 
                                           placeholder="e.g., Tech Solutions Pvt Ltd"
                                           value="<?php echo htmlspecialchars($testimonial['company'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <select id="rating" name="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo $i; ?>" 
                                                    <?php echo ($testimonial['rating'] ?? 5) == $i ? 'selected' : ''; ?>>
                                                <?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="testimonial">Testimonial Text *</label>
                                <textarea id="testimonial" name="testimonial" rows="6" required 
                                          placeholder="Write the customer's testimonial here..."><?php echo htmlspecialchars($testimonial['testimonial'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active" <?php echo ($testimonial['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($testimonial['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="featured">
                                        <input type="checkbox" id="featured" name="featured" value="1" 
                                               <?php echo ($testimonial['featured'] ?? 0) ? 'checked' : ''; ?>>
                                        Featured Testimonial
                                    </label>
                                    <small>Featured testimonials appear prominently on the website</small>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Customer Photo</label>
                                <input type="file" id="image" name="image" accept="image/*">
                                <?php if ($action === 'edit' && !empty($testimonial['image'])): ?>
                                    <div class="current-image">
                                        <p>Current image:</p>
                                        <img src="../assets/images/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" 
                                             alt="Current Testimonial Image" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 
                                    <?php echo $action === 'edit' ? 'Update Testimonial' : 'Add Testimonial'; ?>
                                </button>
                                <a href="testimonials.php" class="btn btn-secondary">
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
