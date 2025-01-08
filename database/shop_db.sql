-- Create and use the shop_db database
CREATE DATABASE IF NOT EXISTS shop_db;
USE shop_db;

-- Users table for login authentication
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inventory table for product tracking
CREATE TABLE inventory (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    quantity INT NOT NULL DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inventory log table for tracking changes
CREATE TABLE inventory_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    quantity_changed INT NOT NULL,
    previous_quantity INT NOT NULL,
    new_quantity INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES inventory(item_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Items table for additional product tracking
CREATE TABLE items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    date_modified DATETIME NOT NULL
);

-- Insert a default admin user (username: admin, password: admin123)
INSERT INTO users (username, password, role) VALUES ('admin', 'admin123', 'admin');
