-- EDU Career India Database Initialization Script
-- This script creates the necessary tables for the website

USE educareer_db;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMP NULL,
  login_attempts INT DEFAULT 0,
  locked_until TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Page content table
CREATE TABLE IF NOT EXISTS page_content (
  id INT PRIMARY KEY AUTO_INCREMENT,
  page_name VARCHAR(50) NOT NULL,
  section_name VARCHAR(100) NOT NULL,
  content_type ENUM('text', 'html', 'image', 'url') NOT NULL,
  content_key VARCHAR(100) NOT NULL,
  content_value TEXT,
  display_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEO meta tags table
CREATE TABLE IF NOT EXISTS seo_meta (
  id INT PRIMARY KEY AUTO_INCREMENT,
  page_name VARCHAR(50) NOT NULL UNIQUE,
  meta_title VARCHAR(100),
  meta_description VARCHAR(200),
  focus_keywords TEXT,
  canonical_url VARCHAR(255),
  og_image VARCHAR(255),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
  id INT PRIMARY KEY AUTO_INCREMENT,
  student_name VARCHAR(100) NOT NULL,
  course VARCHAR(100),
  college VARCHAR(200),
  batch_year VARCHAR(20),
  testimonial_text TEXT NOT NULL,
  rating INT DEFAULT 5,
  display_order INT DEFAULT 0,
  is_featured BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Statistics table
CREATE TABLE IF NOT EXISTS site_statistics (
  id INT PRIMARY KEY AUTO_INCREMENT,
  stat_key VARCHAR(50) NOT NULL UNIQUE,
  stat_value INT NOT NULL,
  stat_label VARCHAR(100),
  stat_icon VARCHAR(100),
  display_order INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact submissions table
CREATE TABLE IF NOT EXISTS contact_submissions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  course VARCHAR(100),
  city VARCHAR(100),
  message TEXT,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_read BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Colleges table
CREATE TABLE IF NOT EXISTS colleges (
  id INT PRIMARY KEY AUTO_INCREMENT,
  state VARCHAR(100) NOT NULL,
  city VARCHAR(100) NOT NULL,
  college_name VARCHAR(255) NOT NULL,
  course_type ENUM('btech', 'mba', 'mbbs', 'bpharma', 'agriculture') NOT NULL,
  fee_amount DECIMAL(10,2),
  fee_period VARCHAR(50),
  program_details TEXT,
  accreditation VARCHAR(100),
  display_order INT DEFAULT 0,
  is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE IF NOT EXISTS site_settings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT,
  setting_category VARCHAR(50),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - Change this in production!)
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@educareerindia.com')
ON DUPLICATE KEY UPDATE username=username;

-- Insert default statistics
INSERT INTO site_statistics (stat_key, stat_value, stat_label, stat_icon, display_order) VALUES
('students_counseled', 5000, 'Students Counseled', '🎓', 1),
('success_rate', 95, 'Success Rate', '✓', 2),
('partner_institutions', 200, 'Partner Institutions', '🏛️', 3),
('years_experience', 15, 'Years of Excellence', '⭐', 4)
ON DUPLICATE KEY UPDATE stat_key=stat_key;

-- Insert sample testimonials
INSERT INTO testimonials (student_name, course, college, batch_year, testimonial_text, rating, is_featured, is_active) VALUES
('Priya Sharma', 'B.Tech CSE', 'RVCE Bangalore', '2024', 'EDU Career India helped me secure a direct seat in RVCE, Bangalore. Their guidance was precise and highly professional. Highly recommended!', 5, TRUE, TRUE),
('Rahul Verma', 'MBBS', 'JSS Medical College, Mysore', '2024', 'Getting into a top MBBS program seemed impossible, but the team made the entire process seamless. Thank you for making my dream a reality.', 5, TRUE, TRUE),
('Anjali Patel', 'MBA Marketing', 'SIBM Pune', '2023', 'I was confused about which MBA college to choose. EDU Career India not only helped me get admission to a top B-school but also guided me in selecting the right specialization for my career goals.', 5, TRUE, TRUE)
ON DUPLICATE KEY UPDATE student_name=student_name;

-- Create indexes for better performance
CREATE INDEX idx_page_content ON page_content(page_name, section_name);
CREATE INDEX idx_colleges_state ON colleges(state, city);
CREATE INDEX idx_contact_submissions ON contact_submissions(submitted_at, is_read);
CREATE INDEX idx_testimonials ON testimonials(is_featured, is_active);

-- Success message
SELECT 'Database initialization completed successfully!' AS Message;
