<?php
/**
 * Password Testing Script
 * This will help us understand exactly what's happening with the password
 */

echo "<h1>🔐 Password Testing Tool</h1>";

// Test password hashing and verification
$test_password = 'admin123';
echo "<h2>Testing Password: '$test_password'</h2>";

// Generate multiple hashes to test
echo "<h3>Generated Hashes:</h3>";
for ($i = 1; $i <= 3; $i++) {
    $hash = password_hash($test_password, PASSWORD_DEFAULT);
    $verify = password_verify($test_password, $hash) ? "✅ VALID" : "❌ INVALID";
    echo "<p><strong>Hash $i:</strong><br>";
    echo "<code>$hash</code><br>";
    echo "<strong>Verification:</strong> $verify</p>";
}

// Test database connection and user
echo "<h3>Database Test:</h3>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=mywebsite;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get current admin user
    $stmt = $pdo->prepare("SELECT email, password FROM users WHERE email = 'admin@mywebsite.com'");
    $stmt->execute();
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ User found in database</p>";
        echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
        echo "<p><strong>Stored Hash:</strong><br><code>" . $user['password'] . "</code></p>";
        
        // Test verification with stored hash
        $verify_result = password_verify($test_password, $user['password']);
        echo "<p><strong>Password Verification:</strong> " . ($verify_result ? "✅ SUCCESS" : "❌ FAILED") . "</p>";
        
        if (!$verify_result) {
            echo "<p><strong>🔧 Fixing password...</strong></p>";
            
            // Generate new hash and update
            $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@mywebsite.com'");
            $update_stmt->execute([$new_hash]);
            
            echo "<p>✅ Password updated with new hash</p>";
            echo "<p><strong>New Hash:</strong><br><code>$new_hash</code></p>";
            
            // Test again
            $verify_new = password_verify($test_password, $new_hash);
            echo "<p><strong>New Password Verification:</strong> " . ($verify_new ? "✅ SUCCESS" : "❌ FAILED") . "</p>";
        }
        
    } else {
        echo "<p>❌ No user found with email 'admin@mywebsite.com'</p>";
        
        // Create the user
        echo "<p><strong>🔧 Creating admin user...</strong></p>";
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        $insert_stmt = $pdo->prepare("INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')");
        $insert_stmt->execute(['admin@mywebsite.com', $new_hash, 'Administrator']);
        
        echo "<p>✅ Admin user created</p>";
        echo "<p><strong>Hash:</strong><br><code>$new_hash</code></p>";
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🎯 Quick Test Form</h2>";
echo "<p>Test the login directly here:</p>";

if ($_POST['test_login'] ?? false) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h3>Testing Login:</h3>";
    echo "<p>Email: $email</p>";
    echo "<p>Password: $password</p>";
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, password, name FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            echo "<p>✅ <strong>LOGIN SUCCESS!</strong></p>";
            echo "<p>User: " . $user['name'] . " (ID: " . $user['id'] . ")</p>";
        } else {
            echo "<p>❌ <strong>LOGIN FAILED</strong></p>";
            if (!$user) {
                echo "<p>Reason: User not found</p>";
            } else {
                echo "<p>Reason: Password mismatch</p>";
            }
        }
    } catch(Exception $e) {
        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    }
}
?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
    <div style="margin-bottom: 15px;">
        <label><strong>Email:</strong></label><br>
        <input type="email" name="email" value="admin@mywebsite.com" style="width: 300px; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label><strong>Password:</strong></label><br>
        <input type="password" name="password" value="admin123" style="width: 300px; padding: 8px;">
    </div>
    <button type="submit" name="test_login" value="1" style="background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 3px;">Test Login</button>
</form>

<p><strong>Next Steps:</strong></p>
<ul>
    <li><a href="../admin/login.php">Try Normal Login Page</a></li>
    <li><a href="../admin/debug_login.php">Try Debug Login Page</a></li>
    <li><a href="../index.php">View Public Website</a></li>
</ul>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2, h3 {
    color: #333;
}

code {
    background: #f4f4f4;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
    word-break: break-all;
}

p {
    margin: 10px 0;
}

hr {
    margin: 30px 0;
    border: none;
    border-top: 2px solid #ddd;
}

a {
    color: #007cba;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
