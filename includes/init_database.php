<?php
/**
 * Database Initialization Script
 * Run this file once to set up the database and tables
 */

// Database configuration for initial setup
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // First, connect without specifying database to create it
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS mywebsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'mywebsite' created successfully.<br>";
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=mywebsite;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_users);
    echo "Users table created successfully.<br>";
    
    // Create content table
    $sql_content = "CREATE TABLE IF NOT EXISTS content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_content);
    echo "Content table created successfully.<br>";
    
    // Insert default admin user (password: admin123)
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_admin = "INSERT IGNORE INTO users (email, password, name, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql_admin);
    $stmt->execute(['admin@mywebsite.com', $admin_password, 'Administrator', 'admin']);
    echo "Default admin user created (email: admin@mywebsite.com, password: admin123).<br>";
    
    // Insert sample content
    $sample_content = [
        ['Welcome to Our Website', 'This is a sample content entry. You can edit or delete this from the admin panel.', 'sample1.jpg'],
        ['About Our Services', 'Learn more about what we offer and how we can help you achieve your goals.', 'sample2.jpg'],
        ['Contact Information', 'Get in touch with us for any inquiries or support needs.', 'sample3.jpg']
    ];
    
    $sql_content_insert = "INSERT IGNORE INTO content (title, description, image) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql_content_insert);
    
    foreach ($sample_content as $content) {
        $stmt->execute($content);
    }
    echo "Sample content inserted successfully.<br>";
    
    // Create indexes (only if they don't exist)
    try {
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_content_status ON content(status)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_content_created_at ON content(created_at)");
        echo "Database indexes created successfully.<br>";
    } catch(PDOException $e) {
        // Indexes might already exist, which is fine
        echo "Database indexes checked (some may already exist).<br>";
    }
    
    echo "<br><strong>Database initialization completed successfully!</strong><br>";
    echo "You can now use the admin panel with:<br>";
    echo "Email: admin@mywebsite.com<br>";
    echo "Password: admin123<br>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
