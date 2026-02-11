<?php
include 'includes/db.php';

try {
    // Add transaction_id column if not exists
    $sql = "ALTER TABLE orders ADD COLUMN IF NOT EXISTS transaction_id VARCHAR(50);";
    $pdo->exec($sql);
    echo "Database updated successfully! 'transaction_id' column added.";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>