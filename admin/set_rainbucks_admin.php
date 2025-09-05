<?php
/**
 * Set Rainbucks Admin Credentials
 * This script will update your admin credentials to the new Rainbucks ones
 */

require_once '../includes/db.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'set_rainbucks_credentials') {
        try {
            // Set the Rainbucks credentials
            $email = 'admin@rainbucks.org';
            $password = 'RB#Admin!2025';
            $name = 'Rainbucks Administrator';
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Delete any existing admin and create new one
            executeQuery("DELETE FROM users WHERE role = 'admin'");
            $sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, 'admin')";
            executeQuery($sql, [$email, $hashed_password, $name]);
            
            $message = 'Rainbucks admin credentials set successfully!';
            $message_type = 'success';
            
        } catch (Exception $e) {
            $message = 'Error setting credentials: ' . $e->getMessage();
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
    <title>Set Rainbucks Admin Credentials</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .credentials {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .credentials strong {
            color: #0056b3;
        }
        
        .btn {
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #218838;
        }
        
        .current-admins {
            margin-top: 30px;
        }
        
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .admin-table th,
        .admin-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .admin-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .links {
            text-align: center;
            margin-top: 30px;
        }
        
        .links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .links a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏢 Set Rainbucks Admin Credentials</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="section">
            <h3>Update Admin Credentials</h3>
            <p>This will set your admin login to the new Rainbucks credentials:</p>
            
            <div class="credentials">
                <strong>Email:</strong> admin@rainbucks.org<br>
                <strong>Password:</strong> RB#Admin!2025<br>
                <strong>Name:</strong> Rainbucks Administrator
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="set_rainbucks_credentials">
                <button type="submit" class="btn" onclick="return confirm('This will replace all existing admin accounts. Continue?')">
                    🔧 Set Rainbucks Credentials
                </button>
            </form>
        </div>
        
        <div class="current-admins">
            <h3>Current Admin Users</h3>
            <?php if (!empty($current_admins)): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($current_admins as $admin): ?>
                            <tr>
                                <td><?php echo $admin['id']; ?></td>
                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($admin['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No admin users found in database.</p>
            <?php endif; ?>
        </div>
        
        <div class="links">
            <a href="login.php">🔑 Try Login</a>
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="../index.php">🌐 View Website</a>
        </div>
    </div>
</body>
</html>
