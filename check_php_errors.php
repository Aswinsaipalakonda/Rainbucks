<?php
/**
 * Check for PHP errors in testimonials rendering
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 PHP Error Check for Testimonials</h1>";

// Include the same files as index.php
require_once 'includes/db.php';

echo "<h2>Step 1: Test Database Connection</h2>";
try {
    $test = fetchAll("SELECT 1");
    echo "<p>✅ Database connection OK</p>";
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Step 2: Test Testimonials Query</h2>";
try {
    $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1 ORDER BY created_at DESC LIMIT 6");
    echo "<p>✅ Query executed successfully</p>";
    echo "<p>Found: " . count($featured_testimonials) . " testimonials</p>";
    
    // If no testimonials, add sample like in index.php
    if (empty($featured_testimonials)) {
        $featured_testimonials = [
            [
                'id' => 999,
                'name' => 'Priya Sharma',
                'designation' => 'Digital Marketing Manager',
                'company' => 'Tech Solutions Pvt Ltd',
                'testimonial' => 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing.',
                'rating' => 5,
                'image' => '',
                'featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        echo "<p>⚠️ Using sample testimonial (same as index.php)</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Query error: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>Step 3: Test Testimonials Rendering</h2>";

if (!empty($featured_testimonials)) {
    echo "<p>✅ Testimonials array is not empty</p>";
    echo "<p>Testing the exact same PHP code as index.php...</p>";
    
    try {
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Rendering Test:</h3>";
        
        // Test the exact same loop as index.php
        foreach ($featured_testimonials as $testimonial) {
            echo "<div style='border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;'>";
            echo "<h4>Testing testimonial: " . htmlspecialchars($testimonial['name']) . "</h4>";
            
            // Test each field access
            echo "<p><strong>Name:</strong> ";
            try {
                echo htmlspecialchars($testimonial['name']);
                echo " ✅</p>";
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<p><strong>Company:</strong> ";
            try {
                $location_parts = [];
                if (!empty($testimonial['company'])) {
                    $location_parts[] = $testimonial['company'];
                } elseif (!empty($testimonial['designation'])) {
                    $location_parts[] = $testimonial['designation'];
                }
                echo htmlspecialchars(implode(', ', $location_parts) ?: 'Delhi');
                echo " ✅</p>";
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<p><strong>Rating:</strong> ";
            try {
                for ($i = 1; $i <= 5; $i++) {
                    echo ($i <= $testimonial['rating'] ? '★' : '☆');
                }
                echo " ✅</p>";
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<p><strong>Testimonial:</strong> ";
            try {
                echo htmlspecialchars(substr($testimonial['testimonial'], 0, 50)) . "...";
                echo " ✅</p>";
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "<p><strong>Image:</strong> ";
            try {
                if (!empty($testimonial['image'])) {
                    echo "Has image: " . $testimonial['image'];
                } else {
                    echo "No image - will show initial: " . strtoupper(substr($testimonial['name'], 0, 1));
                }
                echo " ✅</p>";
            } catch (Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "</p>";
            }
            
            echo "</div>";
            break; // Test only first one
        }
        
        echo "</div>";
        echo "<p>✅ All testimonial field access tests passed!</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Error in testimonials rendering: " . $e->getMessage() . "</p>";
        echo "<p>Stack trace:</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
} else {
    echo "<p>❌ Testimonials array is empty</p>";
}

echo "<h2>Step 4: Check for PHP Errors</h2>";
$errors = error_get_last();
if ($errors) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<h3>❌ PHP Error Detected:</h3>";
    echo "<p><strong>Type:</strong> " . $errors['type'] . "</p>";
    echo "<p><strong>Message:</strong> " . $errors['message'] . "</p>";
    echo "<p><strong>File:</strong> " . $errors['file'] . "</p>";
    echo "<p><strong>Line:</strong> " . $errors['line'] . "</p>";
    echo "</div>";
} else {
    echo "<p>✅ No PHP errors detected</p>";
}

echo "<h2>Step 5: Browser Console Check</h2>";
echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px;'>";
echo "<h3>Check Browser Console for JavaScript Errors:</h3>";
echo "<ol>";
echo "<li>Open your browser's Developer Tools (F12)</li>";
echo "<li>Go to the Console tab</li>";
echo "<li>Refresh index.php page</li>";
echo "<li>Look for any red error messages</li>";
echo "</ol>";
echo "<p>Common JavaScript errors that could hide testimonials:</p>";
echo "<ul>";
echo "<li>jQuery not loaded</li>";
echo "<li>Font Awesome icons not loading</li>";
echo "<li>CSS conflicts</li>";
echo "<li>JavaScript syntax errors</li>";
echo "</ul>";
echo "</div>";

?>

<hr>
<h2>🔧 Next Steps</h2>

<div style="background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
    <h3>Based on the results above:</h3>
    <ol>
        <li><strong>If all tests pass:</strong> The issue is likely JavaScript/CSS related</li>
        <li><strong>If PHP errors found:</strong> Fix the specific error mentioned</li>
        <li><strong>If database issues:</strong> Check database connection and table structure</li>
    </ol>
</div>

<p><a href="index.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Index Page</a></p>
<p><a href="test_index_testimonials.php" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Detailed Test</a></p>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}
h1, h2, h3 { color: #333; }
pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
</style>
