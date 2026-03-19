<?php
include 'auth_check.php';   // starts session + enforces login
include '../includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        // Detach any existing orders so the FK constraint doesn't block deletion.
        // Order history is preserved with product_id set to NULL.
        $stmt = $pdo->prepare("UPDATE orders SET product_id = NULL WHERE product_id = ?");
        $stmt->execute([$id]);

        // Now delete the product (product_features are removed automatically via ON DELETE CASCADE).
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Product deleted successfully.'
        ];
    } catch (Exception $e) {
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => 'Failed to delete product: ' . $e->getMessage()
        ];
    }
}

header("Location: index.php");
exit;
