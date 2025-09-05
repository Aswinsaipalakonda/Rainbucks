<?php
/**
 * Add Sample Featured Testimonials
 * This will add sample testimonials to test the display
 */

require_once 'includes/db.php';

echo "<h1>🎯 Add Sample Featured Testimonials</h1>";

try {
    // Check if testimonials table exists
    $tables = fetchAll("SHOW TABLES LIKE 'testimonials'");
    
    if (empty($tables)) {
        echo "<p>❌ Testimonials table does not exist!</p>";
        echo "<p><a href='includes/update_database.php'>Run Database Update First</a></p>";
        exit;
    }
    
    // Sample testimonials data
    $sample_testimonials = [
        [
            'name' => 'Priya Sharma',
            'designation' => 'Digital Marketing Manager',
            'company' => 'Tech Solutions Pvt Ltd',
            'testimonial' => 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing.',
            'rating' => 5,
            'featured' => 1,
            'status' => 'active'
        ],
        [
            'name' => 'Rahul Kumar',
            'designation' => 'Freelance Content Writer',
            'company' => 'Self Employed',
            'testimonial' => 'The copywriting course was exceptional. I learned techniques that doubled my freelance income within 3 months.',
            'rating' => 5,
            'featured' => 1,
            'status' => 'active'
        ],
        [
            'name' => 'Anjali Patel',
            'designation' => 'HR Executive',
            'company' => 'Global Corp',
            'testimonial' => 'The personality development course boosted my confidence tremendously. Highly recommend Rainbucks to everyone.',
            'rating' => 5,
            'featured' => 1,
            'status' => 'active'
        ],
        [
            'name' => 'Vikash Singh',
            'designation' => 'Data Analyst',
            'company' => 'Analytics Pro',
            'testimonial' => 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease.',
            'rating' => 4,
            'featured' => 1,
            'status' => 'active'
        ]
    ];
    
    if ($_POST['add_testimonials'] ?? false) {
        echo "<h2>Adding Sample Testimonials...</h2>";
        
        $sql = "INSERT INTO testimonials (name, designation, company, testimonial, rating, featured, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $added_count = 0;
        foreach ($sample_testimonials as $testimonial) {
            try {
                // Check if testimonial with this name already exists
                $existing = fetchOne("SELECT id FROM testimonials WHERE name = ?", [$testimonial['name']]);
                
                if (!$existing) {
                    executeQuery($sql, [
                        $testimonial['name'],
                        $testimonial['designation'],
                        $testimonial['company'],
                        $testimonial['testimonial'],
                        $testimonial['rating'],
                        $testimonial['featured'],
                        $testimonial['status']
                    ]);
                    echo "<p>✅ Added: " . htmlspecialchars($testimonial['name']) . "</p>";
                    $added_count++;
                } else {
                    echo "<p>⚠️ Skipped: " . htmlspecialchars($testimonial['name']) . " (already exists)</p>";
                }
            } catch (Exception $e) {
                echo "<p>❌ Failed to add " . htmlspecialchars($testimonial['name']) . ": " . $e->getMessage() . "</p>";
            }
        }
        
        echo "<hr>";
        echo "<h2>✅ Process Complete!</h2>";
        echo "<p><strong>Added:</strong> $added_count new testimonials</p>";
        
        // Test the query
        $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1");
        echo "<p><strong>Total Featured Testimonials:</strong> " . count($featured_testimonials) . "</p>";
        
        echo "<h3>🎯 Next Steps:</h3>";
        echo "<ol>";
        echo "<li><a href='index.php' target='_blank'>View Homepage</a> - Check if testimonials appear</li>";
        echo "<li><a href='admin/testimonials.php' target='_blank'>Manage Testimonials</a> - Edit or add more</li>";
        echo "<li><a href='test_testimonials.php' target='_blank'>Test Display</a> - Debug if still not showing</li>";
        echo "</ol>";
        
    } else {
        // Show current status
        echo "<h2>Current Status:</h2>";
        
        $all_testimonials = fetchAll("SELECT * FROM testimonials");
        $featured_testimonials = fetchAll("SELECT * FROM testimonials WHERE status = 'active' AND featured = 1");
        
        echo "<p><strong>Total Testimonials:</strong> " . count($all_testimonials) . "</p>";
        echo "<p><strong>Featured & Active:</strong> " . count($featured_testimonials) . "</p>";
        
        if (!empty($featured_testimonials)) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>✅ You already have featured testimonials!</h3>";
            echo "<p>If they're not showing on the homepage, there might be a display issue.</p>";
            echo "<p><a href='test_testimonials.php'>Run Display Test</a></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>⚠️ No Featured Testimonials Found</h3>";
            echo "<p>Click the button below to add sample testimonials that will appear on your homepage.</p>";
            echo "</div>";
        }
        
        echo "<h3>Sample Testimonials to Add:</h3>";
        foreach ($sample_testimonials as $testimonial) {
            echo "<div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007cba;'>";
            echo "<h4>" . htmlspecialchars($testimonial['name']) . "</h4>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($testimonial['company']) . "</p>";
            echo "<p><strong>Testimonial:</strong> " . htmlspecialchars(substr($testimonial['testimonial'], 0, 100)) . "...</p>";
            echo "<p><strong>Rating:</strong> " . $testimonial['rating'] . " stars | <strong>Featured:</strong> Yes | <strong>Status:</strong> Active</p>";
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<h3>❌ Database Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>

<?php if (!($_POST['add_testimonials'] ?? false)): ?>
<hr>
<h2>🚀 Add Sample Testimonials</h2>
<form method="POST" style="margin: 20px 0;">
    <div style="background: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0;">
        <h3>What this will do:</h3>
        <ul>
            <li>✅ Add 4 sample testimonials</li>
            <li>✅ Mark them as "Featured"</li>
            <li>✅ Set status to "Active"</li>
            <li>✅ They will appear on your homepage immediately</li>
        </ul>
        <p><strong>Note:</strong> This won't duplicate existing testimonials.</p>
    </div>
    
    <button type="submit" name="add_testimonials" value="1" 
            style="background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
        🎯 Add Sample Featured Testimonials
    </button>
</form>
<?php endif; ?>

<hr>
<h2>🔧 Other Options</h2>
<p><a href="admin/testimonials.php?action=add" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Add Custom Testimonial</a></p>
<p><a href="debug_testimonials.php" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Full Debug Report</a></p>
<p><a href="index.php" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">View Homepage</a></p>

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

a {
    color: #007cba;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

ul, ol {
    margin: 15px 0;
    padding-left: 30px;
}

li {
    margin: 8px 0;
}
</style>
