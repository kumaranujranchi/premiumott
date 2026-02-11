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

    // 2. Insert or Update Order
    $order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;

    if ($order_id > 0) {
        // UPDATE existing order with Transaction ID
        $stmt = $pdo->prepare("UPDATE orders SET transaction_id = ?, status = 'Pending' WHERE id = ?");
        $stmt->execute([$utr, $order_id]);
    } else {
        // Fallback: INSERT new Order (if for some reason user came directly to payment.php)
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, total_amount, status, transaction_id) VALUES (?, ?, ?, ?, 'Pending', ?)");
        $stmt->execute([$product_id, $name, $email, $amount, $utr]);
        $order_id = $pdo->lastInsertId();
    }

    // 3. Redirect to Confirmation
    header("Location: confirmation.php?order_id=" . $order_id);
    exit;
}
?>