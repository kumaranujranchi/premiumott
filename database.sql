CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    tagline VARCHAR(255),
    description TEXT,
    original_price DECIMAL(10, 2),
    discounted_price DECIMAL(10, 2),
    rating DECIMAL(2, 1),
    reviews INT,
    discount_percent INT,
    category VARCHAR(100),
    license_type VARCHAR(100),
    icon VARCHAR(50),
    color VARCHAR(20),
    image VARCHAR(255),
    currency VARCHAR(10) DEFAULT 'USD',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS product_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    feature_text VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    customer_whatsapp VARCHAR(20),
    requirements TEXT,
    total_amount DECIMAL(10, 2),
    status ENUM('Pending', 'Processed', 'Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: admin@premiumott.com / Admin@123
INSERT INTO admins (email, password) VALUES ('admin@premiumott.com', '$2y$10$iI0T7Q.fWp.fKk7fK/L0e.hC5U0P.kX1K.K/K.K.K/K.K.K/K.K.K');
