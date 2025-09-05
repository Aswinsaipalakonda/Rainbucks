<?php
/**
 * Debug Admin Forms
 * This will help diagnose why admin forms aren't working
 */

require_once 'db.php';

echo "<h1>🔍 Admin Forms Debug Tool</h1>";

// Check if new tables exist
echo "<h2>Step 1: Checking Database Tables</h2>";

$required_tables = ['packages', 'courses', 'testimonials'];
$existing_tables = [];

try {
    $tables = fetchAll("SHOW TABLES");
    foreach ($tables as $table) {
        $table_name = array_values($table)[0];
        $existing_tables[] = $table_name;
    }
    
    echo "<p><strong>Existing tables:</strong></p>";
    echo "<ul>";
    foreach ($existing_tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>Required tables check:</strong></p>";
    foreach ($required_tables as $table) {
        if (in_array($table, $existing_tables)) {
            echo "<p>✅ $table - EXISTS</p>";
        } else {
            echo "<p>❌ $table - MISSING</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error checking tables: " . $e->getMessage() . "</p>";
}

// Check table structures
echo "<h2>Step 2: Checking Table Structures</h2>";

foreach ($required_tables as $table) {
    if (in_array($table, $existing_tables)) {
        echo "<h3>$table table structure:</h3>";
        try {
            $columns = fetchAll("DESCRIBE $table");
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            foreach ($columns as $column) {
                echo "<tr>";
                echo "<td>{$column['Field']}</td>";
                echo "<td>{$column['Type']}</td>";
                echo "<td>{$column['Null']}</td>";
                echo "<td>{$column['Key']}</td>";
                echo "<td>{$column['Default']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (Exception $e) {
            echo "<p>❌ Error checking $table structure: " . $e->getMessage() . "</p>";
        }
    }
}

// Test form submission simulation
echo "<h2>Step 3: Testing Form Submission</h2>";

if ($_POST['test_form'] ?? false) {
    $test_type = $_POST['test_type'];
    
    echo "<h3>Testing $test_type form submission:</h3>";
    
    try {
        switch ($test_type) {
            case 'package':
                $sql = "INSERT INTO packages (name, description, price, currency, features, status) VALUES (?, ?, ?, ?, ?, ?)";
                $params = ['Test Package', 'Test Description', 100.00, 'INR', 'Test Feature 1,Test Feature 2', 'active'];
                executeQuery($sql, $params);
                echo "<p>✅ Package test insertion successful!</p>";
                
                // Clean up test data
                executeQuery("DELETE FROM packages WHERE name = 'Test Package'");
                echo "<p>🧹 Test data cleaned up</p>";
                break;
                
            case 'course':
                $sql = "INSERT INTO courses (title, description, duration, category, status) VALUES (?, ?, ?, ?, ?)";
                $params = ['Test Course', 'Test Description', '10 Classes', 'Test Category', 'active'];
                executeQuery($sql, $params);
                echo "<p>✅ Course test insertion successful!</p>";
                
                // Clean up test data
                executeQuery("DELETE FROM courses WHERE title = 'Test Course'");
                echo "<p>🧹 Test data cleaned up</p>";
                break;
                
            case 'testimonial':
                $sql = "INSERT INTO testimonials (name, designation, company, testimonial, rating, status) VALUES (?, ?, ?, ?, ?, ?)";
                $params = ['Test User', 'Test Position', 'Test Company', 'Test testimonial content', 5, 'active'];
                executeQuery($sql, $params);
                echo "<p>✅ Testimonial test insertion successful!</p>";
                
                // Clean up test data
                executeQuery("DELETE FROM testimonials WHERE name = 'Test User'");
                echo "<p>🧹 Test data cleaned up</p>";
                break;
        }
    } catch (Exception $e) {
        echo "<p>❌ Test failed: " . $e->getMessage() . "</p>";
    }
}

// Check directory permissions
echo "<h2>Step 4: Checking Directory Permissions</h2>";

$upload_dirs = [
    '../assets/images/',
    '../assets/images/packages/',
    '../assets/images/courses/',
    '../assets/images/testimonials/'
];

foreach ($upload_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<p>✅ $dir - EXISTS and WRITABLE</p>";
        } else {
            echo "<p>⚠️ $dir - EXISTS but NOT WRITABLE</p>";
        }
    } else {
        echo "<p>❌ $dir - DOES NOT EXIST</p>";
        // Try to create it
        if (mkdir($dir, 0755, true)) {
            echo "<p>✅ Created $dir successfully</p>";
        } else {
            echo "<p>❌ Failed to create $dir</p>";
        }
    }
}

?>

<hr>
<h2>🧪 Test Form Submissions</h2>
<p>Use these forms to test if database insertions work:</p>

<form method="POST" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 5px;">
    <label><strong>Test Type:</strong></label><br>
    <select name="test_type" style="padding: 8px; margin: 10px 0;">
        <option value="package">Package</option>
        <option value="course">Course</option>
        <option value="testimonial">Testimonial</option>
    </select><br>
    <button type="submit" name="test_form" value="1" style="background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 3px;">Test Form Submission</button>
</form>

<hr>
<h2>🔧 Quick Fixes</h2>

<?php if (!in_array('packages', $existing_tables) || !in_array('courses', $existing_tables) || !in_array('testimonials', $existing_tables)): ?>
<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>⚠️ Missing Tables Detected</h3>
    <p>Some required tables are missing. Please run the database update script:</p>
    <p><a href="update_database.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px;">Run Database Update</a></p>
</div>
<?php endif; ?>

<p><strong>Next Steps:</strong></p>
<ul>
    <li><a href="../admin/packages.php">Test Packages Page</a></li>
    <li><a href="../admin/dashboard.php">Back to Dashboard</a></li>
    <li><a href="update_database.php">Update Database Schema</a></li>
</ul>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2, h3 {
    color: #333;
}

table {
    width: 100%;
    margin: 10px 0;
}

th, td {
    padding: 8px;
    text-align: left;
}

th {
    background: #f8f9fa;
}

a {
    color: #007cba;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
