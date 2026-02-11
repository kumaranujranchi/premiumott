<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $utr = trim($_POST['transaction_id']);

    // 1. Validate Product
    $product = getProduct($pdo, $product_id);
    if (!$product) {
        die("Invalid Product");
    }

    $amount = $product['discounted_price'];

    // 2. Insert Order (Pending)
    $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, total_amount, status, transaction_id) VALUES (?, ?, ?, ?, 'Pending', ?)");
    $stmt->execute([$product_id, $name, $email, $amount, $utr]);

    // 3. Redirect to Confirmation (or check_status)
    $order_id = $pdo->lastInsertId();
    header("Location: confirmation.php?order_id=" . $order_id);
    exit;
}
?>