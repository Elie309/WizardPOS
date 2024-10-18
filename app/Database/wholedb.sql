CREATE SCHEMA IF NOT EXISTS wizardpos;
USE wizardpos;

CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(50) UNIQUE NOT NULL,
    employee_password VARCHAR(255) NOT NULL,
    employee_first_name VARCHAR(50) NOT NULL,
    employee_last_name VARCHAR(50) NOT NULL,
    employee_role ENUM('Admin', 'Cashier', 'Waiter') NOT NULL,
    employee_number VARCHAR(15),
    employee_email VARCHAR(100),
    employee_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    employee_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    client_first_name VARCHAR(50) NOT NULL,
    client_last_name VARCHAR(50) NOT NULL,
    client_number VARCHAR(15) UNIQUE NOT NULL,
    client_email VARCHAR(100),
    client_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    client_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    product_description TEXT,
    product_price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES Categories(category_id) ON DELETE SET NULL
);



