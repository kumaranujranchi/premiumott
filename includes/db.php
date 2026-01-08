<?php
// Database configuration
$host = "localhost";
$db_name = "u743570205_premiumott";
$username = "u743570205_premiumott";
$password = "Anuj@2025@2026";

try {
    $pdo = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get product by ID with features
function getProduct($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        $stmt = $pdo->prepare("SELECT feature_text FROM product_features WHERE product_id = ?");
        $stmt->execute([$id]);
        $product['features'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    return $product;
}

// Function to get all products
function getAllProducts($pdo)
{
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();

    foreach ($products as &$product) {
        $stmt = $pdo->prepare("SELECT feature_text FROM product_features WHERE product_id = ?");
        $stmt->execute([$product['id']]);
        $product['features'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    return $products;
}
?>