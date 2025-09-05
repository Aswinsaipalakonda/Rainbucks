-- Enhanced Database Schema for Rainbucks CMS
-- Run this to add new tables for packages, courses, and testimonials

USE mywebsite;

-- Create packages table
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'INR',
    features TEXT, -- JSON or comma-separated features
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    duration VARCHAR(100), -- e.g., "30 Classes", "2 Hours"
    rating DECIMAL(2,1) DEFAULT 4.9,
    category VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
);

-- Create testimonials table
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

-- Insert sample packages
INSERT IGNORE INTO packages (id, name, description, price, features, image) VALUES 
(1, 'Starter Package', 'Perfect for beginners looking to start their learning journey', 3000.00, 'Access to 4 courses,Ideal for Beginners,Basic Support', 'starter-package.jpg'),
(2, 'Professional Package', 'Designed for professionals who want to enhance their skills', 5000.00, 'Access to 8 courses,Professional Level,Email Support', 'professional-package.jpg'),
(3, 'Advanced Package', 'For career-oriented learners seeking comprehensive knowledge', 7000.00, 'Access to 12 courses,Advanced Level,Priority Support', 'advanced-package.jpg'),
(4, 'Expert Package', 'Includes exclusive mentorship and live sessions', 9000.00, 'Access to 16 courses,Expert Level,Live Sessions,Mentorship', 'expert-package.jpg'),
(5, 'Ultimate Package', 'Complete learning experience with future updates', 11000.00, 'Access to 20+ courses,Future Updates,Career Guidance,Premium Support', 'ultimate-package.jpg'),
(6, 'Super Ultimate Package', 'The most comprehensive package with AI tools', 20000.00, 'All Courses,AI Tools Access,ChatGPT Training,Premium Mentorship', 'super-ultimate-package.jpg');

-- Insert sample courses
INSERT IGNORE INTO courses (id, package_id, title, description, image, duration, category) VALUES 
(1, 1, 'Social Media Marketing', 'Learn the fundamentals of social media marketing and grow your online presence', 'social-media.jpg', '30 Classes', 'Digital Marketing'),
(2, 1, 'MS Word Learning', 'Master Microsoft Word from basics to advanced features', 'ms-word.jpg', '25 Classes', 'Productivity Tools'),
(3, 2, 'Copy Writing', 'Learn the art of persuasive writing and content creation', 'copywriting.jpg', '25 Classes', 'Content Creation'),
(4, 2, 'E-mail Marketing', 'Master email marketing strategies and automation', 'email-marketing.jpg', '20 Classes', 'Digital Marketing'),
(5, 3, 'Facebook Marketing', 'Advanced Facebook advertising and marketing techniques', 'facebook-marketing.jpg', '18 Classes', 'Digital Marketing'),
(6, 3, 'How to Crack Job Interviews', 'Comprehensive guide to acing job interviews', 'job-interviews.jpg', '22 Classes', 'Career Development'),
(7, 4, 'MS Excel Advanced', 'Advanced Excel techniques for data analysis and automation', 'ms-excel.jpg', '30 Classes', 'Productivity Tools'),
(8, 4, 'Personal Finance', 'Learn to manage your finances and build wealth', 'personal-finance.jpg', '24 Classes', 'Finance'),
(9, 5, 'Personality Development', 'Enhance your personality and communication skills', 'personality-development.jpg', '26 Classes', 'Personal Development'),
(10, 5, 'PowerPoint Mastery', 'Create stunning presentations with PowerPoint', 'powerpoint.jpg', '18 Classes', 'Productivity Tools');

-- Insert sample testimonials
INSERT IGNORE INTO testimonials (id, name, designation, company, testimonial, image, rating, featured) VALUES 
(1, 'Priya Sharma', 'Digital Marketing Manager', 'Tech Solutions Pvt Ltd', 'Rainbucks courses transformed my career. The practical approach and expert guidance helped me land my dream job in digital marketing.', 'testimonial-1.jpg', 5, TRUE),
(2, 'Rahul Kumar', 'Freelance Content Writer', 'Self Employed', 'The copywriting course was exceptional. I learned techniques that doubled my freelance income within 3 months.', 'testimonial-2.jpg', 5, TRUE),
(3, 'Anjali Patel', 'HR Executive', 'Global Corp', 'The personality development course boosted my confidence tremendously. Highly recommend Rainbucks to everyone.', 'testimonial-3.jpg', 5, FALSE),
(4, 'Vikash Singh', 'Data Analyst', 'Analytics Pro', 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease.', 'testimonial-4.jpg', 4, FALSE);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_packages_status ON packages(status);
CREATE INDEX IF NOT EXISTS idx_courses_package_id ON courses(package_id);
CREATE INDEX IF NOT EXISTS idx_courses_status ON courses(status);
CREATE INDEX IF NOT EXISTS idx_testimonials_status ON testimonials(status);
CREATE INDEX IF NOT EXISTS idx_testimonials_featured ON testimonials(featured);
