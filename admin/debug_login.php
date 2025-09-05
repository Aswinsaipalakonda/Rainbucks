<?php
/**
 * Debug Login Script
 * Use this to troubleshoot login issues
 */

session_start();
require_once '../includes/db.php';

$debug_info = [];
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $debug_info[] = "Form submitted with email: " . $email;
    $debug_info[] = "Password length: " . strlen($password);
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        try {
            // Check if user exists
            $sql = "SELECT id, email, password, name FROM users WHERE email = ?";
            $debug_info[] = "SQL Query: " . $sql;
            $debug_info[] = "Looking for email: " . $email;
            
            $user = fetchOne($sql, [$email]);
            
            if ($user) {
                $debug_info[] = "✅ User found in database";
                $debug_info[] = "User ID: " . $user['id'];
                $debug_info[] = "User Name: " . $user['name'];
                $debug_info[] = "Stored password hash: " . substr($user['password'], 0, 20) . "...";
                
                // Test password verification
                $password_match = password_verify($password, $user['password']);
                $debug_info[] = "Password verification result: " . ($password_match ? "✅ MATCH" : "❌ NO MATCH");
                
                if ($password_match) {
                    $debug_info[] = "✅ Login should be successful!";
                    
                    // Set session variables
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_name'] = $user['name'];
                    
                    $debug_info[] = "✅ Session variables set";
                    $debug_info[] = "Redirecting to dashboard...";
                    
                    // Redirect after 3 seconds
                    header("refresh:3;url=dashboard.php");
                    $error_message = "Login successful! Redirecting to dashboard...";
                } else {
                    $error_message = 'Invalid password.';
                    
                    // Additional password debugging
                    $debug_info[] = "Password debugging:";
                    $debug_info[] = "- Input password: '" . $password . "'";
                    $debug_info[] = "- Stored hash: " . $user['password'];
                    $debug_info[] = "- Hash algorithm: " . password_get_info($user['password'])['algoName'];
                }
            } else {
                $debug_info[] = "❌ No user found with email: " . $email;
                $error_message = 'User not found.';
                
                // Check if any users exist
                $all_users = fetchAll("SELECT email FROM users");
                $debug_info[] = "All users in database:";
                foreach ($all_users as $u) {
                    $debug_info[] = "- " . $u['email'];
                }
            }
        } catch (Exception $e) {
            $error_message = 'Database error: ' . $e->getMessage();
            $debug_info[] = "❌ Exception: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Login - My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .login-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .debug-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .debug-info h3 {
            margin-top: 0;
            color: #495057;
        }
        
        .debug-info ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .debug-info li {
            margin: 5px 0;
            font-family: monospace;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn {
            background: #007cba;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background: #005a87;
        }
        
        .error {
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .success {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .links {
            margin-top: 20px;
        }
        
        .links a {
            color: #007cba;
            text-decoration: none;
            margin-right: 15px;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h1>🔍 Debug Login</h1>
        <p>Use this form to debug login issues. Default credentials:</p>
        <ul>
            <li><strong>Email:</strong> admin@mywebsite.com</li>
            <li><strong>Password:</strong> admin123</li>
        </ul>
        
        <?php if (!empty($error_message)): ?>
            <div class="<?php echo strpos($error_message, 'successful') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? 'admin@mywebsite.com'); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required
                       value="<?php echo htmlspecialchars($_POST['password'] ?? 'admin123'); ?>">
            </div>
            
            <button type="submit" class="btn">Debug Login</button>
        </form>
        
        <div class="links">
            <a href="login.php">← Back to Normal Login</a>
            <a href="../includes/create_admin.php">Create Fresh Admin User</a>
            <a href="../index.php">View Public Site</a>
        </div>
    </div>
    
    <?php if (!empty($debug_info)): ?>
    <div class="debug-info">
        <h3>🔍 Debug Information</h3>
        <ul>
            <?php foreach ($debug_info as $info): ?>
                <li><?php echo htmlspecialchars($info); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</body>
</html>
