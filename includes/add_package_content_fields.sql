-- Add new content fields to packages table for dynamic sections
-- Run this SQL to add the new fields for overview and why choose sections

USE mywebsite;

-- Add new fields to packages table
ALTER TABLE packages 
ADD COLUMN overview_title VARCHAR(255) DEFAULT NULL AFTER features,
ADD COLUMN overview_content TEXT DEFAULT NULL AFTER overview_title,
ADD COLUMN what_you_learn TEXT DEFAULT NULL AFTER overview_content,
ADD COLUMN why_choose_title VARCHAR(255) DEFAULT NULL AFTER what_you_learn,
ADD COLUMN why_choose_content TEXT DEFAULT NULL AFTER why_choose_title,
ADD COLUMN why_choose_points TEXT DEFAULT NULL AFTER why_choose_content;

-- Update existing packages with default content
UPDATE packages SET 
    overview_title = 'Course Overview',
    overview_content = CONCAT('This ', REPLACE(name, ' Package', ''), ' Package is your gateway to mastering essential skills. Our comprehensive curriculum covers topics that professionals need to know.\n\nYou\'ll learn from industry experts who have years of real-world experience. Each course is designed to be practical and immediately applicable to your career journey.'),
    what_you_learn = 'Business fundamentals and strategy\nMarketing and customer acquisition\nFinancial planning and management\nLeadership and team building\nDigital marketing essentials\nProject management skills',
    why_choose_title = 'Why Choose This Package?',
    why_choose_content = CONCAT('Our ', REPLACE(name, ' Package', ''), ' Package stands out from the competition with its comprehensive approach to learning and practical application.\n\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.'),
    why_choose_points = 'Expert instructors with industry experience\nPractical, hands-on learning approach\nComprehensive curriculum designed for success\nLifetime access to all course materials\nCertificate of completion\n24/7 support and community access'
WHERE overview_title IS NULL;

-- Add students enrolled field to courses table
ALTER TABLE courses
ADD COLUMN students_enrolled INT DEFAULT 0 AFTER rating;

-- Update existing courses with sample enrollment numbers
UPDATE courses SET students_enrolled = FLOOR(RAND() * 5000) + 500 WHERE students_enrolled = 0;

-- Create the constant image for why choose section
-- Note: Admin should upload this image to assets/images/why-choose-constant.jpg
