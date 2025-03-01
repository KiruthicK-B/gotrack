CREATE DATABASE GoTrack_DB;

USE GoTrack_DB;

-- Users Table (Passenger Signup/Login)
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50),
    lname VARCHAR(50),
    phone VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    user_type ENUM('Passenger', 'Admin') DEFAULT 'Passenger',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Drivers Table
CREATE TABLE Drivers (
    driver_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50),
    lname VARCHAR(50),
    phone VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    bus_no VARCHAR(50),
    route_no VARCHAR(50),
    total_seats INT DEFAULT 65,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bus Seats Status Table (Real-time Seat Tracking)
CREATE TABLE BusSeats (
    seat_id INT AUTO_INCREMENT PRIMARY KEY,
    bus_no VARCHAR(50),
    seat_number INT,
    seat_status ENUM('Available', 'Occupied', 'Reserved') DEFAULT 'Available',
    user_id INT,
    booked_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (bus_no) REFERENCES Drivers(bus_no) ON DELETE CASCADE
);

-- Routes Table
CREATE TABLE Routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    route_no VARCHAR(50),
    start_point VARCHAR(100),
    end_point VARCHAR(100),
    distance_km DOUBLE,
    traffic_status ENUM('Normal', 'Delayed', 'Heavy Traffic'),
    estimated_time INT, -- in Minutes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notifications Table for Push Alerts
CREATE TABLE Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    status ENUM('Unread', 'Read') DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);
ALTER TABLE Drivers ADD UNIQUE (bus_no);

ALTER TABLE Drivers
ADD COLUMN bus_type ENUM('AC', 'Non-AC', 'Sleeper', 'Seater') NOT NULL AFTER bus_no,
ADD COLUMN start_location VARCHAR(100) NOT NULL AFTER route_no,
ADD COLUMN destination VARCHAR(100) NOT NULL AFTER start_location,
ADD COLUMN intermediate_stops TEXT AFTER destination;
