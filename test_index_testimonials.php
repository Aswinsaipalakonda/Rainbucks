<?php
/**
 * Test the exact same code as index.php for testimonials
 */

// Include the same files as index.php
require_once 'includes/db.php';

echo "<h1>🧪 Index.php Testimonials Test</h1>";

// Test the exact same query as index.php
echo "<h2>Step 1: Database Connection Test</h2>";
try {
    $test_query = fetchAll("SELECT 1 as test");
    echo "<p>✅ Database connection working</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Step 2: Testimonials Query Test</h2>";
try {
    $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    echo "<p>✅ Query executed successfully</p>";
    echo "<p><strong>Found:</strong> " . count($featured_testimonials) . " testimonials</p>";
    
    if (empty($featured_testimonials)) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24; margin: 15px 0;'>";
        echo "<h3>❌ No Featured Testimonials Found!</h3>";
        echo "<p>This is why the testimonials section is empty on index.php</p>";
        echo "</div>";
        
        // Check what testimonials exist
        $all_testimonials = fetchAll("SELECT * FROM testimonials");
        echo "<h3>All Testimonials in Database:</h3>";
        echo "<p>Total: " . count($all_testimonials) . "</p>";
        
        if (!empty($all_testimonials)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 15px 0;'>";
            echo "<tr style='background: #f8f9fa;'><th style='padding: 8px;'>Name</th><th style='padding: 8px;'>Status</th><th style='padding: 8px;'>Featured</th><th style='padding: 8px;'>Issue</th></tr>";
            
            foreach ($all_testimonials as $t) {
                echo "<tr>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($t['name']) . "</td>";
                echo "<td style='padding: 8px;'>" . $t['status'] . "</td>";
                echo "<td style='padding: 8px;'>" . ($t['featured'] ? 'Yes' : 'No') . "</td>";
                echo "<td style='padding: 8px;'>";
                
                $issues = [];
                if ($t['status'] !== 'active') {
                    $issues[] = "Status not 'active'";
                }
                if (!$t['featured']) {
                    $issues[] = "Not featured";
                }
                
                if (empty($issues)) {
                    echo "<span style='color: green;'>✅ Should show</span>";
                } else {
                    echo "<span style='color: red;'>❌ " . implode(', ', $issues) . "</span>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; margin: 15px 0;'>";
        echo "<h3>✅ Featured Testimonials Found!</h3>";
        echo "<p>The query is working correctly. Let's check the data:</p>";
        echo "</div>";
        
        foreach ($featured_testimonials as $i => $testimonial) {
            echo "<div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007cba;'>";
            echo "<h4>Testimonial #" . ($i + 1) . ": " . htmlspecialchars($testimonial['name']) . "</h4>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($testimonial['company'] ?? 'N/A') . "</p>";
            echo "<p><strong>Status:</strong> " . $testimonial['status'] . "</p>";
            echo "<p><strong>Featured:</strong> " . ($testimonial['featured'] ? 'Yes' : 'No') . "</p>";
            echo "<p><strong>Rating:</strong> " . $testimonial['rating'] . " stars</p>";
            echo "<p><strong>Testimonial:</strong> " . htmlspecialchars(substr($testimonial['testimonial'], 0, 100)) . "...</p>";
            if ($testimonial['image']) {
                echo "<p><strong>Image:</strong> " . $testimonial['image'];
                $image_path = "assets/images/testimonials/" . $testimonial['image'];
                if (file_exists($image_path)) {
                    echo " ✅ (exists)";
                } else {
                    echo " ❌ (missing)";
                }
                echo "</p>";
            }
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Query failed: " . $e->getMessage() . "</p>";
}

echo "<h2>Step 3: Simulate Index.php Logic</h2>";

if (!empty($featured_testimonials)) {
    echo "<p>✅ The if (!empty(\$featured_testimonials)) condition would be TRUE</p>";
    echo "<p>This means the testimonials slider should display.</p>";
    
    echo "<h3>HTML Preview (First Testimonial):</h3>";
    $testimonial = $featured_testimonials[0];
    
    echo "<div style='background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); padding: 40px; border-radius: 10px; color: white;'>";
    echo "<h2 style='text-align: center; margin-bottom: 30px;'>What Our Clients Say</h2>";
    
    echo "<div style='max-width: 800px; margin: 0 auto;'>";
    echo "<div style='background: white; border-radius: 25px; padding: 50px 40px; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.15); position: relative; margin: 20px 0; color: #333;'>";
    
    // Client Image
    echo "<div style='position: absolute; top: -40px; left: 50%; transform: translateX(-50%);'>";
    if (!empty($testimonial['image'])) {
        echo "<img src='assets/images/testimonials/" . htmlspecialchars($testimonial['image']) . "' alt='" . htmlspecialchars($testimonial['name']) . "' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 5px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.1);'>";
    } else {
        echo "<div style='width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold; border: 5px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.1);'>";
        echo strtoupper(substr($testimonial['name'], 0, 1));
        echo "</div>";
    }
    echo "</div>";
    
    // Client Name and Location
    echo "<div style='margin-top: 50px; margin-bottom: 25px;'>";
    echo "<h3 style='font-size: 1.4rem; color: #333; margin-bottom: 8px; font-weight: 600;'>";
    echo htmlspecialchars($testimonial['name']);
    echo "</h3>";
    echo "<p style='color: #666; font-size: 1rem;'>";
    $location_parts = [];
    if (!empty($testimonial['company'])) {
        $location_parts[] = $testimonial['company'];
    } elseif (!empty($testimonial['designation'])) {
        $location_parts[] = $testimonial['designation'];
    }
    echo htmlspecialchars(implode(', ', $location_parts) ?: 'Delhi');
    echo "</p>";
    echo "</div>";
    
    // Star Rating
    echo "<div style='margin-bottom: 30px;'>";
    for ($i = 1; $i <= 5; $i++) {
        echo "<span style='color: " . ($i <= $testimonial['rating'] ? '#FFD700' : '#e0e0e0') . "; font-size: 20px; margin: 0 3px;'>★</span>";
    }
    echo "</div>";
    
    // Testimonial Text
    echo "<blockquote style='font-size: 1.2rem; line-height: 1.7; color: #555; font-style: italic; margin: 0; position: relative; max-width: 600px; margin: 0 auto;'>";
    echo "<span style='font-size: 4rem; color: #e0e0e0; position: absolute; top: -30px; left: -20px; font-family: serif; line-height: 1;'>&quot;</span>";
    echo htmlspecialchars($testimonial['testimonial']);
    echo "<span style='font-size: 4rem; color: #e0e0e0; position: absolute; bottom: -50px; right: -20px; font-family: serif; line-height: 1;'>&quot;</span>";
    echo "</blockquote>";
    
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
} else {
    echo "<p>❌ The if (!empty(\$featured_testimonials)) condition would be FALSE</p>";
    echo "<p>This means the 'No featured testimonials available yet' message should display.</p>";
}

?>

<hr>
<h2>🔧 Next Steps</h2>

<?php if (empty($featured_testimonials)): ?>
<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>⚠️ No Featured Testimonials - Action Required</h3>
    <ol>
        <li><a href="add_sample_testimonials.php">Add Sample Testimonials</a></li>
        <li><a href="admin/testimonials.php">Manage Existing Testimonials</a></li>
        <li>Make sure testimonials are marked as "Featured" and "Active"</li>
    </ol>
</div>
<?php else: ?>
<div style="background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>✅ Testimonials Found - Check Display</h3>
    <ol>
        <li><a href="index.php">View Homepage</a> - Check if testimonials now appear</li>
        <li>If still not showing, check browser console for JavaScript errors</li>
        <li>Clear browser cache and refresh</li>
    </ol>
</div>
<?php endif; ?>

<p><a href="index.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Homepage</a></p>
<p><a href="admin/testimonials.php" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Manage Testimonials</a></p>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}
h1, h2, h3 { color: #333; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 8px; border: 1px solid #ddd; }
th { background: #f8f9fa; }
a { color: #007cba; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
