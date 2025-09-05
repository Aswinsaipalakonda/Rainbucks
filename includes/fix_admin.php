<?php
/**
 * Complete Admin Fix Script
 * This will diagnose and fix all admin login issues
 */

echo "<h1>🔧 Admin Login Fix Tool</h1>";

// Step 1: Test database connection
echo "<h2>Step 1: Testing Database Connection</h2>";
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'mywebsite';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful<br>";
} catch(PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    echo "<strong>Please check:</strong><br>";
    echo "- XAMPP MySQL is running<br>";
    echo "- Database 'mywebsite' exists<br>";
    exit;
}

// Step 2: Check if users table exists
echo "<h2>Step 2: Checking Users Table</h2>";
try {
    $stmt = $pdo->query("DESCRIBE users");
    echo "✅ Users table exists<br>";
    echo "<strong>Table structure:</strong><br>";
    while ($row = $stmt->fetch()) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
    }
} catch(PDOException $e) {
    echo "❌ Users table doesn't exist. Creating it...<br>";
    
    $create_table = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    try {
        $pdo->exec($create_table);
        echo "✅ Users table created successfully<br>";
    } catch(PDOException $e) {
        echo "❌ Failed to create users table: " . $e->getMessage() . "<br>";
        exit;
    }
}

// Step 3: Check existing admin users
echo "<h2>Step 3: Checking Existing Admin Users</h2>";
try {
    $stmt = $pdo->prepare("SELECT id, email, name, role, created_at FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admins = $stmt->fetchAll();
    
    if (empty($admins)) {
        echo "❌ No admin users found<br>";
    } else {
        echo "✅ Found " . count($admins) . " admin user(s):<br>";
        foreach ($admins as $admin) {
            echo "- ID: {$admin['id']}, Email: {$admin['email']}, Name: {$admin['name']}<br>";
        }
    }
} catch(PDOException $e) {
    echo "❌ Error checking admin users: " . $e->getMessage() . "<br>";
}

// Step 4: Delete existing admin and create fresh one
echo "<h2>Step 4: Creating Fresh Admin User</h2>";

$admin_email = 'admin@mywebsite.com';
$admin_password = 'admin123';
$admin_name = 'Administrator';

try {
    // Delete existing admin
    $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
    $stmt->execute([$admin_email]);
    echo "✅ Removed existing admin user (if any)<br>";
    
    // Create new password hash
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
    echo "✅ Generated new password hash: " . substr($hashed_password, 0, 30) . "...<br>";
    
    // Insert new admin
    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute([$admin_email, $hashed_password, $admin_name]);
    echo "✅ Created new admin user<br>";
    
    // Verify the user was created
    $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->execute([$admin_email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ Admin user verified in database<br>";
        echo "- User ID: " . $user['id'] . "<br>";
        echo "- Email: " . $user['email'] . "<br>";
        
        // Test password verification
        if (password_verify($admin_password, $user['password'])) {
            echo "✅ Password verification test: PASSED<br>";
        } else {
            echo "❌ Password verification test: FAILED<br>";
        }
    } else {
        echo "❌ Failed to verify admin user creation<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Error creating admin user: " . $e->getMessage() . "<br>";
}

// Step 5: Test the actual login process
echo "<h2>Step 5: Testing Login Process</h2>";

try {
    // Simulate the login process
    $test_email = $admin_email;
    $test_password = $admin_password;
    
    echo "Testing with:<br>";
    echo "- Email: $test_email<br>";
    echo "- Password: $test_password<br>";
    
    // Get user from database (same as login.php)
    $stmt = $pdo->prepare("SELECT id, email, password, name FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$test_email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User found in database<br>";
        
        if (password_verify($test_password, $user['password'])) {
            echo "✅ Password verification successful<br>";
            echo "<strong>🎉 LOGIN SHOULD WORK NOW!</strong><br>";
        } else {
            echo "❌ Password verification failed<br>";
            echo "Stored hash: " . $user['password'] . "<br>";
            echo "Test password: $test_password<br>";
        }
    } else {
        echo "❌ User not found or not admin<br>";
    }
    
} catch(PDOException $e) {
    echo "❌ Error testing login: " . $e->getMessage() . "<br>";
}

// Step 6: Check content table
echo "<h2>Step 6: Checking Content Table</h2>";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM content");
    $result = $stmt->fetch();
    echo "✅ Content table exists with " . $result['count'] . " records<br>";
    
    if ($result['count'] == 0) {
        echo "Adding sample content...<br>";
        $sample_content = [
            ['Welcome to Our Website', 'This is a sample content entry. You can edit or delete this from the admin panel.'],
            ['About Our Services', 'Learn more about what we offer and how we can help you achieve your goals.'],
            ['Contact Information', 'Get in touch with us for any inquiries or support needs.']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO content (title, description) VALUES (?, ?)");
        foreach ($sample_content as $content) {
            $stmt->execute($content);
        }
        echo "✅ Added sample content<br>";
    }
} catch(PDOException $e) {
    echo "❌ Content table issue: " . $e->getMessage() . "<br>";
    
    // Create content table if it doesn't exist
    $create_content = "CREATE TABLE content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    try {
        $pdo->exec($create_content);
        echo "✅ Content table created<br>";
    } catch(PDOException $e) {
        echo "❌ Failed to create content table: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";
echo "<h2>🎯 Summary</h2>";
echo "<p><strong>Admin Credentials:</strong></p>";
echo "<ul>";
echo "<li>Email: <strong>$admin_email</strong></li>";
echo "<li>Password: <strong>$admin_password</strong></li>";
echo "</ul>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li><a href='../admin/login.php' target='_blank'>Try Admin Login</a></li>";
echo "<li><a href='../index.php' target='_blank'>View Public Site</a></li>";
echo "<li><a href='../admin/debug_login.php' target='_blank'>Use Debug Login (if still issues)</a></li>";
echo "</ol>";

echo "<p><strong>If login still fails:</strong></p>";
echo "<ul>";
echo "<li>Clear your browser cache and cookies</li>";
echo "<li>Try in incognito/private browsing mode</li>";
echo "<li>Check browser console for JavaScript errors</li>";
echo "</ul>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: #f8f9fa;
    line-height: 1.6;
}

h1 {
    color: #2c3e50;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}

h2 {
    color: #34495e;
    margin-top: 30px;
    margin-bottom: 15px;
    padding: 10px;
    background: #ecf0f1;
    border-left: 4px solid #3498db;
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

strong {
    color: #2c3e50;
}
</style>
