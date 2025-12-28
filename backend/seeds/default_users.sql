-- Smart-Share Default Users Seed Data
-- Passwords: All users have password "password123" (bcrypt hashed)

USE smart_share;

-- Clear existing data (use with caution in production)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE announcement_reactions;
TRUNCATE TABLE announcements;
TRUNCATE TABLE shopping_items;
TRUNCATE TABLE maintenance_tickets;
TRUNCATE TABLE chores;
TRUNCATE TABLE bill_splits;
TRUNCATE TABLE bills;
TRUNCATE TABLE sessions;
TRUNCATE TABLE activity_log;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- Insert demo users with PLAIN TEXT passwords (for assignment purposes only)
-- Password for all users: password123
-- NOTE: In production, passwords should ALWAYS be hashed!

INSERT INTO users (username, email, password, full_name, role, avatar, phone, lease_start, lease_end, is_active) VALUES
('admin', 'ahmed@smartshare.com', 'password123', 'Ahmed Ali', 'admin', 'AA', '+92-300-1234567', '2024-01-01', '2025-12-31', TRUE),
('roommate1', 'hassan@gmail.com', 'password123', 'Hassan Khan', 'roommate', 'HK', '+92-301-2345678', '2024-01-01', '2025-12-31', TRUE),
('roommate2', 'fatima@gmail.com', 'password123', 'Fatima Noor', 'roommate', 'FN', '+92-302-3456789', '2024-01-01', '2025-12-31', TRUE),
('landlord', 'malik@properties.com', 'password123', 'Malik Ahmed', 'landlord', 'MA', '+92-321-4567890', NULL, NULL, TRUE),
('maintenance', 'usman@maintenance.com', 'password123', 'Usman Tariq', 'maintenance', 'UT', '+92-345-5678901', NULL, NULL, TRUE);

-- Insert sample bills
INSERT INTO bills (title, amount, category, due_date, is_paid, paid_by, created_by) VALUES
('December Rent', 45000.00, 'rent', '2024-12-05', TRUE, 1, 1),
('Electricity Bill - November', 8500.00, 'electricity', '2024-12-15', FALSE, NULL, 1),
('Internet Package', 3000.00, 'internet', '2024-12-01', TRUE, 2, 1),
('Gas Bill - November', 2500.00, 'gas', '2024-12-20', FALSE, NULL, 1);

-- Insert bill splits (split equally among 3 roommates: admin, roommate1, roommate2)
INSERT INTO bill_splits (bill_id, user_id, amount, is_paid, paid_date) VALUES
-- December Rent splits
(1, 1, 15000.00, TRUE, '2024-12-03'),
(1, 2, 15000.00, TRUE, '2024-12-03'),
(1, 3, 15000.00, TRUE, '2024-12-04'),
-- Electricity Bill splits
(2, 1, 2833.33, FALSE, NULL),
(2, 2, 2833.33, FALSE, NULL),
(2, 3, 2833.34, FALSE, NULL),
-- Internet Package splits
(3, 1, 1000.00, TRUE, '2024-11-28'),
(3, 2, 1000.00, TRUE, '2024-11-28'),
(3, 3, 1000.00, TRUE, '2024-11-29'),
-- Gas Bill splits
(4, 1, 833.33, FALSE, NULL),
(4, 2, 833.33, FALSE, NULL),
(4, 3, 833.34, FALSE, NULL);

-- Insert sample chores (current week)
INSERT INTO chores (title, description, assigned_to, assigned_date, due_date, is_completed, recurrence, created_by) VALUES
('Kitchen Cleaning', 'Clean kitchen counters, sink, and floor', 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAYS), FALSE, 'weekly', 1),
('Bathroom Cleaning', 'Clean all bathrooms', 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAYS), TRUE, 'weekly', 1),
('Living Room Vacuuming', 'Vacuum living room and hallway', 3, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAYS), FALSE, 'weekly', 1),
('Trash Collection', 'Take out all trash and recycling', 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), FALSE, 'weekly', 1),
('Grocery Shopping', 'Weekly grocery run', 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 DAYS), FALSE, 'weekly', 1);

-- Insert sample maintenance tickets
INSERT INTO maintenance_tickets (title, description, category, priority, status, location, reported_by, assigned_to) VALUES
('Leaking Kitchen Faucet', 'The kitchen sink faucet is dripping constantly. Wasting water.', 'plumbing', 'medium', 'in_progress', 'Kitchen', 2, 5),
('Bedroom AC Not Cooling', 'Air conditioner in master bedroom not producing cold air', 'appliance', 'high', 'open', 'Master Bedroom', 1, NULL),
('Broken Door Lock', 'Front door lock is jammed and difficult to open', 'structural', 'urgent', 'open', 'Front Door', 3, 5);

-- Insert sample shopping items
INSERT INTO shopping_items (item_name, quantity, category, is_purchased, claimed_by, added_by) VALUES
('Milk', '2 liters', 'groceries', FALSE, NULL, 1),
('Bread', '2 loaves', 'groceries', FALSE, 2, 3),
('Dish Soap', '1 bottle', 'household', TRUE, 1, 1),
('Toilet Paper', '12 rolls', 'household', FALSE, NULL, 2),
('Rice', '5 kg', 'groceries', FALSE, 3, 1),
('Laundry Detergent', '1 pack', 'household', FALSE, NULL, 3);

-- Insert sample announcements
INSERT INTO announcements (title, content, is_important, posted_by) VALUES
('House Meeting This Friday', 'Monthly house meeting scheduled for Friday, 7 PM. Please be present to discuss December expenses and upcoming maintenance.', TRUE, 1),
('WiFi Password Changed', 'The WiFi password has been updated for security. New password: SmartShare2024!', FALSE, 1),
('Water Supply Interruption', 'Water supply will be interrupted on Sunday between 10 AM - 2 PM for maintenance work in the area.', TRUE, 4),
('New House Rules', 'Please remember to lock all doors and windows before leaving. Security is everyone\'s responsibility!', FALSE, 1);

-- Insert sample announcement reactions
INSERT INTO announcement_reactions (announcement_id, user_id, reaction) VALUES
(1, 2, '👍'),
(1, 3, '👍'),
(2, 2, '✓'),
(2, 3, '✓'),
(3, 1, '👍'),
(3, 2, '👍'),
(4, 2, '✓'),
(4, 3, '👍');

-- Insert sample activity log
INSERT INTO activity_log (user_id, action, entity_type, entity_id, description, ip_address) VALUES
(1, 'login', NULL, NULL, 'User logged in', '127.0.0.1'),
(2, 'create_bill', 'bill', 2, 'Created electricity bill', '127.0.0.1'),
(1, 'mark_paid', 'bill', 1, 'Marked rent bill as paid', '127.0.0.1'),
(3, 'complete_chore', 'chore', 2, 'Completed bathroom cleaning', '127.0.0.1'),
(2, 'create_ticket', 'maintenance_ticket', 1, 'Reported leaking faucet', '127.0.0.1'),
(1, 'create_announcement', 'announcement', 1, 'Posted house meeting announcement', '127.0.0.1');
