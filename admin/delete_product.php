<?php
session_start();
include '../includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Check for existing orders referencing this product
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE product_id = ?");
        $stmt->execute([$id]);
        $ordersCount = (int) $stmt->fetchColumn();

        if ($ordersCount > 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Cannot delete product because there are existing orders referencing it.'
            ];
        } else {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Product deleted successfully.'
            ];
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Failed to delete product: ' . $e->getMessage()
        ];
    }
}

header("Location: index.php");
exit;
