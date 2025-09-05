-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 03, 2025 at 04:14 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u545827212_rainbucks`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to Rainbucks Learning Platform', 'We are excited to announce the launch of our comprehensive learning platform with over 20+ courses designed to boost your career and personal development.', NULL, 'active', '2025-08-30 08:22:09', '2025-08-30 08:22:09'),
(2, 'New Course Alert: Advanced Digital Marketing', 'Master the latest digital marketing strategies and tools used by top companies. This course covers SEO, SEM, Social Media Marketing, and Analytics.', NULL, 'active', '2025-08-30 08:22:09', '2025-08-30 08:22:09'),
(3, 'Student Success Story', 'Our student Priya Sharma just landed her dream job as Digital Marketing Manager after completing our professional package. Read her inspiring journey and tips for success.', NULL, 'active', '2025-08-30 08:22:09', '2025-08-30 08:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `package_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 4.9,
  `category` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `package_id`, `title`, `description`, `image`, `duration`, `rating`, `category`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Social Media Marketing', 'A course that covers strategies to build brand awareness, engage audiences, and drive sales through platforms like Facebook, Instagram, Twitter.', 'course_68b4e16b9891c.jpg', '30 Classes', 4.9, 'Digital Marketing', 'active', 0, '2025-08-30 08:22:09', '2025-09-03 06:11:23'),
(2, 1, 'MS Word Learning', 'A course that teaches the essentials of Microsoft Word, including document creation, formatting, editing, and advanced features for professional use.', 'course_68b4e1cf14880.jpg', '25 Classes', 4.8, 'Productivity Tools', 'active', 0, '2025-08-30 08:22:09', '2025-08-31 23:59:11'),
(3, 6, 'Copy Writing', 'A course that trains you to craft persuasive and engaging written content that drives sales, builds brand identity, and captures audience attention.', 'course_68b4e1f3aaf8d.jpg', '25 Classes', 4.9, 'Content Creation', 'active', 0, '2025-08-30 08:22:09', '2025-08-31 23:59:47'),
(4, 6, 'E-mail Marketing', 'A course that teaches how to design, write, and automate effective email campaigns to build relationships, nurture leads, and boost conversions.', 'course_68b4e255add6b.jpg', '20 Classes', 4.7, 'Digital Marketing', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:01:25'),
(5, 6, 'Facebook Marketing', 'A course that focuses on using Facebook’s tools and advertising features to grow brand visibility, engage audiences, and generate leads or sales.', 'course_68b4e27a5844f.jpg', '18 Classes', 4.8, 'Digital Marketing', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:02:07'),
(6, 6, 'How to Crack Job Interviews', 'A course that prepares you with essential interview skills, including answering common questions, presenting confidently, and making a lasting impression on employers.', 'course_68b4e29f16483.jpg', '22 Classes', 4.9, 'Career Development', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:02:39'),
(7, 6, 'MS Excel Advanced', 'A course that covers advanced Excel features such as formulas, pivot tables, data analysis, macros, and automation to enhance productivity and decision-making.', 'course_68b4e2be9fd29.jpg', '30 Classes', 4.8, 'Productivity Tools', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:03:10'),
(8, 6, 'Personal Finance', 'A course that teaches how to manage money effectively, covering budgeting, saving, investing, debt management, and financial planning for long-term security.', 'course_68b4e2f076ecf.jpg', '24 Classes', 4.7, 'Finance', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:04:00'),
(9, 6, 'Personality Development', 'A course that focuses on improving self-confidence, communication, body language, and overall personal growth to build a strong and positive personality.', 'course_68b4e30e6988c.jpg', '26 Classes', 4.9, 'Personal Development', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:04:30'),
(10, 6, 'PowerPoint Mastery', 'A course that teaches how to design impactful presentations using PowerPoint, covering slide design, animations, visuals, and storytelling techniques.', 'course_68b4e3790ca49.jpg', '18 Classes', 4.8, 'Productivity Tools', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 00:06:17'),
(11, 6, 'Business communication', 'A course that enhances professional communication skills, focusing on effective writing, speaking, presentation, and interpersonal communication in business settings.', 'course_68b4e4118dc4a.jpg', '30 Classes', 4.9, 'Business', 'active', 0, '2025-08-31 09:11:48', '2025-09-01 00:08:49'),
(12, 6, 'Freelancing Mastery', 'A course that guides you in building a profitable freelance career through client acquisition, project management, pricing, and growth strategies.', 'course_68b4e459e295d.jpg', '28 Classes', 4.9, 'Career Development', 'active', 0, '2025-08-31 09:13:42', '2025-09-01 00:10:01'),
(13, 6, 'Sales Funnel', 'A course that teaches how to design and optimize sales funnels to attract leads, nurture prospects, and convert them into loyal customers.', 'course_68b4e47da03cd.jpg', '30 Classes', 4.9, 'Marketing & Sales', 'active', 0, '2025-08-31 09:14:43', '2025-09-01 00:10:37'),
(14, 6, 'Instagram Marketing', 'A course focused on leveraging Instagram to grow brand presence, engage audiences, and drive sales through effective content strategies and advertising.', 'course_68b4e4a879f20.jpg', '30 Classes', 4.9, 'Digital Marketing', 'active', 0, '2025-08-31 09:15:35', '2025-09-01 00:11:20'),
(15, 6, 'Affiliate Marketing', 'A course that covers strategies to earn income by promoting products or services, building partnerships, and driving sales through affiliate networks.', 'course_68b4e5131be06.jpg', '30 Classes', 4.9, 'Digital Marketing', 'active', 0, '2025-08-31 09:16:29', '2025-09-01 00:13:07'),
(16, 6, 'LinkedIn Marketing', 'A course that teaches how to build a strong professional presence, generate leads, and grow business opportunities using LinkedIn’s networking and advertising tools.', 'course_68b4e5685ef02.jpg', '28 Classes', 4.9, 'Digital Marketing', 'active', 0, '2025-08-31 09:17:33', '2025-09-01 00:14:32'),
(17, 6, 'Spoken English', 'A course designed to improve fluency, pronunciation, and confidence in everyday and professional English communication.', 'course_68b4e5938787b.jpg', '26 Classes', 4.9, 'Communication', 'active', 0, '2025-08-31 09:18:41', '2025-09-01 00:15:15'),
(18, 6, 'Public Speaking Skills', 'A course that helps you develop confidence, clarity, and effectiveness in delivering speeches and presentations to any audience.', 'course_68b4e5e4f39ba.jpg', '24 Classes', 4.9, 'Communication', 'active', 0, '2025-08-31 09:19:57', '2025-09-01 00:16:36'),
(19, 6, 'Principles of Video Editing', 'A course that introduces the fundamentals of video editing, including cutting, transitions, sound design, and storytelling to create engaging visual content.', 'course_68b4e3a3e04f3.jpg', '28 Classes', 4.9, 'Media & Creative Arts', 'active', 0, '2025-08-31 09:21:25', '2025-09-01 00:06:59'),
(20, 6, 'Content Creation Mastery', 'A course that guides you in producing engaging content across blogs, videos, and social media, focusing on strategy, creativity, and audience growth.', 'course_68b4e3cd983a5.jpg', '24 Classes', 4.9, 'Digital Marketing', 'active', 0, '2025-08-31 09:22:20', '2025-09-01 00:07:41'),
(21, 6, 'ChatGPT', 'A course that teaches how to effectively use ChatGPT for content creation, problem-solving, automation, and enhancing productivity across various fields.', 'course_68b4e60a76c09.png', '30 Classes', 4.9, 'Productivity Tools', 'active', 0, '2025-08-31 09:23:05', '2025-09-01 00:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) DEFAULT 'INR',
  `features` text DEFAULT NULL,
  `overview_title` varchar(255) DEFAULT NULL,
  `overview_content` text DEFAULT NULL,
  `what_you_learn` text DEFAULT NULL,
  `why_choose_title` varchar(255) DEFAULT NULL,
  `why_choose_content` text DEFAULT NULL,
  `why_choose_points` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `description`, `price`, `currency`, `features`, `overview_title`, `overview_content`, `what_you_learn`, `why_choose_title`, `why_choose_content`, `why_choose_points`, `image`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Starter Package', 'Our Starter Package is designed to help you build a strong foundation in essential professional tools and skills. Whether you\'re aiming to enhance your proficiency in MS-Office applications, prepare for job interviews, or advance in your business career, this package is your stepping stone to success. With a variety of practical and industry-relevant courses, the Starter Package is crafted to strengthen your capabilities and boost your confidence in today\'s competitive landscape.', 3000.00, 'INR', 'Huge Commission On Every Referral\r\nValuable Bonuses\r\nAccess To All Live Training\r\nAll Starter Courses Free As A Bonus', 'Course Overview', 'The Starter Course Bundle is a thoughtfully curated package designed to provide you with the must-have skills for career success. Whether you\'re a student looking to enhance your resume or a professional aiming to refine your expertise, this bundle offers comprehensive learning solutions.\r\n\r\nOur courses provide hands-on practice, practical insights, and expert-led training to help you tackle real-world challenges. The Starter Course Bundle is an excellent investment for your personal and professional growth with Rainbucks.', 'This package helps you master core professional skills and stay ahead in the business landscape. You\'ll learn lessons in cracking job interviews, financial management, and impactful content creation.', 'Why Choose This Package?', 'Our Starter Package stands out from the competition with its comprehensive approach to learning and practical application.\r\n\r\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.', 'Social Media Marketing: \"Social Success Secrets: Dominate Digital Platforms\"\r\nMS Word: \"Word Wizard: Unlock the Power of MS Word\"\r\nContent Creation: \"Creative Content Mastery: From Ideas to Impact\"\r\nCopywriting: \"Words That Sell: The Ultimate Copywriting Course\"', 'package_68b42b7ac247b.png', 'active', 0, '2025-08-30 08:22:09', '2025-08-31 11:01:14'),
(2, 'Professional Package', 'The Professional Package is tailored to equip you with essential skills to excel in today’s competitive business environment. Whether you aim to master social media marketing, boost your professional communication, or improve financial literacy, this package has you covered. Designed for aspiring professionals and entrepreneurs, it provides practical insights to help you build confidence, sharpen your expertise, and achieve your career goals. With an emphasis on adaptability and growth, this package sets the stage for success in any professional arena.', 5000.00, 'INR', 'How to Crack Job Interviews: \"Interview Edge: Ace Every Opportunity\"\r\nPersonal Finance: \"Financial Freedom Formula: Take Control of Your Money\"\r\nTop Tricks of MS Excel: \"Excel Genius: Advanced Tricks for Data Mastery\"\r\nPowerPoint: \"Presentation Power: Mastering the Art of PowerPoint\"', 'Course Overview', 'Step into the world of professional success with our Professional Package. Ideal for beginners and experienced professionals alike, this package focuses on developing impactful skills to help you stand out in the competitive job market. Whether you\'re looking to improve your digital presence or enhance your financial acumen, these courses deliver practical strategies and expert knowledge. Learn from industry leaders and gain insights to fuel your growth and drive exceptional results.', 'Master core professional skills and stay ahead in the evolving business landscape. This package offers valuable lessons in cracking job interviews, financial management, and impactful content creation.', 'Why Choose This Package?', 'Our Professional Package stands out from the competition with its comprehensive approach to learning and practical application.\r\n\r\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.', 'Expert instructors with industry experience\r\nPractical, hands-on learning approach\r\nComprehensive curriculum designed for success\r\nLifetime access to all course materials\r\nCertificate of completion\r\n24/7 support and community access', 'package_68b5cddd10813.jpg', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 16:46:21'),
(3, 'Advanced Package', 'Staying ahead in today’s fast-paced industry requires continuous learning and skill enhancement. The Advanced Package is designed to help learners master essential soft skills and cutting-edge strategies for professional and personal development. This comprehensive course empowers you to transform your expertise, boost productivity, and achieve your goals in record time.', 7000.00, 'INR', 'Access to 12 courses,Advanced Level,Priority Support,Live Q&A Sessions,Certificate of Completion', 'Course Overview', 'Success doesn’t happen overnight—it requires dedication and the right tools. With increasing competition, upgrading your skills is crucial for standing out and excelling in your field. Rainbucks brings you an in-depth training program that equips you with practical skills in areas like personality development, communication, spoken English, interview techniques, leadership, time management, and networking strategies. Take charge of your career today and become the leader you aspire to be!', 'Business fundamentals and strategy\r\nMarketing and customer acquisition\r\nFinancial planning and management\r\nLeadership and team building\r\nDigital marketing essentials\r\nProject management skills', 'Why Choose This Package?', 'Our Advanced Package stands out from the competition with its comprehensive approach to learning and practical application.\r\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.', 'Expert instructors with industry experience\r\nPractical, hands-on learning approach\r\nComprehensive curriculum designed for success\r\nLifetime access to all course materials\r\nCertificate of completion\r\n24/7 support and community access', 'package_68b5ce19b779e.jpg', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 16:47:21'),
(4, 'Expert Package', 'The Expert Package is tailored for students and entrepreneurs eager to become digital marketing professionals. This course will empower you to master your field and provide you with valuable insights into the professional world. Whether you\'re passionate about staying updated with the latest techniques or striving to excel in your career, this course is the perfect opportunity to enhance your skills. Get ready to dive into trending tools and cutting-edge technologies that will elevate your professional journey.', 9000.00, 'INR', 'Access to 16 courses,Expert Level,Live Sessions,Personal Mentorship,Certificate of Completion', 'Course Overview', 'In today’s dynamic digital landscape, staying updated with the latest marketing trends is essential for success. Rainbucks offers you an immersive learning experience, ensuring you understand the most vital digital marketing concepts with enthusiasm and depth. If you\'re looking to gain comprehensive, hands-on knowledge of digital marketing, this course is designed just for you.', 'Business fundamentals and strategy\r\nMarketing and customer acquisition\r\nFinancial planning and management\r\nLeadership and team building\r\nDigital marketing essentials\r\nProject management skills', 'Why Choose This Package?', 'Our Expert Package stands out from the competition with its comprehensive approach to learning and practical application.\r\n\r\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.', 'Expert instructors with industry experience\r\nPractical, hands-on learning approach\r\nComprehensive curriculum designed for success\r\nLifetime access to all course materials\r\nCertificate of completion\r\n24/7 support and community access', 'package_68b5ce96d2286.jpg', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 16:49:26'),
(5, 'Ultimate Package', 'The Ultimate Package is designed specifically for students and aspiring professionals aiming to thrive in the world of finance. This course provides a deep dive into a wide range of financial topics, giving you valuable insights into the industry. If you\'re committed to mastering the latest financial techniques, strategies, and tools, this course is the perfect opportunity to level up your career. Don’t wait any longer—take the plunge into trending financial tools and cutting-edge technologies to accelerate your career in finance.', 11000.00, 'INR', 'Access to 19 courses, Future Updates, Career Guidance, Premium Support, Certificate of Completion', 'Course Overview', 'In today’s dynamic digital landscape, staying updated with the latest marketing trends is essential for success. Rainbucks offers you an immersive learning experience, ensuring you understand the most vital digital marketing concepts with enthusiasm and depth. If you\'re looking to gain comprehensive, hands-on knowledge of digital marketing, this course is designed just for you.', 'Business fundamentals and strategy\r\nMarketing and customer acquisition\r\nFinancial planning and management\r\nLeadership and team building\r\nDigital marketing essentials\r\nProject management skills', 'Why Choose This Package?', 'Our Ultimate Package stands out from the competition with its comprehensive approach to learning and practical application.\r\n\r\nWe focus on real-world skills that you can immediately apply to your career or business, ensuring maximum return on your investment.', 'Expert instructors with industry experience\r\nPractical, hands-on learning approach\r\nComprehensive curriculum designed for success\r\nLifetime access to all course materials\r\nCertificate of completion\r\n24/7 support and community access', 'package_68b5ceeadec5c.jpg', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 16:50:50'),
(6, 'Super Ultimate Package', 'This course provides a practical introduction to ChatGPT, from signing up to mastering its advanced features. Topics covered include conversing with ChatGPT, customizing it, using it for productivity and building chatbots, as well as advanced applications like language translation and generating creative content.', 20000.00, 'INR', 'All Courses Access,AI Tools Training,ChatGPT Mastery,Premium Mentorship,Lifetime Updates', 'Course Overview', 'ChatGPT is an extremely powerful tool, but only if you understand how to use it properly. This course includes an AI Assistant that offers real-time support, feedback, and scoring to help you improve your prompting skills and use of ChatGPT. You\'ll learn how to create precise prompts, improve AI-generated responses, and avoid common errors that result in ambiguous or misleading answers.', 'Business fundamentals and strategy\r\nMarketing and customer acquisition\r\nFinancial planning and management\r\nLeadership and team building\r\nDigital marketing essentials\r\nProject management skills', 'Why Choose This Package?', 'ChatGPT may sound confident, but it’s not always right! You’ll discover the limitations of AI, including privacy risks, misinformation, and hallucinated facts. You’ll learn to fact-check AI-generated content and use ChatGPT responsibly - especially when dealing with sensitive or high-stakes topics. With these skills, you can make AI work for you while knowing its strengths and weaknesses. By the end of this course, you’ll think like an AI pro, prompt smarter, and get better, more reliable results.', 'Expert instructors with industry experience\r\nPractical, hands-on learning approach\r\nComprehensive curriculum designed for success\r\nLifetime access to all course materials\r\nCertificate of completion\r\n24/7 support and community access', 'package_68b5ce4d916ce.jpg', 'active', 0, '2025-08-30 08:22:09', '2025-09-01 16:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `package_courses`
--

CREATE TABLE `package_courses` (
  `id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package_courses`
--

INSERT INTO `package_courses` (`id`, `package_id`, `course_id`, `sort_order`, `created_at`) VALUES
(1, 1, 1, 2, '2025-08-31 11:09:49'),
(2, 1, 2, 3, '2025-08-31 11:09:49'),
(3, 6, 3, 3, '2025-08-31 11:09:49'),
(4, 6, 4, 4, '2025-08-31 11:09:49'),
(5, 6, 5, 5, '2025-08-31 11:09:49'),
(6, 6, 6, 6, '2025-08-31 11:09:49'),
(7, 6, 7, 7, '2025-08-31 11:09:49'),
(8, 6, 8, 8, '2025-08-31 11:09:49'),
(9, 6, 9, 9, '2025-08-31 11:09:49'),
(10, 6, 10, 10, '2025-08-31 11:09:49'),
(11, 6, 11, 11, '2025-08-31 11:09:49'),
(12, 6, 12, 12, '2025-08-31 11:09:49'),
(13, 6, 13, 13, '2025-08-31 11:09:49'),
(14, 6, 14, 14, '2025-08-31 11:09:49'),
(15, 6, 15, 15, '2025-08-31 11:09:49'),
(16, 6, 16, 16, '2025-08-31 11:09:49'),
(17, 6, 17, 17, '2025-08-31 11:09:49'),
(18, 6, 18, 18, '2025-08-31 11:09:49'),
(19, 6, 19, 19, '2025-08-31 11:09:49'),
(20, 6, 20, 20, '2025-08-31 11:09:49'),
(21, 6, 21, 21, '2025-08-31 11:09:49'),
(35, 2, 3, 1, '2025-08-31 11:10:01'),
(37, 3, 5, 1, '2025-08-31 11:10:01'),
(38, 3, 6, 2, '2025-08-31 11:10:01'),
(39, 4, 7, 1, '2025-08-31 11:10:01'),
(40, 4, 8, 2, '2025-08-31 11:10:01'),
(41, 5, 9, 1, '2025-08-31 11:10:01'),
(42, 5, 10, 2, '2025-08-31 11:10:01'),
(43, 1, 3, 4, '2025-08-31 11:11:40'),
(55, 1, 20, 5, '2025-08-31 11:17:29'),
(57, 2, 6, 3, '2025-08-31 23:43:06'),
(58, 2, 8, 4, '2025-08-31 23:43:19'),
(59, 2, 7, 5, '2025-08-31 23:43:29'),
(60, 2, 10, 6, '2025-08-31 23:43:37'),
(61, 2, 1, 7, '2025-08-31 23:43:57'),
(62, 2, 2, 8, '2025-08-31 23:44:09'),
(63, 2, 20, 9, '2025-08-31 23:44:14'),
(64, 3, 4, 3, '2025-08-31 23:46:09'),
(65, 3, 19, 4, '2025-08-31 23:46:22'),
(66, 3, 9, 5, '2025-08-31 23:46:43'),
(67, 3, 8, 6, '2025-08-31 23:46:59'),
(68, 3, 7, 7, '2025-08-31 23:47:13'),
(69, 3, 10, 8, '2025-08-31 23:47:19'),
(70, 3, 3, 9, '2025-08-31 23:47:31'),
(71, 3, 20, 10, '2025-08-31 23:47:46'),
(72, 3, 1, 11, '2025-08-31 23:48:10'),
(73, 3, 2, 12, '2025-08-31 23:48:15'),
(74, 4, 15, 3, '2025-08-31 23:49:17'),
(75, 4, 16, 4, '2025-08-31 23:49:30'),
(76, 4, 17, 5, '2025-08-31 23:49:44'),
(77, 4, 18, 6, '2025-08-31 23:50:04'),
(78, 4, 4, 7, '2025-08-31 23:50:15'),
(79, 4, 19, 8, '2025-08-31 23:50:27'),
(80, 4, 5, 9, '2025-08-31 23:50:34'),
(81, 4, 9, 10, '2025-08-31 23:50:40'),
(82, 4, 6, 11, '2025-08-31 23:50:47'),
(83, 4, 10, 12, '2025-08-31 23:51:13'),
(84, 4, 3, 13, '2025-08-31 23:51:20'),
(85, 4, 1, 14, '2025-08-31 23:51:26'),
(86, 4, 2, 15, '2025-08-31 23:51:34'),
(87, 4, 20, 16, '2025-08-31 23:51:40'),
(88, 5, 11, 3, '2025-08-31 23:53:09'),
(89, 5, 13, 4, '2025-08-31 23:53:15'),
(90, 5, 14, 5, '2025-08-31 23:53:31'),
(91, 5, 15, 6, '2025-08-31 23:53:32'),
(92, 5, 16, 7, '2025-08-31 23:53:40'),
(93, 5, 17, 8, '2025-08-31 23:53:49'),
(94, 5, 18, 9, '2025-08-31 23:53:53'),
(95, 5, 4, 10, '2025-08-31 23:53:57'),
(96, 5, 19, 11, '2025-08-31 23:54:11'),
(97, 5, 5, 12, '2025-08-31 23:54:21'),
(98, 5, 6, 13, '2025-08-31 23:54:33'),
(99, 5, 8, 14, '2025-08-31 23:54:41'),
(100, 5, 7, 15, '2025-08-31 23:54:47'),
(101, 5, 3, 16, '2025-08-31 23:54:54'),
(102, 5, 1, 17, '2025-08-31 23:55:03'),
(103, 5, 2, 18, '2025-08-31 23:55:09'),
(104, 5, 20, 19, '2025-08-31 23:55:17'),
(105, 6, 1, 22, '2025-08-31 23:56:29'),
(106, 6, 2, 23, '2025-08-31 23:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `testimonial` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `status` enum('active','inactive') DEFAULT 'active',
  `featured` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `company`, `testimonial`, `image`, `rating`, `status`, `featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Priya Sharma', 'Digital Marketing Manager', 'Tech Solutions Pvt Ltd', 'Rainbucks courses transformed my career completely. The practical approach and expert guidance helped me land my dream job in digital marketing. The instructors are knowledgeable and the content is up-to-date with industry standards.', 'testimonial_68b4e63a41ad2.jpg', 5, 'active', 1, 0, '2025-08-30 08:22:09', '2025-09-03 06:13:08'),
(2, 'Rahul Kumar', 'Freelance Content Writer', 'Self Employed', 'The copywriting course was exceptional and exceeded my expectations. I learned techniques that doubled my freelance income within just 3 months. The course material is comprehensive and the support is outstanding.', 'testimonial_68b4e66e23f44.jpg', 5, 'active', 1, 0, '2025-08-30 08:22:09', '2025-09-01 00:18:54'),
(3, 'Anjali Patel', 'HR Executive', 'Global Corp', 'The personality development course boosted my confidence tremendously. I can now handle presentations and meetings with ease. Highly recommend Rainbucks to everyone looking to improve their professional skills.', NULL, 5, 'active', 0, 0, '2025-08-30 08:22:09', '2025-08-30 08:22:09'),
(4, 'Vikash Singh', 'Data Analyst', 'Analytics Pro', 'Excel course was comprehensive and practical. Now I can handle complex data analysis with ease and have become the go-to person for Excel solutions in my company. The advanced techniques taught here are invaluable.', NULL, 4, 'active', 0, 0, '2025-08-30 08:22:09', '2025-08-30 08:22:09'),
(5, 'Gupta', 'Social Media Manager', 'Creative Agency', 'The social media marketing course gave me insights I never had before. My campaigns now perform 300% better and I have been promoted to senior manager. Thank you Rainbucks for this amazing learning experience!', 'testimonial_68b4e68565504.jpg', 5, 'active', 1, 0, '2025-08-30 08:22:09', '2025-09-01 00:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`, `created_at`, `updated_at`) VALUES
(2, 'admin@rainbucks.org', '$2y$10$bYad4aWz.AO2HXIGNaOdlOGPkhleEfiBv8nk12o6shWD4QoXUTmPm', 'Rainbucks Administrator', 'admin', '2025-08-30 08:23:48', '2025-08-30 08:23:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_content_status` (`status`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courses_package_id` (`package_id`),
  ADD KEY `idx_courses_status` (`status`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_packages_status` (`status`);

--
-- Indexes for table `package_courses`
--
ALTER TABLE `package_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_package_course` (`package_id`,`course_id`),
  ADD KEY `idx_package_courses_package_id` (`package_id`),
  ADD KEY `idx_package_courses_course_id` (`course_id`),
  ADD KEY `idx_package_courses_sort_order` (`sort_order`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_testimonials_status` (`status`),
  ADD KEY `idx_testimonials_featured` (`featured`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `package_courses`
--
ALTER TABLE `package_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `package_courses`
--
ALTER TABLE `package_courses`
  ADD CONSTRAINT `package_courses_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
