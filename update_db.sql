ALTER TABLE products ADD COLUMN section VARCHAR(100) DEFAULT 'New Arrivals' AFTER category;

-- Payment verification columns (run once)
ALTER TABLE orders ADD COLUMN IF NOT EXISTS upi_payer_name VARCHAR(255) DEFAULT NULL AFTER transaction_id;
ALTER TABLE orders ADD COLUMN IF NOT EXISTS amount_entered DECIMAL(10,2) DEFAULT NULL AFTER upi_payer_name;
