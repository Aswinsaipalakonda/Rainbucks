-- Clean Database Setup Script for mywebsite
-- This script can be run multiple times safely

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS mywebsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE mywebsite;

-- Drop existing tables if they exist (for clean setup)
-- Uncomment the lines below if you want to start fresh
-- DROP TABLE IF EXISTS content;
-- DROP TABLE IF EXISTS users;

-- Create users table for admin authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create content table for website content management
CREATE TABLE IF NOT EXISTS content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123) - only if not exists
INSERT IGNORE INTO users (email, password, name, role) VALUES 
('admin@mywebsite.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample content - only if not exists
INSERT IGNORE INTO content (id, title, description, image) VALUES 
(1, 'Welcome to Our Website', 'This is a sample content entry. You can edit or delete this from the admin panel.', 'sample1.jpg'),
(2, 'About Our Services', 'Learn more about what we offer and how we can help you achieve your goals.', 'sample2.jpg'),
(3, 'Contact Information', 'Get in touch with us for any inquiries or support needs.', 'sample3.jpg');

-- Create indexes for better performance (only if they don't exist)
-- Note: MySQL 5.7+ supports IF NOT EXISTS for indexes
SET @sql = 'CREATE INDEX idx_users_email ON users(email)';
SET @sql = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = DATABASE() AND table_name = 'users' AND index_name = 'idx_users_email') > 0, 'SELECT "Index idx_users_email already exists"', @sql);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = 'CREATE INDEX idx_content_status ON content(status)';
SET @sql = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = DATABASE() AND table_name = 'content' AND index_name = 'idx_content_status') > 0, 'SELECT "Index idx_content_status already exists"', @sql);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = 'CREATE INDEX idx_content_created_at ON content(created_at)';
SET @sql = IF((SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = DATABASE() AND table_name = 'content' AND index_name = 'idx_content_created_at') > 0, 'SELECT "Index idx_content_created_at already exists"', @sql);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
