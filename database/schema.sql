-- Create Database
CREATE DATABASE IF NOT EXISTS event_registration;
USE event_registration;

-- Create Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Events Table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Attendees Table
CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    ticket_type VARCHAR(50) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

- Create Registered Users Table (merged users and attendees)
CREATE TABLE registered_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    ticket_type ENUM('Standard', 'VIP') NOT NULL DEFAULT 'Standard',
    dietary_requirements TEXT,
    company_organization VARCHAR(150),
    job_title VARCHAR(100),
    how_did_you_hear VARCHAR(100),
    special_accommodations TEXT,
    newsletter_subscribe BOOLEAN DEFAULT FALSE,
    terms_accepted BOOLEAN DEFAULT TRUE,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    registration_status ENUM('confirmed', 'pending', 'cancelled') DEFAULT 'confirmed',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_email (email),
    INDEX idx_event_id (event_id),
    INDEX idx_confirmation_code (confirmation_code)
);

-- Insert Dummy Users (Login/Signup)
INSERT INTO users (full_name, email, password) VALUES
('Admin User', 'admin@example.com', 'admin123'),
('John Doe', 'johndoe@example.com', 'password123');

-- Insert Dummy Event (For Event Details)
INSERT INTO events (name, date, location, description) VALUES
('Creative Summit 2024', '2024-08-15', 'Austin Convention Center', 
'Join industry leaders and tech enthusiasts for a day of insightful talks, networking, and workshops.');

-- Insert Dummy Attendees
INSERT INTO attendees (event_id, full_name, email, phone, ticket_type) VALUES
(1, 'Alice Mensah', 'alice@example.com', '0240001111', 'Standard'),
(1, 'Kwame Boateng', 'kwame@example.com', '0201112222', 'VIP'),
(1, 'Ama Serwaa', 'ama@example.com', '0501234567', 'Standard'),
(1, 'Sarah Johnson', 'sarah@example.com', '0551234567', 'VIP'),
(1, 'Michael Chen', 'michael@example.com', '0241234567', 'Standard');
