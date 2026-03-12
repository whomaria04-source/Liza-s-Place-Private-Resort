-- Create Database
CREATE DATABASE IF NOT EXISTS resort_booking_system;
USE resort_booking_system;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Rooms Table
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    image VARCHAR(255),
    room_type VARCHAR(50),
    amenities TEXT,
    status ENUM('available', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    number_of_guests INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_room_id (room_id),
    INDEX idx_status (status),
    INDEX idx_check_in (check_in),
    INDEX idx_check_out (check_out)
);

-- Admin Table (for admin info)
CREATE TABLE IF NOT EXISTS admin_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample admin user
INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@resort.com', 'admin123', 'admin');

-- Insert sample rooms
INSERT INTO rooms (room_name, description, price, capacity, image, room_type, amenities, status) VALUES 
('Deluxe Room', 'Spacious room with ocean view, king-size bed, and modern amenities.', 150.00, 2, 'deluxe-room.jpg', 'Room', 'WiFi, AC, TV, Shower', 'available'),
('Family Cottage', 'Perfect for families with 2 bedrooms, living area, and kitchen.', 250.00, 6, 'family-cottage.jpg', 'Cottage', 'WiFi, Kitchen, TV, Dining Area', 'available'),
('Standard Room', 'Comfortable room with queen-size bed and basic amenities.', 100.00, 2, 'standard-room.jpg', 'Room', 'WiFi, AC, TV', 'available'),
('Suite Room', 'Luxury suite with separate bedroom and living area.', 300.00, 4, 'suite-room.jpg', 'Suite', 'WiFi, Jacuzzi, Mini Bar, King Bed', 'available');

-- Insert sample bookings
INSERT INTO bookings (user_id, room_id, check_in, check_out, number_of_guests, total_price, status) VALUES 
(1, 1, '2026-03-15', '2026-03-17', 2, 300.00, 'confirmed');