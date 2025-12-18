-- Database: aim_db
-- Create database
CREATE DATABASE IF NOT EXISTS aim_db;
USE aim_db;
-- Table 1: farmers (represents Farmer data)
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_name VARCHAR(100) NOT NULL,
    water_usage DECIMAL(10, 2) NOT NULL,
    farm_size DECIMAL(10, 2) NOT NULL,
    entry_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for farmers
INSERT INTO farmers (farmer_name, water_usage, farm_size, entry_date) VALUES
('Ahmed Al-Rashidi', 250.50, 2.5, '2025-12-15'),
('Fatima Al-Balushi', 320.75, 3.2, '2025-12-14'),
('Mohammed Al-Hinai', 180.25, 1.8, '2025-12-13'),
('Aisha Al-Ghafri', 410.00, 4.5, '2025-12-12'),
('Salem Al-Siyabi', 295.60, 2.9, '2025-12-11');

-- Table 2: companies (represents Company data)
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    registration_date DATE NOT NULL,
    contact_email VARCHAR(100),
    total_farms INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for companies
INSERT INTO companies (company_name, registration_date, contact_email, total_farms) VALUES
('Al-Wusta Agricultural Corp', '2025-01-15', 'info@alwusta.om', 5),
('Dhofar Farms Ltd', '2025-02-20', 'contact@dhofarfarms.om', 8),
('Muscat AgriTech', '2025-03-10', 'hello@muscatagri.om', 3),
('Nizwa Irrigation Co', '2025-04-05', 'support@nizwairrig.om', 6),
('Salalah Green Solutions', '2025-05-12', 'info@salalahgreen.om', 4);

-- Table 3: irrigation_records (represents irrigation data from companies)
CREATE TABLE IF NOT EXISTS irrigation_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    irrigation_amount DECIMAL(10, 2) NOT NULL,
    location VARCHAR(100) NOT NULL,
    record_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for irrigation records
INSERT INTO irrigation_records (company_name, irrigation_amount, location, record_date) VALUES
('Al-Wusta Agricultural Corp', 4.5, 'Al-Wusta Region', '2025-12-15'),
('Dhofar Farms Ltd', 6.2, 'Salalah', '2025-12-14'),
('Muscat AgriTech', 3.8, 'Muscat Plains', '2025-12-13'),
('Nizwa Irrigation Co', 5.5, 'Nizwa Valley', '2025-12-12'),
('Salalah Green Solutions', 4.9, 'Dhofar Coast', '2025-12-11');

CREATE TABLE IF NOT EXISTS questionnaire_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_type VARCHAR(50),
    satisfaction VARCHAR(50),
    topics TEXT,
    message TEXT,
    agree TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Insert sample data for questionnaire_feedback
INSERT INTO questionnaire_feedback (full_name, email, user_type, satisfaction, topics, message, agree) VALUES
('Ahmed Al-Rashidi', 'ahmed@example.om', 'Farmer', 'Very satisfied', 'Water saving tips, Farm analytics', 'Great system for monitoring water usage!', 1),
('Fatima Al-Balushi', 'fatima@example.om', 'Researcher', 'Satisfied', 'Government policies, Farm analytics', 'Very useful data for research purposes.', 1),
('Mohammed Al-Hinai', 'mohammed@example.om', 'Company', 'Neutral', 'Water saving tips', 'Good but needs more features.', 1),
('Aisha Al-Ghafri', 'aisha@example.om', 'Government', 'Very satisfied', 'Government policies, Water saving tips', 'Excellent tool for policy making!', 1),
('Salem Al-Siyabi', 'salem@example.om', 'Farmer', 'Satisfied', 'Farm analytics', 'Helps me track my water usage better.', 1);

-- Table 5: gov_regions_usage (for government regional water usage data)
CREATE TABLE IF NOT EXISTS gov_regions_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(100) NOT NULL,
    january INT NOT NULL,
    february INT NOT NULL,
    march INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for gov_regions_usage
INSERT INTO gov_regions_usage (region, january, february, march) VALUES
('Muscat', 1200000, 1150000, 1300000),
('Salalah', 950000, 980000, 1020000),
('Nizwa', 800000, 820000, 850000),
('Al-Wusta', 650000, 670000, 690000),
('Sohar', 1100000, 1080000, 1150000);

-- Show all tables
SHOW TABLES;
