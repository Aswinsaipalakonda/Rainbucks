<?php
/**
 * Update Admin Credentials Tool
 * This will help you change from old credentials to new ones
 */

require_once '../includes/db.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_credentials') {
        $new_email = trim($_POST['new_email'] ?? '');
        $new_password = trim($_POST['new_password'] ?? '');
        $new_name = trim($_POST['new_name'] ?? '');
        
        if (empty($new_email) || empty($new_password) || empty($new_name)) {
            $message = 'All fields are required.';
            $message_type = 'error';
        } else {
            try {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Check if old admin exists and update, or create new admin
                $old_admin = fetchOne("SELECT id FROM users WHERE email = 'admin@mywebsite.com'");
                
                if ($old_admin) {
                    // Update existing admin
                    $sql = "UPDATE users SET email = ?, password = ?, name = ? WHERE email = 'admin@mywebsite.com'";
                    executeQuery($sql, [$new_email, $hashed_password, $new_name]);
                    $message = 'Admin credentials updated successfully!';
                } else {
                    // Create new admin (delete old one first if exists)
                    executeQuery("DELETE FROM users WHERE email = ?", [$new_email]);
                    $sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')";
                    executeQuery($sql, [$new_email, $hashed_password, $new_name]);
                    $message = 'New admin account created successfully!';
                }
                
                $message_type = 'success';
                
            } catch (Exception $e) {
                $message = 'Error updating credentials: ' . $e->getMessage();
                $message_type = 'error';
            }
        }
    }
    
    if ($action === 'use_deployment_defaults') {
        try {
            // Use the new Rainbucks credentials
            $email = 'admin@rainbucks.org';
            $password = 'RB#Admin!2025';
            $name = 'Rainbucks Administrator';
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Delete any existing admin and create new one
            executeQuery("DELETE FROM users WHERE role = 'admin'");
            $sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')";
            executeQuery($sql, [$email, $hashed_password, $name]);

            $message = 'Rainbucks admin credentials set! Email: admin@rainbucks.org, Password: RB#Admin!2025';
            $message_type = 'success';

        } catch (Exception $e) {
            $message = 'Error setting credentials: ' . $e->getMessage();
            $message_type = 'error';
        }
    }

    if ($action === 'set_rainbucks_credentials') {
        try {
            // Set the specific Rainbucks credentials
            $email = 'admin@rainbucks.org';
            $password = 'RB#Admin!2025';
            $name = 'Rainbucks Administrator';
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Delete any existing admin and create new one
            executeQuery("DELETE FROM users WHERE role = 'admin'");
            $sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')";
            executeQuery($sql, [$email, $hashed_password, $name]);

            $message = 'Rainbucks admin credentials updated successfully!<br>Email: admin@rainbucks.org<br>Password: RB#Admin!2025';
            $message_type = 'success';

        } catch (Exception $e) {
            $message = 'Error setting Rainbucks credentials: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// Get current admin users
try {
    $current_admins = fetchAll("SELECT id, email, name, created_at FROM users WHERE role = 'admin'");
} catch (Exception $e) {
    $current_admins = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Credentials - Rainbucks</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .current-admins {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .current-admins h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .admin-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }
        
        .admin-item strong {
            color: #333;
        }
        
        .admin-item small {
            color: #666;
        }
        
        .section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .section h3 {
            color: #333;
            margin-bottom: 15px;
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
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
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
        
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
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
            <h1>🔐 Update Admin Credentials</h1>
            <p>Change your admin login email and password</p>
        </div>
        
        <div class="content">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($current_admins)): ?>
            <div class="current-admins">
                <h3>📋 Current Admin Users:</h3>
                <?php foreach ($current_admins as $admin): ?>
                <div class="admin-item">
                    <strong><?php echo htmlspecialchars($admin['name']); ?></strong><br>
                    <small>Email: <?php echo htmlspecialchars($admin['email']); ?> | Created: <?php echo date('M j, Y', strtotime($admin['created_at'])); ?></small>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="warning">
                <strong>⚠️ Current Issue:</strong>
                Your admin login is using old credentials (admin@mywebsite.com). Use one of the options below to fix this.
            </div>
            
            <!-- Option 1: Set Rainbucks Credentials -->
            <div class="section">
                <h3>🏢 Option 1: Set Rainbucks Admin Credentials</h3>
                <p>This will set your admin credentials to the new Rainbucks credentials:</p>
                <ul style="margin: 15px 0; padding-left: 30px;">
                    <li><strong>Email:</strong> admin@rainbucks.org</li>
                    <li><strong>Password:</strong> RB#Admin!2025</li>
                </ul>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="set_rainbucks_credentials">
                    <button type="submit" class="btn btn-success" onclick="return confirm('This will replace all existing admin accounts with Rainbucks credentials. Continue?')">
                        🔧 Set Rainbucks Credentials
                    </button>
                </form>
            </div>

            <!-- Option 2: Legacy Defaults -->
            <div class="section">
                <h3>� Option 2: Use Legacy Defaults (Backup)</h3>
                <p>This will set your admin credentials to the old deployment defaults:</p>
                <ul style="margin: 15px 0; padding-left: 30px;">
                    <li><strong>Email:</strong> admin@rainbucks.org</li>
                    <li><strong>Password:</strong> RB#Admin!2025</li>
                </ul>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="use_deployment_defaults">
                    <button type="submit" class="btn btn-warning" onclick="return confirm('This will replace all existing admin accounts. Continue?')">
                        🔧 Set Legacy Credentials
                    </button>
                </form>
            </div>
            
            <!-- Option 2: Custom Credentials -->
            <div class="section">
                <h3>⚙️ Option 2: Set Custom Credentials</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="update_credentials">
                    
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
                               placeholder="Enter secure password" required>
                    </div>
                    
                    <button type="submit" class="btn">
                        💾 Update Credentials
                    </button>
                </form>
            </div>
            
            <div class="links">
                <a href="login.php">🔑 Try Login</a>
                <a href="dashboard.php">📊 Dashboard</a>
                <a href="../index.php">🌐 View Website</a>
            </div>
        </div>
    </div>
</body>
</html>
