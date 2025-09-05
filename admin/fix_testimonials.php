<?php
/**
 * Fix Testimonials Table
 * Run this if testimonials are not working
 */

require_once '../includes/db.php';

echo "<h1>🔧 Testimonials Fix Tool</h1>";

try {
    // Check if testimonials table exists
    echo "<h2>Step 1: Checking Testimonials Table</h2>";
    
    $tables = fetchAll("SHOW TABLES LIKE 'testimonials'");
    
    if (empty($tables)) {
        echo "<p>❌ Testimonials table does not exist. Creating it...</p>";
        
        // Create testimonials table
        $create_sql = "CREATE TABLE testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            designation VARCHAR(255),
            company VARCHAR(255),
            testimonial TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            rating INT DEFAULT 5,
            status ENUM('active', 'inactive') DEFAULT 'active',
            featured BOOLEAN DEFAULT FALSE,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        executeQuery($create_sql);
        echo "<p>✅ Testimonials table created successfully!</p>";
        
        // Insert sample data
        echo "<h3>Adding Sample Testimonials...</h3>";
        $sample_testimonials = [
            ['Priya Sharma', 'Digital Marketing Manager', 'Tech Solutions Pvt Ltd', 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing.', 5, 1],
            ['Rahul Kumar', 'Freelance Content Writer', 'Self Employed', 'The copywriting course was exceptional. I learned techniques that doubled my freelance income within 3 months.', 5, 1],
            ['Anjali Patel', 'HR Executive', 'Global Corp', 'The personality development course boosted my confidence tremendously. Highly recommend Rainbucks to everyone.', 5, 0],
            ['Vikash Singh', 'Data Analyst', 'Analytics Pro', 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease.', 4, 0]
        ];
        
        $insert_sql = "INSERT INTO testimonials (name, designation, company, testimonial, rating, featured) VALUES (?, ?, ?, ?, ?, ?)";
        foreach ($sample_testimonials as $testimonial) {
            executeQuery($insert_sql, $testimonial);
        }
        echo "<p>✅ Sample testimonials added!</p>";
        
    } else {
        echo "<p>✅ Testimonials table exists</p>";
        
        // Check table structure
        echo "<h3>Table Structure:</h3>";
        $columns = fetchAll("DESCRIBE testimonials");
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
        
        // Count existing testimonials
        $count = fetchOne("SELECT COUNT(*) as count FROM testimonials")['count'];
        echo "<p>📊 Current testimonials count: <strong>$count</strong></p>";
    }
    
    // Check upload directory
    echo "<h2>Step 2: Checking Upload Directory</h2>";
    $upload_dir = '../assets/images/testimonials/';
    
    if (!is_dir($upload_dir)) {
        echo "<p>❌ Upload directory does not exist. Creating it...</p>";
        if (mkdir($upload_dir, 0755, true)) {
            echo "<p>✅ Upload directory created: $upload_dir</p>";
        } else {
            echo "<p>❌ Failed to create upload directory</p>";
        }
    } else {
        echo "<p>✅ Upload directory exists: $upload_dir</p>";
        
        if (is_writable($upload_dir)) {
            echo "<p>✅ Directory is writable</p>";
        } else {
            echo "<p>⚠️ Directory is not writable. Please set permissions to 755 or 777</p>";
        }
    }
    
    // Test testimonials functionality
    echo "<h2>Step 3: Testing Testimonials Functionality</h2>";
    
    if ($_POST['test_testimonial'] ?? false) {
        try {
            $test_sql = "INSERT INTO testimonials (name, designation, company, testimonial, rating, status) VALUES (?, ?, ?, ?, ?, ?)";
            $test_data = ['Test User', 'Test Position', 'Test Company', 'This is a test testimonial to verify functionality.', 5, 'active'];
            executeQuery($test_sql, $test_data);
            echo "<p>✅ Test testimonial inserted successfully!</p>";
            
            // Clean up test data
            executeQuery("DELETE FROM testimonials WHERE name = 'Test User'");
            echo "<p>🧹 Test data cleaned up</p>";
            
        } catch (Exception $e) {
            echo "<p>❌ Test failed: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>✅ Testimonials System Status</h2>";
    echo "<p><strong>✅ All checks completed!</strong></p>";
    echo "<p>Your testimonials system should now be working properly.</p>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li><a href='testimonials.php' target='_blank'>Test Testimonials Management</a></li>";
    echo "<li><a href='testimonials.php?action=add' target='_blank'>Add New Testimonial</a></li>";
    echo "<li><a href='dashboard.php'>Return to Dashboard</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
}
?>

<hr>
<h2>🧪 Test Testimonials Functionality</h2>
<form method="POST" style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 5px;">
    <p>Click the button below to test if testimonials can be added to the database:</p>
    <button type="submit" name="test_testimonial" value="1" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer;">
        🧪 Test Testimonial Insertion
    </button>
</form>

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

ol, ul {
    margin: 15px 0;
    padding-left: 30px;
}

li {
    margin: 8px 0;
}
</style>
