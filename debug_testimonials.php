<?php
/**
 * Debug Testimonials Display Issue
 * This will help us see what's happening with the testimonials
 */

require_once 'includes/db.php';

echo "<h1>🔍 Testimonials Debug Tool</h1>";

try {
    // Check if testimonials table exists
    echo "<h2>Step 1: Checking Testimonials Table</h2>";
    $tables = fetchAll("SHOW TABLES LIKE 'testimonials'");
    
    if (empty($tables)) {
        echo "<p>❌ Testimonials table does not exist!</p>";
        echo "<p><a href='includes/update_database.php'>Run Database Update</a></p>";
        exit;
    } else {
        echo "<p>✅ Testimonials table exists</p>";
    }
    
    // Check all testimonials
    echo "<h2>Step 2: All Testimonials in Database</h2>";
    $all_testimonials = fetchAll("SELECT * FROM testimonials ORDER BY created_at DESC");
    
    if (empty($all_testimonials)) {
        echo "<p>❌ No testimonials found in database!</p>";
        echo "<p>You need to add testimonials through the admin panel first.</p>";
        echo "<p><a href='admin/testimonials.php?action=add'>Add Testimonial</a></p>";
    } else {
        echo "<p>✅ Found " . count($all_testimonials) . " testimonials</p>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 10px;'>ID</th>";
        echo "<th style='padding: 10px;'>Name</th>";
        echo "<th style='padding: 10px;'>Company</th>";
        echo "<th style='padding: 10px;'>Status</th>";
        echo "<th style='padding: 10px;'>Featured</th>";
        echo "<th style='padding: 10px;'>Created</th>";
        echo "</tr>";
        
        foreach ($all_testimonials as $testimonial) {
            echo "<tr>";
            echo "<td style='padding: 10px;'>{$testimonial['id']}</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($testimonial['name']) . "</td>";
            echo "<td style='padding: 10px;'>" . htmlspecialchars($testimonial['company'] ?? 'N/A') . "</td>";
            echo "<td style='padding: 10px;'>";
            echo "<span style='background: " . ($testimonial['status'] === 'active' ? '#d4edda' : '#f8d7da') . "; padding: 3px 8px; border-radius: 3px;'>";
            echo $testimonial['status'];
            echo "</span>";
            echo "</td>";
            echo "<td style='padding: 10px;'>";
            echo "<span style='background: " . ($testimonial['featured'] ? '#fff3cd' : '#e2e3e5') . "; padding: 3px 8px; border-radius: 3px;'>";
            echo $testimonial['featured'] ? '⭐ YES' : 'No';
            echo "</span>";
            echo "</td>";
            echo "<td style='padding: 10px;'>" . date('M j, Y', strtotime($testimonial['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check featured testimonials specifically
    echo "<h2>Step 3: Featured Testimonials Query</h2>";
    $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    
    if (empty($featured_testimonials)) {
        echo "<p>❌ No featured testimonials found!</p>";
        echo "<p><strong>Possible issues:</strong></p>";
        echo "<ul>";
        echo "<li>No testimonials marked as 'featured'</li>";
        echo "<li>Featured testimonials are not 'active'</li>";
        echo "<li>Database column 'featured' might be different type</li>";
        echo "</ul>";
        
        // Check the featured column type
        echo "<h3>Checking 'featured' column type:</h3>";
        $columns = fetchAll("DESCRIBE testimonials");
        foreach ($columns as $column) {
            if ($column['Field'] === 'featured') {
                echo "<p>Featured column type: <strong>{$column['Type']}</strong></p>";
                echo "<p>Default value: <strong>{$column['Default']}</strong></p>";
                break;
            }
        }
        
    } else {
        echo "<p>✅ Found " . count($featured_testimonials) . " featured testimonials!</p>";
        
        echo "<h3>Featured Testimonials Details:</h3>";
        foreach ($featured_testimonials as $testimonial) {
            echo "<div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<h4>" . htmlspecialchars($testimonial['name']) . "</h4>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($testimonial['company'] ?? 'N/A') . "</p>";
            echo "<p><strong>Testimonial:</strong> " . htmlspecialchars(substr($testimonial['testimonial'], 0, 100)) . "...</p>";
            echo "<p><strong>Image:</strong> " . ($testimonial['image'] ? $testimonial['image'] : 'No image') . "</p>";
            echo "<p><strong>Rating:</strong> " . $testimonial['rating'] . " stars</p>";
            echo "</div>";
        }
    }
    
    // Test the exact query used in index.php
    echo "<h2>Step 4: Testing Index.php Query</h2>";
    echo "<p>Running the exact same query used in index.php:</p>";
    echo "<code>SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6</code>";
    
    $index_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    echo "<p>Result: " . count($index_testimonials) . " testimonials found</p>";
    
    if (!empty($index_testimonials)) {
        echo "<p>✅ Query works! The issue might be in the display logic.</p>";
    } else {
        echo "<p>❌ Query returns empty. Let's try alternative queries:</p>";
        
        // Try different variations
        $alt1 = fetchAll("SELECT * FROM testimonials WHERE featured = '1'");
        echo "<p>Featured = '1' (string): " . count($alt1) . " results</p>";
        
        $alt2 = fetchAll("SELECT * FROM testimonials WHERE featured = true");
        echo "<p>Featured = true: " . count($alt2) . " results</p>";
        
        $alt3 = fetchAll("SELECT * FROM testimonials WHERE featured != 0");
        echo "<p>Featured != 0: " . count($alt3) . " results</p>";
    }
    
    // Check if there are any PHP errors
    echo "<h2>Step 5: PHP Error Check</h2>";
    if (error_get_last()) {
        echo "<p>❌ PHP Error detected:</p>";
        echo "<pre>" . print_r(error_get_last(), true) . "</pre>";
    } else {
        echo "<p>✅ No PHP errors detected</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Database Error:</strong> " . $e->getMessage() . "</p>";
}

?>

<hr>
<h2>🔧 Quick Fixes</h2>

<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>If No Testimonials Found:</h3>
    <ol>
        <li><a href="admin/testimonials.php?action=add">Add New Testimonial</a></li>
        <li>Make sure to check "Featured Testimonial" checkbox</li>
        <li>Set status to "Active"</li>
        <li>Save and refresh homepage</li>
    </ol>
</div>

<div style="background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>If Testimonials Exist But Not Showing:</h3>
    <ol>
        <li>Check if they are marked as "Featured"</li>
        <li>Check if status is "Active"</li>
        <li>Clear browser cache</li>
        <li>Check for JavaScript errors in browser console</li>
    </ol>
</div>

<p><strong>Navigation:</strong></p>
<ul>
    <li><a href="admin/testimonials.php">Manage Testimonials</a></li>
    <li><a href="admin/dashboard.php">Admin Dashboard</a></li>
    <li><a href="index.php">View Homepage</a></li>
</ul>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
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
    border: 1px solid #ddd;
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

code {
    background: #f8f9fa;
    padding: 2px 5px;
    border-radius: 3px;
    font-family: monospace;
}

pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    overflow-x: auto;
}
</style>
