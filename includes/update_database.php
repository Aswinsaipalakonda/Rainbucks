<?php
/**
 * Database Update Script
 * Run this to add new tables for packages, courses, and testimonials
 */

require_once 'db.php';

echo "<h1>🔄 Database Update Script</h1>";

try {
    // Create packages table
    echo "<h2>Creating Packages Table...</h2>";
    $packages_sql = "CREATE TABLE IF NOT EXISTS packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        currency VARCHAR(10) DEFAULT 'INR',
        features TEXT,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    executeQuery($packages_sql);
    echo "✅ Packages table created successfully<br>";

    // Create courses table
    echo "<h2>Creating Courses Table...</h2>";
    $courses_sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        package_id INT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image VARCHAR(255) DEFAULT NULL,
        duration VARCHAR(100),
        rating DECIMAL(2,1) DEFAULT 4.9,
        category VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
    )";
    executeQuery($courses_sql);
    echo "✅ Courses table created successfully<br>";

    // Create testimonials table
    echo "<h2>Creating Testimonials Table...</h2>";
    $testimonials_sql = "CREATE TABLE IF NOT EXISTS testimonials (
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
    executeQuery($testimonials_sql);
    echo "✅ Testimonials table created successfully<br>";

    // Insert sample packages
    echo "<h2>Inserting Sample Packages...</h2>";
    $sample_packages = [
        [1, 'Starter Package', 'Perfect for beginners looking to start their learning journey', 3000.00, 'Access to 4 courses,Ideal for Beginners,Basic Support'],
        [2, 'Professional Package', 'Designed for professionals who want to enhance their skills', 5000.00, 'Access to 8 courses,Professional Level,Email Support'],
        [3, 'Advanced Package', 'For career-oriented learners seeking comprehensive knowledge', 7000.00, 'Access to 12 courses,Advanced Level,Priority Support'],
        [4, 'Expert Package', 'Includes exclusive mentorship and live sessions', 9000.00, 'Access to 16 courses,Expert Level,Live Sessions,Mentorship'],
        [5, 'Ultimate Package', 'Complete learning experience with future updates', 11000.00, 'Access to 20+ courses,Future Updates,Career Guidance,Premium Support'],
        [6, 'Super Ultimate Package', 'The most comprehensive package with AI tools', 20000.00, 'All Courses,AI Tools Access,ChatGPT Training,Premium Mentorship']
    ];

    $package_stmt = "INSERT IGNORE INTO packages (id, name, description, price, features) VALUES (?, ?, ?, ?, ?)";
    foreach ($sample_packages as $package) {
        executeQuery($package_stmt, $package);
    }
    echo "✅ Sample packages inserted<br>";

    // Insert sample courses
    echo "<h2>Inserting Sample Courses...</h2>";
    $sample_courses = [
        [1, 1, 'Social Media Marketing', 'Learn the fundamentals of social media marketing', '30 Classes', 'Digital Marketing'],
        [2, 1, 'MS Word Learning', 'Master Microsoft Word from basics to advanced features', '25 Classes', 'Productivity Tools'],
        [3, 2, 'Copy Writing', 'Learn the art of persuasive writing and content creation', '25 Classes', 'Content Creation'],
        [4, 2, 'E-mail Marketing', 'Master email marketing strategies and automation', '20 Classes', 'Digital Marketing'],
        [5, 3, 'Facebook Marketing', 'Advanced Facebook advertising and marketing techniques', '18 Classes', 'Digital Marketing'],
        [6, 3, 'How to Crack Job Interviews', 'Comprehensive guide to acing job interviews', '22 Classes', 'Career Development'],
        [7, 4, 'MS Excel Advanced', 'Advanced Excel techniques for data analysis', '30 Classes', 'Productivity Tools'],
        [8, 4, 'Personal Finance', 'Learn to manage your finances and build wealth', '24 Classes', 'Finance'],
        [9, 5, 'Personality Development', 'Enhance your personality and communication skills', '26 Classes', 'Personal Development'],
        [10, 5, 'PowerPoint Mastery', 'Create stunning presentations with PowerPoint', '18 Classes', 'Productivity Tools']
    ];

    $course_stmt = "INSERT IGNORE INTO courses (id, package_id, title, description, duration, category) VALUES (?, ?, ?, ?, ?, ?)";
    foreach ($sample_courses as $course) {
        executeQuery($course_stmt, $course);
    }
    echo "✅ Sample courses inserted<br>";

    // Insert sample testimonials
    echo "<h2>Inserting Sample Testimonials...</h2>";
    $sample_testimonials = [
        [1, 'Priya Sharma', 'Digital Marketing Manager', 'Tech Solutions Pvt Ltd', 'Rainbucks courses transformed my career. The practical approach and expert guidance helped me land my dream job in digital marketing.', 5, 1],
        [2, 'Rahul Kumar', 'Freelance Content Writer', 'Self Employed', 'The copywriting course was exceptional. I learned techniques that doubled my freelance income within 3 months.', 5, 1],
        [3, 'Anjali Patel', 'HR Executive', 'Global Corp', 'The personality development course boosted my confidence tremendously. Highly recommend Rainbucks to everyone.', 5, 0],
        [4, 'Vikash Singh', 'Data Analyst', 'Analytics Pro', 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease.', 4, 0]
    ];

    $testimonial_stmt = "INSERT IGNORE INTO testimonials (id, name, designation, company, testimonial, rating, featured) VALUES (?, ?, ?, ?, ?, ?, ?)";
    foreach ($sample_testimonials as $testimonial) {
        executeQuery($testimonial_stmt, $testimonial);
    }
    echo "✅ Sample testimonials inserted<br>";

    // Create indexes
    echo "<h2>Creating Indexes...</h2>";
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_packages_status ON packages(status)",
        "CREATE INDEX IF NOT EXISTS idx_courses_package_id ON courses(package_id)",
        "CREATE INDEX IF NOT EXISTS idx_courses_status ON courses(status)",
        "CREATE INDEX IF NOT EXISTS idx_testimonials_status ON testimonials(status)",
        "CREATE INDEX IF NOT EXISTS idx_testimonials_featured ON testimonials(featured)"
    ];

    foreach ($indexes as $index) {
        try {
            executeQuery($index);
        } catch (Exception $e) {
            // Index might already exist
        }
    }
    echo "✅ Indexes created<br>";

    echo "<hr>";
    echo "<h2>✅ Database Update Completed Successfully!</h2>";
    echo "<p><strong>New Tables Created:</strong></p>";
    echo "<ul>";
    echo "<li>📦 Packages - " . count($sample_packages) . " sample records</li>";
    echo "<li>📚 Courses - " . count($sample_courses) . " sample records</li>";
    echo "<li>💬 Testimonials - " . count($sample_testimonials) . " sample records</li>";
    echo "</ul>";

    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li><a href='../admin/dashboard.php'>Access New Admin Dashboard</a></li>";
    echo "<li><a href='../index.php'>View Updated Public Site</a></li>";
    echo "</ol>";

} catch (Exception $e) {
    echo "<p>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #f8f9fa;
    line-height: 1.6;
}

h1, h2 {
    color: #2c3e50;
}

h1 {
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}

h2 {
    margin-top: 30px;
    color: #34495e;
    border-left: 4px solid #3498db;
    padding-left: 15px;
}

ul, ol {
    margin: 15px 0;
    padding-left: 30px;
}

li {
    margin: 8px 0;
}

a {
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

hr {
    margin: 40px 0;
    border: none;
    border-top: 2px solid #bdc3c7;
}
</style>
