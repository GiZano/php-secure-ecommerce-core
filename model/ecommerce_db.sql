-- Instructions to import in phpMyAdmin:
-- 1. Open phpMyAdmin
-- 2. Go to the "Import" section
-- 3. Select this file and press "Execute"

CREATE DATABASE IF NOT EXISTS secure_ecommerce;
USE secure_ecommerce;

-- Create Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    available_stocks INT NOT NULL DEFAULT 0
);

-- Create Orders table (with foreign key pointing to Users)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    order_status ENUM('Elaborating', 'Sent', 'Delivered', 'Cancelled') DEFAULT 'Elaborating',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create Order Details table (N-N link between Orders and Products)
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    amount INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- Populate with test data

INSERT INTO users (name, surname, email) VALUES
('Mario', 'Rossi', 'mario.rossi@example.com'),
('Giulia', 'Bianchi', 'giulia.bianchi@example.com'),
('Luca', 'Verdi', 'luca.verdi@example.com');

INSERT INTO products (name, description, price, available_stocks) VALUES
('Smartphone XYZ', 'Smartphone di ultima generazione', 599.99, 50),
('Laptop Pro', 'Notebook potente per sviluppatori', 1299.00, 20),
('Cuffie Bluetooth', 'Cuffie con cancellazione del rumore', 89.50, 100),
('Mouse Wireless', 'Mouse ergonomico', 25.00, 200);

-- Order 1 - Mario Rossi
INSERT INTO orders (user_id, total, order_status) VALUES (1, 689.49, 'Sent');
INSERT INTO order_details (order_id, product_id, amount, unit_price) VALUES 
(1, 1, 1, 599.99), 
(1, 3, 1, 89.50);

-- Order 2 - Giulia Bianchi
INSERT INTO orders (user_id, total, order_status) VALUES (2, 25.00, 'Elaborating');
INSERT INTO order_details (order_id, product_id, amount, unit_price) VALUES 
(2, 4, 1, 25.00);

-- Order 3 - Mario Rossi
INSERT INTO orders (user_id, total, order_status) VALUES (1, 1299.00, 'Delivered');
INSERT INTO order_details (order_id, product_id, amount, unit_price) VALUES 
(3, 2, 1, 1299.00);
