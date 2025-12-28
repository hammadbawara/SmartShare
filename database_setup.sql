-- Smart-Share Simple Database Schema

CREATE DATABASE IF NOT EXISTS smartshare_db;
USE smartshare_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'roommate', 'landlord', 'maintenance') NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bills Table
CREATE TABLE IF NOT EXISTS bills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    due_date DATE NOT NULL,
    created_by INT NOT NULL,
    status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Chores Table
CREATE TABLE IF NOT EXISTS chores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_to INT NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE
);

-- Maintenance Tickets Table
CREATE TABLE IF NOT EXISTS maintenance_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('open', 'in-progress', 'completed') DEFAULT 'open',
    reported_by INT NOT NULL,
    assigned_to INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Shopping List Table
CREATE TABLE IF NOT EXISTS shopping_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    quantity INT DEFAULT 1,
    is_purchased BOOLEAN DEFAULT 0,
    added_by INT NOT NULL,
    claimed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (claimed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Announcements Table
CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    posted_by INT NOT NULL,
    is_important BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Demo Users
INSERT INTO users (id, username, fullname, email, role) VALUES
(1, 'admin', 'Ahmed Ali', 'admin@smartshare.com', 'admin'),
(2, 'roommate1', 'Hassan Khan', 'roommate1@smartshare.com', 'roommate'),
(3, 'roommate2', 'Fatima Noor', 'roommate2@smartshare.com', 'roommate'),
(4, 'landlord', 'Malik Ahmed', 'landlord@smartshare.com', 'landlord'),
(5, 'maintenance', 'Usman Tariq', 'maintenance@smartshare.com', 'maintenance');

-- Sample Data
INSERT INTO bills (title, amount, category, due_date, created_by, status) VALUES
('Electricity Bill', 5000, 'Utilities', '2025-01-15', 1, 'unpaid'),
('Internet Bill', 3000, 'Internet', '2025-01-10', 1, 'paid'),
('Water Bill', 2000, 'Utilities', '2025-01-05', 1, 'unpaid');

INSERT INTO chores (title, description, assigned_to, due_date, status) VALUES
('Clean Kitchen', 'Wash dishes and clean counters', 2, '2025-12-30', 'pending'),
('Sweep Living Room', 'Vacuum and mop the living room', 3, '2025-12-31', 'completed'),
('Bathroom Cleaning', 'Clean toilet and sink', 2, '2025-12-29', 'pending');

INSERT INTO maintenance_tickets (title, description, priority, status, reported_by, assigned_to) VALUES
('Broken Ceiling Fan', 'Ceiling fan in bedroom is not working', 'medium', 'open', 2, 5),
('Leaky Tap', 'Kitchen tap is leaking water', 'high', 'in-progress', 3, 5);

INSERT INTO shopping_items (item_name, category, quantity, added_by) VALUES
('Milk', 'Dairy', 1, 1),
('Bread', 'Bakery', 2, 2),
('Vegetables', 'Produce', 1, 3);

INSERT INTO announcements (title, content, posted_by, is_important) VALUES
('Weekly Meeting', 'House meeting on Saturday at 6 PM', 1, 1),
('Guest Policy', 'Please inform before bringing guests', 1, 0);
