<?php
/**
 * Simple Test for Testimonials Display
 */

require_once 'includes/db.php';

echo "<h1>🧪 Simple Testimonials Test</h1>";

// Test the exact same code as in index.php
try {
    $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    
    echo "<h2>Query Result:</h2>";
    echo "<p>Found: " . count($featured_testimonials) . " testimonials</p>";
    
    if (!empty($featured_testimonials)) {
        echo "<h3>Testimonials Data:</h3>";
        foreach ($featured_testimonials as $testimonial) {
            echo "<div style='border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<h4>" . htmlspecialchars($testimonial['name']) . "</h4>";
            echo "<p><strong>Status:</strong> " . $testimonial['status'] . "</p>";
            echo "<p><strong>Featured:</strong> " . ($testimonial['featured'] ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($testimonial['company'] ?? 'N/A') . "</p>";
            echo "<p><strong>Testimonial:</strong> " . htmlspecialchars($testimonial['testimonial']) . "</p>";
            if ($testimonial['image']) {
                echo "<p><strong>Image:</strong> " . $testimonial['image'] . "</p>";
                $image_path = "assets/images/testimonials/" . $testimonial['image'];
                if (file_exists($image_path)) {
                    echo "<p>✅ Image file exists</p>";
                    echo "<img src='$image_path' style='width: 50px; height: 50px; border-radius: 50%; object-fit: cover;'>";
                } else {
                    echo "<p>❌ Image file missing: $image_path</p>";
                }
            }
            echo "</div>";
        }
        
        // Test the HTML output
        echo "<h3>HTML Preview (as it would appear on homepage):</h3>";
        echo "<div style='background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); padding: 40px; border-radius: 10px;'>";
        
        foreach ($featured_testimonials as $testimonial) {
            echo "<div style='background: white; border-radius: 25px; padding: 30px; margin: 20px 0; text-align: center; position: relative;'>";
            
            // Client Image
            echo "<div style='margin-bottom: 20px;'>";
            if (!empty($testimonial['image'])) {
                echo "<img src='assets/images/testimonials/" . htmlspecialchars($testimonial['image']) . "' alt='" . htmlspecialchars($testimonial['name']) . "' style='width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid white;'>";
            } else {
                echo "<div style='width: 60px; height: 60px; border-radius: 50%; background: #4CAF50; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;'>";
                echo strtoupper(substr($testimonial['name'], 0, 1));
                echo "</div>";
            }
            echo "</div>";
            
            // Client Name
            echo "<h4 style='margin: 10px 0; color: #333;'>" . htmlspecialchars($testimonial['name']) . "</h4>";
            
            // Company
            echo "<p style='color: #666; margin: 5px 0;'>" . htmlspecialchars($testimonial['company'] ?? 'Valued Client') . "</p>";
            
            // Rating
            echo "<div style='margin: 15px 0;'>";
            for ($i = 1; $i <= 5; $i++) {
                echo "<span style='color: " . ($i <= $testimonial['rating'] ? '#FFD700' : '#e0e0e0') . "; font-size: 16px;'>★</span>";
            }
            echo "</div>";
            
            // Testimonial
            echo "<blockquote style='font-style: italic; color: #555; margin: 15px 0;'>";
            echo '"' . htmlspecialchars($testimonial['testimonial']) . '"';
            echo "</blockquote>";
            
            echo "</div>";
            break; // Show only first one for preview
        }
        
        echo "</div>";
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
        echo "<h3>❌ No Featured Testimonials Found</h3>";
        echo "<p>This means either:</p>";
        echo "<ul>";
        echo "<li>No testimonials exist in the database</li>";
        echo "<li>No testimonials are marked as 'featured'</li>";
        echo "<li>Featured testimonials are not 'active'</li>";
        echo "</ul>";
        echo "</div>";
        
        // Let's check what testimonials do exist
        $all_testimonials = fetchAll("SELECT * FROM testimonials");
        echo "<h3>All Testimonials in Database:</h3>";
        echo "<p>Total count: " . count($all_testimonials) . "</p>";
        
        if (!empty($all_testimonials)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Name</th><th>Status</th><th>Featured</th><th>Actions</th></tr>";
            foreach ($all_testimonials as $t) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($t['name']) . "</td>";
                echo "<td>" . $t['status'] . "</td>";
                echo "<td>" . ($t['featured'] ? 'Yes' : 'No') . "</td>";
                echo "<td>";
                if ($t['status'] !== 'active') {
                    echo "❌ Not Active";
                }
                if (!$t['featured']) {
                    echo "❌ Not Featured";
                }
                if ($t['status'] === 'active' && $t['featured']) {
                    echo "✅ Should Show";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<h3>❌ Database Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

?>

<hr>
<h2>🔧 Quick Actions</h2>
<p><a href="admin/testimonials.php" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Manage Testimonials</a></p>
<p><a href="debug_testimonials.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Full Debug Report</a></p>
<p><a href="index.php" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Homepage</a></p>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}
h1, h2, h3 { color: #333; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 8px; border: 1px solid #ddd; }
th { background: #f8f9fa; }
</style>
