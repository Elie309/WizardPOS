
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_phone_number VARCHAR(15) UNIQUE NOT NULL,
    employee_email VARCHAR(100) UNIQUE NULL,
    employee_password VARCHAR(255) NOT NULL,

    employee_first_name VARCHAR(50) NOT NULL,
    employee_last_name VARCHAR(50) NOT NULL,

    
    employee_role ENUM('admin', 'manager', 'user') DEFAULT 'user',
    employee_is_active BOOLEAN DEFAULT 1,

    employee_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    employee_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    employee_deleted_at TIMESTAMP NULL
);

ALTER TABLE employees AUTO_INCREMENT = 1001;

CREATE TABLE clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    
    client_first_name VARCHAR(50) NOT NULL,
    client_last_name VARCHAR(50) NOT NULL,

    client_phone_number VARCHAR(255) UNIQUE NOT NULL,

    client_email VARCHAR(100),
    client_address TEXT,

    client_is_active BOOLEAN DEFAULT 1,

    client_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    client_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    client_deleted_at TIMESTAMP NULL

);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,

    category_name VARCHAR(50) UNIQUE NOT NULL,
    category_description TEXT,
    category_image TEXT NULL,

    category_is_active BOOLEAN DEFAULT 1,
    category_show_in_menu BOOLEAN DEFAULT 1,

    category_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_sku VARCHAR(50) UNIQUE NOT NULL,
    product_slug VARCHAR(100) UNIQUE NOT NULL,

    product_name VARCHAR(100) NOT NULL,
    product_description TEXT,

    product_price DECIMAL(10, 2) NOT NULL,

    product_category_id INT NOT NULL,
    product_show_in_menu BOOLEAN DEFAULT 1,

    product_production_date DATE NULL,
    product_expiry_date DATE NULL,

    product_image TEXT NULL,
    product_is_active BOOLEAN DEFAULT 1,

    product_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    product_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    product_deleted_at TIMESTAMP NULL,

    FOREIGN KEY (product_category_id) REFERENCES categories(category_id)
);




CREATE TABLE restaurant_tables (
    table_id INT AUTO_INCREMENT PRIMARY KEY,

    table_name VARCHAR(50) NOT NULL,
    table_description TEXT,
    table_max_capacity INT NOT NULL,

    table_is_active BOOLEAN DEFAULT 1,
    table_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    table_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    table_deleted_at TIMESTAMP NULL
);

CREATE TABLE reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_client_id INT NOT NULL,
    reservation_table_id INT NOT NULL,
    reservation_employee_id INT NOT NULL,

    reservation_date DATE NOT NULL,
    reservation_starting_time TIME NOT NULL,
    reservation_ending_time TIME NOT NULL,
    reservation_guests INT NULL,
    reservation_status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',

    reservation_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reservation_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    reservation_deleted_at TIMESTAMP NULL,

    FOREIGN KEY (reservation_client_id) REFERENCES clients(client_id),
    FOREIGN KEY (reservation_table_id) REFERENCES restaurant_tables(table_id),
    FOREIGN KEY (reservation_employee_id) REFERENCES employees(employee_id)
);


CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_client_id INT NOT NULL,
    order_employee_id INT NOT NULL,

    order_type ENUM('take-away', 'dine-in', 'delivery') DEFAULT 'take-away',
    order_reference VARCHAR(50) UNIQUE NULL,

    order_date DATE NOT NULL,
    order_time TIME NOT NULL,
    order_note TEXT NULL,
    
    order_subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    order_discount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    order_tax DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    order_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,

    order_status ENUM('on-going', 'completed', 'cancelled') DEFAULT 'on-going',

    order_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    order_deleted_at TIMESTAMP NULL,

    FOREIGN KEY (order_client_id) REFERENCES clients(client_id),
    FOREIGN KEY (order_employee_id) REFERENCES employees(employee_id)
);


CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,
    order_item_product_id INT NOT NULL,

    order_item_quantity INT DEFAULT 1,
    order_item_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,

    order_item_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_item_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    order_item_deleted_at TIMESTAMP NULL,

    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (order_item_product_id) REFERENCES products(product_id)
);