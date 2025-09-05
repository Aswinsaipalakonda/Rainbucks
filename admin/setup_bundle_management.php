<?php
/**
 * Bundle Management Setup Script
 * Run this once to set up the dynamic course bundle management system
 */

require_once '../includes/db.php';
require_once 'auth_check.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'setup_schema') {
        try {
            // Create package_courses junction table
            $sql = "CREATE TABLE IF NOT EXISTS package_courses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                package_id INT NOT NULL,
                course_id INT NOT NULL,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
                FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
                UNIQUE KEY unique_package_course (package_id, course_id)
            )";
            executeQuery($sql);
            
            // Add indexes
            executeQuery("CREATE INDEX IF NOT EXISTS idx_package_courses_package_id ON package_courses(package_id)");
            executeQuery("CREATE INDEX IF NOT EXISTS idx_package_courses_course_id ON package_courses(course_id)");
            executeQuery("CREATE INDEX IF NOT EXISTS idx_package_courses_sort_order ON package_courses(sort_order)");
            
            $message = 'Database schema created successfully!';
            $message_type = 'success';
            
        } catch (Exception $e) {
            $message = 'Error creating schema: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
    
    if ($action === 'migrate_existing') {
        try {
            // Check if courses table has package_id column
            $columns = fetchAll("SHOW COLUMNS FROM courses LIKE 'package_id'");
            
            if (!empty($columns)) {
                // Migrate existing relationships
                $sql = "INSERT IGNORE INTO package_courses (package_id, course_id, sort_order)
                        SELECT package_id, id, id as sort_order 
                        FROM courses 
                        WHERE package_id IS NOT NULL";
                executeQuery($sql);
                
                $message = 'Existing course-package relationships migrated successfully!';
                $message_type = 'success';
            } else {
                $message = 'No package_id column found in courses table. Migration not needed.';
                $message_type = 'info';
            }
            
        } catch (Exception $e) {
            $message = 'Error migrating data: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
    
    if ($action === 'create_sample_data') {
        try {
            // Create sample relationships for testing
            $sample_data = [
                [1, 1, 1], [1, 2, 2],  // Starter Package
                [2, 3, 1], [2, 4, 2],  // Professional Package
                [3, 5, 1], [3, 6, 2],  // Advanced Package
                [4, 7, 1], [4, 8, 2],  // Expert Package
                [5, 9, 1], [5, 10, 2], // Ultimate Package
            ];
            
            foreach ($sample_data as $data) {
                $sql = "INSERT IGNORE INTO package_courses (package_id, course_id, sort_order) VALUES (?, ?, ?)";
                executeQuery($sql, $data);
            }
            
            $message = 'Sample data created successfully!';
            $message_type = 'success';
            
        } catch (Exception $e) {
            $message = 'Error creating sample data: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// Check current status
try {
    $table_exists = fetchOne("SHOW TABLES LIKE 'package_courses'");
    $relationship_count = 0;
    
    if ($table_exists) {
        $count_result = fetchOne("SELECT COUNT(*) as count FROM package_courses");
        $relationship_count = $count_result['count'] ?? 0;
    }
    
    $packages_count = fetchOne("SELECT COUNT(*) as count FROM packages WHERE status = 'active'")['count'] ?? 0;
    $courses_count = fetchOne("SELECT COUNT(*) as count FROM courses WHERE status = 'active'")['count'] ?? 0;
    
} catch (Exception $e) {
    $table_exists = false;
    $relationship_count = 0;
    $packages_count = 0;
    $courses_count = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundle Management Setup - Rainbucks Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin-styles.css">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .status-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .status-item:last-child {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .setup-step {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .setup-step h3 {
            margin-bottom: 1rem;
            color: #495057;
        }
        
        .setup-step p {
            margin-bottom: 1rem;
            color: #6c757d;
        }
        
        .btn-setup {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        
        .btn-setup:hover {
            background: #0056b3;
        }
        
        .btn-setup:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content">
            <div class="setup-container">
                <h1><i class="fas fa-cogs"></i> Bundle Management Setup</h1>
                <p>Set up the dynamic course bundle management system for your Rainbucks platform.</p>
                
                <?php if (!empty($message)): ?>
                    <div class="alert <?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Current Status -->
                <div class="status-card">
                    <h2>Current Status</h2>
                    <div class="status-item">
                        <span>Junction Table (package_courses)</span>
                        <span class="status-badge <?php echo $table_exists ? 'success' : 'warning'; ?>">
                            <?php echo $table_exists ? 'Created' : 'Not Created'; ?>
                        </span>
                    </div>
                    <div class="status-item">
                        <span>Course-Package Relationships</span>
                        <span class="status-badge <?php echo $relationship_count > 0 ? 'success' : 'warning'; ?>">
                            <?php echo $relationship_count; ?> relationships
                        </span>
                    </div>
                    <div class="status-item">
                        <span>Active Packages</span>
                        <span class="status-badge success"><?php echo $packages_count; ?> packages</span>
                    </div>
                    <div class="status-item">
                        <span>Active Courses</span>
                        <span class="status-badge success"><?php echo $courses_count; ?> courses</span>
                    </div>
                </div>
                
                <!-- Setup Steps -->
                <div class="setup-step">
                    <h3><i class="fas fa-database"></i> Step 1: Create Database Schema</h3>
                    <p>Create the package_courses junction table and indexes for the bundle management system.</p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="setup_schema">
                        <button type="submit" class="btn-setup" <?php echo $table_exists ? 'disabled' : ''; ?>>
                            <?php echo $table_exists ? 'Schema Already Created' : 'Create Schema'; ?>
                        </button>
                    </form>
                </div>
                
                <div class="setup-step">
                    <h3><i class="fas fa-exchange-alt"></i> Step 2: Migrate Existing Data</h3>
                    <p>Migrate existing course-package relationships from the courses table to the new junction table.</p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="migrate_existing">
                        <button type="submit" class="btn-setup" <?php echo !$table_exists ? 'disabled' : ''; ?>>
                            Migrate Existing Data
                        </button>
                    </form>
                </div>
                
                <div class="setup-step">
                    <h3><i class="fas fa-plus"></i> Step 3: Create Sample Data (Optional)</h3>
                    <p>Create sample course-package relationships for testing the bundle management system.</p>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="create_sample_data">
                        <button type="submit" class="btn-setup" <?php echo !$table_exists ? 'disabled' : ''; ?>>
                            Create Sample Data
                        </button>
                    </form>
                </div>
                
                <!-- Next Steps -->
                <div class="status-card">
                    <h2>Next Steps</h2>
                    <ol>
                        <li>Complete the setup steps above</li>
                        <li>Go to <a href="packages.php">Packages Management</a> to edit package bundles</li>
                        <li>Test the dynamic course bundle system</li>
                        <li>Update your navigation links to use the dynamic package page</li>
                    </ol>
                </div>
                
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="packages.php" class="btn-setup">
                        <i class="fas fa-arrow-right"></i> Go to Package Management
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
