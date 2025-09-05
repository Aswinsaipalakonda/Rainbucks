<?php
/**
 * Create Admin User Script
 * Run this script to create a fresh admin user with correct password
 */

// Include database connection
require_once 'db.php';

// Admin credentials
$email = 'admin@mywebsite.com';
$password = 'admin123';
$name = 'Administrator';
$role = 'admin';

try {
    // Hash the password properly
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    echo "<h2>Creating Admin User</h2>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Password:</strong> $password</p>";
    echo "<p><strong>Hashed Password:</strong> $hashed_password</p>";
    
    // Delete existing admin user if exists
    $delete_sql = "DELETE FROM users WHERE email = ?";
    executeQuery($delete_sql, [$email]);
    echo "<p>✅ Removed existing admin user (if any)</p>";
    
    // Insert new admin user
    $insert_sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)";
    executeQuery($insert_sql, [$email, $hashed_password, $name, $role]);
    echo "<p>✅ Created new admin user successfully!</p>";
    
    // Verify the user was created
    $verify_sql = "SELECT id, email, name, role, created_at FROM users WHERE email = ?";
    $user = fetchOne($verify_sql, [$email]);
    
    if ($user) {
        echo "<h3>✅ User Verification:</h3>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $user['id'] . "</li>";
        echo "<li><strong>Email:</strong> " . $user['email'] . "</li>";
        echo "<li><strong>Name:</strong> " . $user['name'] . "</li>";
        echo "<li><strong>Role:</strong> " . $user['role'] . "</li>";
        echo "<li><strong>Created:</strong> " . $user['created_at'] . "</li>";
        echo "</ul>";
        
        // Test password verification
        $stored_password = fetchOne("SELECT password FROM users WHERE email = ?", [$email])['password'];
        if (password_verify($password, $stored_password)) {
            echo "<p>✅ <strong>Password verification test: PASSED</strong></p>";
        } else {
            echo "<p>❌ <strong>Password verification test: FAILED</strong></p>";
        }
        
    } else {
        echo "<p>❌ Failed to verify user creation</p>";
    }
    
    echo "<hr>";
    echo "<h3>🎯 Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Go to: <a href='../admin/login.php' target='_blank'>Admin Login Page</a></li>";
    echo "<li>Use Email: <strong>$email</strong></li>";
    echo "<li>Use Password: <strong>$password</strong></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}

h2, h3 {
    color: #333;
}

p, li {
    margin: 10px 0;
}

ul, ol {
    margin: 15px 0;
}

a {
    color: #007cba;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

hr {
    margin: 30px 0;
    border: none;
    border-top: 2px solid #ddd;
}
</style>
