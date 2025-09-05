<?php
/**
 * Admin Password Change Tool
 * Use this script to change admin credentials safely
 * DELETE THIS FILE AFTER USE FOR SECURITY!
 */

require_once '../includes/db.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_email = trim($_POST['current_email'] ?? '');
    $new_email = trim($_POST['new_email'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $new_name = trim($_POST['new_name'] ?? '');
    
    // Validation
    if (empty($current_email) || empty($new_email) || empty($new_password) || empty($new_name)) {
        $message = 'All fields are required.';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = 'New password and confirm password do not match.';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $message_type = 'error';
    } else {
        try {
            // Check if current email exists
            $user = fetchOne("SELECT id FROM users WHERE email = ?", [$current_email]);
            
            if (!$user) {
                $message = 'Current email not found in database.';
                $message_type = 'error';
            } else {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update user credentials
                $sql = "UPDATE users SET email = ?, password = ?, name = ?, updated_at = NOW() WHERE email = ?";
                executeQuery($sql, [$new_email, $hashed_password, $new_name, $current_email]);
                
                $message = 'Admin credentials updated successfully! You can now login with your new credentials.';
                $message_type = 'success';
                
                // Log the change
                error_log("Admin credentials changed from $current_email to $new_email at " . date('Y-m-d H:i:s'));
            }
        } catch (Exception $e) {
            $message = 'Error updating credentials: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// Get current admin users
try {
    $current_users = fetchAll("SELECT id, email, name, created_at FROM users WHERE role = 'admin'");
} catch (Exception $e) {
    $current_users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Credentials - Rainbucks</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 16px;
        }
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .warning strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .current-users {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .current-users h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .user-item {
            background: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .links {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e1e5e9;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
        }
        
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Change Admin Credentials</h1>
            <p>Update your admin login email and password</p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Security Warning:</strong>
            Delete this file (change_password.php) after changing your credentials for security reasons!
        </div>
        
        <?php if (!empty($current_users)): ?>
        <div class="current-users">
            <h3>Current Admin Users:</h3>
            <?php foreach ($current_users as $user): ?>
            <div class="user-item">
                <strong><?php echo htmlspecialchars($user['name']); ?></strong><br>
                <small>Email: <?php echo htmlspecialchars($user['email']); ?> | Created: <?php echo date('M j, Y', strtotime($user['created_at'])); ?></small>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="current_email">Current Email Address</label>
                <input type="email" id="current_email" name="current_email" 
                       placeholder="admin@rainbucks.com" required>
            </div>
            
            <div class="form-group">
                <label for="new_email">New Email Address</label>
                <input type="email" id="new_email" name="new_email" 
                       placeholder="your-email@domain.com" required>
            </div>
            
            <div class="form-group">
                <label for="new_name">Your Name</label>
                <input type="text" id="new_name" name="new_name" 
                       placeholder="Your Full Name" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" 
                       placeholder="Enter new password (min 6 characters)" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Confirm your new password" required>
            </div>
            
            <button type="submit" class="btn">Update Admin Credentials</button>
        </form>
        
        <div class="links">
            <a href="login.php">← Back to Login</a>
            <a href="dashboard.php">Go to Dashboard</a>
            <a href="../index.php">View Website</a>
        </div>
    </div>
</body>
</html>
