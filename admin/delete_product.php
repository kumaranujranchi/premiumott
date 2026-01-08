<?php
include '../includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    } catch (Exception $e) {
        // Handle error if needed
    }
}

header("Location: index.php");
exit;
