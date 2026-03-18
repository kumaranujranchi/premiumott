-- Run this once on your live database to add user auth tables

ALTER TABLE products ADD COLUMN IF NOT EXISTS section VARCHAR(100) DEFAULT 'New Arrivals';

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)        NOT NULL,
    email       VARCHAR(255) UNIQUE  NOT NULL,
    password    VARCHAR(255)        NOT NULL,   -- bcrypt hash
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
