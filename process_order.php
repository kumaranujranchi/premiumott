<?php
include 'includes/db.php';
include 'includes/user_auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id   = (int)  ($_POST['product_id'] ?? 0);
    $order_id     = (int)  ($_POST['order_id']   ?? 0);
    $utr          = trim(   $_POST['transaction_id']  ?? '');
    $upi_payer    = trim(   $_POST['upi_payer_name']  ?? '');
    $whatsapp     = trim(   $_POST['whatsapp']        ?? '');
    $requirements = trim(   $_POST['requirements']    ?? '');

    $product = getProduct($pdo, $product_id);
    if (!$product) {
        die('Invalid Product');
    }

    // Always use the real product price — never trust user-submitted amount
    $amount_entered = (float) $product['discounted_price'];

    if ($order_id > 0) {
        $stmt = $pdo->prepare("
            UPDATE orders
            SET transaction_id = ?, upi_payer_name = ?, amount_entered = ?, status = 'Pending'
            WHERE id = ?
        ");
        $stmt->execute([$utr, $upi_payer, $amount_entered, $order_id]);
    } else {
        // Fallback: user arrived directly at payment page
        $name  = $_SESSION['user_name']  ?? 'Customer';
        $email = $_SESSION['user_email'] ?? '';
        
        try {
            // Check available columns
            $columnsStmt      = $pdo->query("SHOW COLUMNS FROM orders");
            $availableColumns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
            $orderData = [
                'product_id'        => $product_id,
                'customer_name'     => $name,
                'customer_email'    => $email,
                'customer_whatsapp' => $whatsapp,
                'requirements'      => $requirements,
                'total_amount'      => $product['discounted_price'],
                'status'            => 'Pending',
                'transaction_id'    => $utr,
                'upi_payer_name'    => $upi_payer,
                'amount_entered'    => $amount_entered
            ];
    
            $insertData = [];
            foreach ($orderData as $column => $value) {
                if (in_array($column, $availableColumns, true)) {
                    $insertData[$column] = $value;
                }
            }
    
            $columnNames  = array_keys($insertData);
            $placeholders = implode(', ', array_fill(0, count($columnNames), '?'));
            $sql          = "INSERT INTO orders (" . implode(', ', $columnNames) . ") VALUES (" . $placeholders . ")";
    
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($insertData));
            $order_id = $pdo->lastInsertId();
        } catch (Throwable $e) {
            die('Error creating order: ' . $e->getMessage());
        }
    }

    header('Location: confirmation?order_id=' . $order_id);
    exit;
}
?>