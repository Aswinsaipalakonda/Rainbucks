-- Update database schema for dynamic course bundle management
-- Run this SQL to add the necessary tables and relationships

-- Create package_courses junction table for many-to-many relationship
CREATE TABLE IF NOT EXISTS package_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT NOT NULL,
    course_id INT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_package_course (package_id, course_id)
);

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_package_courses_package_id ON package_courses(package_id);
CREATE INDEX IF NOT EXISTS idx_package_courses_course_id ON package_courses(course_id);
CREATE INDEX IF NOT EXISTS idx_package_courses_sort_order ON package_courses(sort_order);

-- Update courses table to remove package_id (we'll use junction table instead)
-- Note: This is optional if you want to maintain backward compatibility
-- ALTER TABLE courses DROP FOREIGN KEY courses_ibfk_1;
-- ALTER TABLE courses DROP COLUMN package_id;

-- Insert existing relationships into junction table (if courses still have package_id)
INSERT IGNORE INTO package_courses (package_id, course_id, sort_order)
SELECT package_id, id, id as sort_order 
FROM courses 
WHERE package_id IS NOT NULL;

-- Sample data for testing (optional)
-- INSERT IGNORE INTO package_courses (package_id, course_id, sort_order) VALUES
-- (1, 1, 1), (1, 2, 2),  -- Starter Package courses
-- (2, 3, 1), (2, 4, 2),  -- Professional Package courses
-- (3, 5, 1), (3, 6, 2);  -- Advanced Package courses
