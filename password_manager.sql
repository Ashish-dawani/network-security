-- Use or create the database
CREATE DATABASE IF NOT EXISTS password_manager;
USE password_manager;

-- Create a users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create a table to store passwords
CREATE TABLE stored_passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    website VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    iv VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE otp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    email VARCHAR(255),
    otp VARCHAR(6), -- Assuming OTP is a 6-digit code
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

