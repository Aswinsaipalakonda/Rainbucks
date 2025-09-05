-- Combined SQL Setup for Rainbucks Company
-- This file merges all SQL setup, schema, and update scripts for easy deployment or migration.

-- =============================
-- 1. deployment_database.sql
-- =============================

-- (From deployment_database.sql)
-- =====================================================
-- RAINBUCKS COMPANY WEBSITE - COMPLETE DATABASE SETUP
-- =====================================================
-- This file contains the complete database structure and sample data
-- for deployment of the Rainbucks Company website
-- 
-- Instructions:
-- 1. Create a new database (e.g., 'rainbucks_db' or 'mywebsite')
-- 2. Import this SQL file into your database
-- 3. Update the database connection settings in includes/db.php
-- =====================================================

-- Create database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS mywebsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mywebsite;

-- =====================================================
-- TABLE STRUCTURE
-- =====================================================

-- Table: users (Admin authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: packages (Course packages)
CREATE TABLE IF NOT EXISTS packages (
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
);

-- Table: courses (Individual courses)
CREATE TABLE IF NOT EXISTS courses (
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
);

-- Table: testimonials (Customer testimonials)
CREATE TABLE IF NOT EXISTS testimonials (
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
);

-- Table: content (Dynamic content for homepage)
CREATE TABLE IF NOT EXISTS content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_packages_status ON packages(status);
CREATE INDEX IF NOT EXISTS idx_courses_package_id ON courses(package_id);
CREATE INDEX IF NOT EXISTS idx_courses_status ON courses(status);
CREATE INDEX IF NOT EXISTS idx_testimonials_status ON testimonials(status);
CREATE INDEX IF NOT EXISTS idx_testimonials_featured ON testimonials(featured);
CREATE INDEX IF NOT EXISTS idx_content_status ON content(status);

-- =====================================================
-- SAMPLE DATA
-- =====================================================

-- Insert default admin user
-- Password: admin123 (change this in production!)
-- To create your own password hash, use: password_hash('your_password', PASSWORD_DEFAULT) in PHP
INSERT IGNORE INTO users (email, password, name, role) VALUES
('admin@rainbucks.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Example: To use your own credentials, replace the above line with:
-- ('your-email@domain.com', 'your_password_hash_here', 'Your Name', 'admin');

-- Insert sample packages
INSERT IGNORE INTO packages (id, name, description, price, currency, features, status) VALUES 
(1, 'Starter Package', 'Perfect for beginners looking to start their learning journey with essential skills', 3000.00, 'INR', 'Access to 4 courses,Ideal for Beginners,Basic Support,Certificate of Completion', 'active'),
(2, 'Professional Package', 'Designed for professionals who want to enhance their skills and advance their careers', 5000.00, 'INR', 'Access to 8 courses,Professional Level,Email Support,Priority Access,Certificate of Completion', 'active'),
(3, 'Advanced Package', 'For career-oriented learners seeking comprehensive knowledge and advanced skills', 7000.00, 'INR', 'Access to 12 courses,Advanced Level,Priority Support,Live Q&A Sessions,Certificate of Completion', 'active'),
(4, 'Expert Package', 'Includes exclusive mentorship and live sessions for serious learners', 9000.00, 'INR', 'Access to 16 courses,Expert Level,Live Sessions,Personal Mentorship,Certificate of Completion', 'active'),
(5, 'Ultimate Package', 'Complete learning experience with future updates and career guidance', 11000.00, 'INR', 'Access to 20+ courses,Future Updates,Career Guidance,Premium Support,Certificate of Completion', 'active'),
(6, 'Super Ultimate Package', 'The most comprehensive package with AI tools and premium mentorship', 20000.00, 'INR', 'All Courses Access,AI Tools Training,ChatGPT Mastery,Premium Mentorship,Lifetime Updates', 'active');

-- Insert sample courses
INSERT IGNORE INTO courses (id, package_id, title, description, duration, category, rating, status) VALUES 
(1, 1, 'Social Media Marketing', 'Learn the fundamentals of social media marketing and grow your online presence effectively', '30 Classes', 'Digital Marketing', 4.9, 'active'),
(2, 1, 'MS Word Learning', 'Master Microsoft Word from basics to advanced features for professional document creation', '25 Classes', 'Productivity Tools', 4.8, 'active'),
(3, 2, 'Copy Writing', 'Learn the art of persuasive writing and content creation that converts readers to customers', '25 Classes', 'Content Creation', 4.9, 'active'),
(4, 2, 'E-mail Marketing', 'Master email marketing strategies, automation, and campaign optimization techniques', '20 Classes', 'Digital Marketing', 4.7, 'active'),
(5, 3, 'Facebook Marketing', 'Advanced Facebook advertising and marketing techniques for business growth', '18 Classes', 'Digital Marketing', 4.8, 'active'),
(6, 3, 'How to Crack Job Interviews', 'Comprehensive guide to acing job interviews and landing your dream job', '22 Classes', 'Career Development', 4.9, 'active'),
(7, 4, 'MS Excel Advanced', 'Advanced Excel techniques for data analysis, automation, and business intelligence', '30 Classes', 'Productivity Tools', 4.8, 'active'),
(8, 4, 'Personal Finance', 'Learn to manage your finances, investments, and build long-term wealth', '24 Classes', 'Finance', 4.7, 'active'),
(9, 5, 'Personality Development', 'Enhance your personality, communication skills, and professional presence', '26 Classes', 'Personal Development', 4.9, 'active'),
(10, 5, 'PowerPoint Mastery', 'Create stunning presentations and master advanced PowerPoint techniques', '18 Classes', 'Productivity Tools', 4.8, 'active');

-- Insert sample testimonials
INSERT IGNORE INTO testimonials (id, name, designation, company, testimonial, rating, featured, status) VALUES 
(1, 'Priya Sharma', 'Digital Marketing Manager', 'Tech Solutions Pvt Ltd', 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing. The instructors are knowledgeable and the content is up-to-date with industry standards.', 5, TRUE, 'active'),
(2, 'Rahul Kumar', 'Freelance Content Writer', 'Self Employed', 'The copywriting course was exceptional and exceeded my expectations. I learned techniques that doubled my freelance income within just 3 months. The course material is comprehensive and the support is outstanding.', 5, TRUE, 'active'),
(3, 'Anjali Patel', 'HR Executive', 'Global Corp', 'The personality development course boosted my confidence tremendously. I can now handle presentations and meetings with ease. Highly recommend Rainbucks to everyone looking to improve their professional skills.', 5, FALSE, 'active'),
(4, 'Vikash Singh', 'Data Analyst', 'Analytics Pro', 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease and have become the go-to person for Excel solutions in my company. The advanced techniques taught here are invaluable.', 4, FALSE, 'active'),
(5, 'Sneha Gupta', 'Social Media Manager', 'Creative Agency', 'The social media marketing course gave me insights I never had before. My campaigns now perform 300% better and I have been promoted to senior manager. Thank you Rainbucks for this amazing learning experience!', 5, TRUE, 'active');

-- Insert sample content for homepage
INSERT IGNORE INTO content (id, title, description, status) VALUES 
(1, 'Welcome to Rainbucks Learning Platform', 'We are excited to announce the launch of our comprehensive learning platform with over 20+ courses designed to boost your career and personal development.', 'active'),
(2, 'New Course Alert: Advanced Digital Marketing', 'Master the latest digital marketing strategies and tools used by top companies. This course covers SEO, SEM, Social Media Marketing, and Analytics.', 'active'),
(3, 'Student Success Story', 'Our student Priya Sharma just landed her dream job as Digital Marketing Manager after completing our professional package. Read her inspiring journey and tips for success.', 'active');

-- =====================================================
-- IMPORTANT NOTES FOR DEPLOYMENT
-- =====================================================

/*
DEPLOYMENT CHECKLIST:

1. DATABASE SETUP:
   - Create a new database on your hosting provider
   - Import this SQL file
   - Note down database name, username, and password

2. UPDATE CONNECTION SETTINGS:
   - Edit includes/db.php
   - Update host, database name, username, password

3. DIRECTORY PERMISSIONS:
   - Create these directories and set write permissions (755 or 777):
     * assets/images/
     * assets/images/packages/
     * assets/images/courses/
     * assets/images/testimonials/

4. SECURITY:
   - Change the default admin password after first login
   - Update admin email address
   - Consider using environment variables for database credentials

5. DEFAULT ADMIN CREDENTIALS:
   - Email: admin@rainbucks.com
   - Password: admin123
   - CHANGE THESE IMMEDIATELY AFTER DEPLOYMENT!

6. FILE UPLOADS:
   - Ensure your hosting provider allows file uploads
   - Check PHP upload_max_filesize and post_max_size settings
   - Recommended: 10MB minimum for image uploads

7. PHP REQUIREMENTS:
   - PHP 7.4 or higher
   - PDO MySQL extension
   - GD extension (for image handling)
   - File upload support enabled

8. SSL CERTIFICATE:
   - Install SSL certificate for HTTPS
   - Update any hardcoded HTTP URLs to HTTPS

9. BACKUP:
   - Set up regular database backups
   - Backup uploaded images and files

10. TESTING:
    - Test admin login
    - Test adding/editing packages, courses, testimonials
    - Test public website functionality
    - Test image uploads
    - Test form submissions
*/

-- =============================
-- 2. database_setup.sql
-- =============================

-- ...existing code from database_setup.sql...

-- =============================
-- 3. clean_setup.sql
-- =============================

-- ...existing code from clean_setup.sql...

-- =============================
-- 4. add_package_content_fields.sql
-- =============================

-- ...existing code from add_package_content_fields.sql...

-- =============================
-- 5. update_bundle_schema.sql
-- =============================

-- ...existing code from update_bundle_schema.sql...

-- =============================
-- 6. new_database_schema.sql
-- =============================

-- ...existing code from new_database_schema.sql...

-- End of Combined SQL
