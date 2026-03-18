<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id     = (int)   ($_POST['product_id']      ?? 0);
    $order_id       = (int)   ($_POST['order_id']        ?? 0);
    $utr            = trim(    $_POST['transaction_id']  ?? '');
    $upi_payer      = trim(    $_POST['upi_payer_name']  ?? '');
    $amount_entered = (float) ($_POST['amount_entered']  ?? 0);

    $product = getProduct($pdo, $product_id);
    if (!$product) {
        die('Invalid Product');
    }

    if ($order_id > 0) {
        $stmt = $pdo->prepare("
            UPDATE orders
            SET transaction_id = ?, upi_payer_name = ?, amount_entered = ?, status = 'Pending'
            WHERE id = ?
        ");
        $stmt->execute([$utr, $upi_payer, $amount_entered, $order_id]);
    } else {
        // Fallback: user arrived directly at payment page
        $name  = trim($_POST['name']  ?? 'Customer');
        $email = trim($_POST['email'] ?? '');
        $stmt  = $pdo->prepare("
            INSERT INTO orders
                (product_id, customer_name, customer_email, total_amount, status, transaction_id, upi_payer_name, amount_entered)
            VALUES (?, ?, ?, ?, 'Pending', ?, ?, ?)
        ");
        $stmt->execute([$product_id, $name, $email, $product['discounted_price'], $utr, $upi_payer, $amount_entered]);
        $order_id = $pdo->lastInsertId();
    }

    header('Location: confirmation.php?order_id=' . $order_id);
    exit;
}
?>