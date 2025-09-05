<?php
// Database migration to add new content fields to packages table
require_once '../includes/db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['migrate'])) {
    try {
        // Check if columns already exist
        $columns_check = fetchAll("SHOW COLUMNS FROM packages LIKE 'overview_title'");
        
        if (empty($columns_check)) {
            // Add new columns to packages table
            $sql = "ALTER TABLE packages 
                    ADD COLUMN overview_title VARCHAR(255) DEFAULT NULL AFTER features,
                    ADD COLUMN overview_content TEXT DEFAULT NULL AFTER overview_title,
                    ADD COLUMN what_you_learn TEXT DEFAULT NULL AFTER overview_content,
                    ADD COLUMN why_choose_title VARCHAR(255) DEFAULT NULL AFTER what_you_learn,
                    ADD COLUMN why_choose_content TEXT DEFAULT NULL AFTER why_choose_title,
                    ADD COLUMN why_choose_points TEXT DEFAULT NULL AFTER why_choose_content";
            
            executeQuery($sql);
            
            // Update existing packages with default content
            $update_sql = "UPDATE packages SET 
                overview_title = 'Course Overview',
                overview_content = CONCAT('This ', REPLACE(name, ' Package', ''), ' Package is your gateway to mastering essential skills. Our comprehensive curriculum covers topics that professionals need to know.\\n\\nYou\\'ll learn from industry experts who have years of real-world experience. Each course is designed to be practical and immediately applicable to your career journey.'),
                what_you_learn = 'Business fundamentals and strategy\\nMarketing and customer acquisition\\nFinancial planning and management\\nLeadership and team building\\nDigital marketing essentials\\nProject management skills',
                why_choose_title = 'Why Choose This Package?',
                why_choose_content = CONCAT('Our ', REPLACE(name, ' Package', ''), ' Package stands out from the competition with its comprehensive approach to learning and practical application.\\n\\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.'),
                why_choose_points = 'Expert instructors with industry experience\\nPractical, hands-on learning approach\\nComprehensive curriculum designed for success\\nLifetime access to all course materials\\nCertificate of completion\\n24/7 support and community access'
                WHERE overview_title IS NULL";
            
            executeQuery($update_sql);
            
            $message = 'Database migration completed successfully! New content fields have been added to the packages table.';
            $message_type = 'success';
        } else {
            $message = 'Migration already completed. The new fields already exist in the packages table.';
            $message_type = 'info';
        }
        
    } catch (Exception $e) {
        $message = 'Migration failed: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Check current table structure
try {
    $columns = fetchAll("SHOW COLUMNS FROM packages");
    $has_new_fields = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'overview_title') {
            $has_new_fields = true;
            break;
        }
    }
} catch (Exception $e) {
    $columns = [];
    $has_new_fields = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Fields Migration - Rainbucks Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin-styles.css">
    <style>
        .migration-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-box {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .status-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .status-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .status-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .table-structure {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .column-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        .column-item {
            background: white;
            padding: 8px 12px;
            border-radius: 3px;
            border-left: 3px solid #007bff;
        }
        .new-field {
            border-left-color: #28a745 !important;
            background: #f8fff9 !important;
        }
    </style>
</head>
<body>
    <div class="migration-container">
        <h1><i class="fas fa-database"></i> Package Fields Migration</h1>
        <p>This tool will add the new content fields to the packages table for the dynamic course page sections.</p>
        
        <?php if ($message): ?>
            <div class="status-box status-<?php echo $message_type; ?>">
                <strong><?php echo ucfirst($message_type); ?>:</strong> <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="table-structure">
            <h3>Current Packages Table Structure:</h3>
            <?php if (!empty($columns)): ?>
                <div class="column-list">
                    <?php foreach ($columns as $column): ?>
                        <div class="column-item <?php echo in_array($column['Field'], ['overview_title', 'overview_content', 'what_you_learn', 'why_choose_title', 'why_choose_content', 'why_choose_points']) ? 'new-field' : ''; ?>">
                            <strong><?php echo htmlspecialchars($column['Field']); ?></strong><br>
                            <small><?php echo htmlspecialchars($column['Type']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="status-error">Unable to fetch table structure.</p>
            <?php endif; ?>
        </div>
        
        <?php if (!$has_new_fields): ?>
            <div class="status-box status-warning">
                <strong>Migration Required:</strong> The new content fields are not present in the packages table. 
                Click the button below to add them.
            </div>
            
            <form method="POST">
                <button type="submit" name="migrate" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">
                    <i class="fas fa-play"></i> Run Migration
                </button>
            </form>
        <?php else: ?>
            <div class="status-box status-success">
                <strong>Migration Complete:</strong> All new content fields are present in the packages table.
            </div>
            
            <p><a href="packages.php" class="btn btn-success"><i class="fas fa-arrow-left"></i> Back to Packages</a></p>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <h4>New Fields Being Added:</h4>
            <ul>
                <li><strong>overview_title</strong> - Title for the course overview section</li>
                <li><strong>overview_content</strong> - Main content for the overview section</li>
                <li><strong>what_you_learn</strong> - List of learning outcomes</li>
                <li><strong>why_choose_title</strong> - Title for the why choose section</li>
                <li><strong>why_choose_content</strong> - Main content for why choose section</li>
                <li><strong>why_choose_points</strong> - List of benefits/reasons</li>
            </ul>
        </div>
    </div>
</body>
</html>
