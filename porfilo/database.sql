-- Portfolio Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS jayaram_portfolio;
USE jayaram_portfolio;

-- Create messages table for contact form
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'read', 'replied') DEFAULT 'new'
);

-- Create visitors table for analytics
CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    page_visited VARCHAR(100),
    visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    country VARCHAR(100),
    city VARCHAR(100)
);

-- Create skills table for dynamic skills management
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(50) NOT NULL,
    skill_icon VARCHAR(50) NOT NULL,
    skill_level INT DEFAULT 0,
    category VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create projects table for dynamic project management
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(500),
    project_url VARCHAR(500),
    github_url VARCHAR(500),
    technologies TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample skills data
INSERT INTO skills (skill_name, skill_icon, skill_level, category) VALUES
('HTML', 'fab fa-html5', 90, 'Frontend'),
('CSS', 'fab fa-css3-alt', 85, 'Frontend'),
('JavaScript', 'fab fa-js-square', 80, 'Frontend'),
('PHP', 'fab fa-php', 75, 'Backend'),
('SQL', 'fas fa-database', 85, 'Backend'),
('Figma', 'fab fa-figma', 70, 'Design'),
('React', 'fab fa-react', 65, 'Frontend'),
('Node.js', 'fab fa-node-js', 60, 'Backend'),
('MySQL', 'fas fa-database', 80, 'Database'),
('Git', 'fab fa-git-alt', 75, 'Tools');

-- Insert sample projects data
INSERT INTO projects (title, description, image_url, project_url, github_url, technologies, is_featured) VALUES
('E-commerce Website', 'A fully functional online store built with HTML, CSS, JavaScript, PHP, and SQL. Features user authentication, product management, and secure payment integration.', 'project1.jpg', '#', '#', 'HTML, CSS, JavaScript, PHP, MySQL', TRUE),
('Student Tracking System', 'A robust system for managing student data, attendance, and grades. Developed using PHP, SQL, HTML, and CSS with a focus on intuitive UI and data security.', 'project2.jpg', '#', '#', 'PHP, MySQL, HTML, CSS, JavaScript', TRUE),
('Portfolio Website', 'A responsive portfolio website built with modern web technologies. Features smooth animations, contact form, and mobile-first design.', 'project3.jpg', '#', '#', 'HTML, CSS, JavaScript, PHP, MySQL', FALSE),
('Task Management App', 'A web-based task management application with real-time updates and team collaboration features.', 'project4.jpg', '#', '#', 'React, Node.js, MongoDB, Express', FALSE);

-- Create admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, email, password_hash) VALUES
('admin', 'admin@jayaram.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Create site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Jayaram - Portfolio'),
('site_description', 'B.E. Student | Web Developer | Innovator | UI/UX Designer'),
('contact_email', 'jayaram.be@email.com'),
('linkedin_url', '#'),
('github_url', '#'),
('phone_number', '+91 98765 43210'),
('location', 'India'),
('about_text', 'I''m a passionate B.E. student with a deep love for technology and innovation. My journey in computer science has been driven by curiosity and a desire to create meaningful solutions through code.'),
('resume_url', '#');
