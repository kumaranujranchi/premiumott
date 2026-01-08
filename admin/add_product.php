<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discounted_price = $_POST['discounted_price'];
    $rating = $_POST['rating'] ?: 5.0;
    $reviews = $_POST['reviews'] ?: 0;
    $discount_percent = round((($original_price - $discounted_price) / $original_price) * 100);
    $category = $_POST['category'];
    $license_type = $_POST['license_type'];
    $icon = $_POST['icon'];
    $color = $_POST['color'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $currency = $_POST['currency'] ?: 'USD';
    $features = explode("\n", str_replace("\r", "", $_POST['features']));

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../uploads/products/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $image = "uploads/products/" . $fileName;
            }
        }
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO products (name, tagline, description, original_price, discounted_price, rating, reviews, discount_percent, category, license_type, icon, color, image, currency, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $tagline, $description, $original_price, $discounted_price, $rating, $reviews, $discount_percent, $category, $license_type, $icon, $color, $image, $currency, $is_active]);

        $productId = $pdo->lastInsertId();

        foreach ($features as $feature) {
            $feature = trim($feature);
            if (!empty($feature)) {
                $fStmt = $pdo->prepare("INSERT INTO product_features (product_id, feature_text) VALUES (?, ?)");
                $fStmt->execute([$productId, $feature]);
            }
        }

        $pdo->commit();
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Premium OTT Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3DFE02;
            --bg: #F9FAFB;
            --card: #FFFFFF;
            --text: #111827;
            --border: #E5E7EB;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #374151;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
            margin-top: 10px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .back-link {
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="index.php" class="back-link">← Back</a>
            <h1>Add New Product</h1>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: #FEE2E2; color: #B91C1C; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="card" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required placeholder="e.g. Nexus CRM">
            </div>
            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" required placeholder="Short catchy description">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required placeholder="Detailed product description"></textarea>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Original Price</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="currency" style="width: 80px;">
                            <option value="USD">USD ($)</option>
                            <option value="INR">INR (₹)</option>
                        </select>
                        <input type="number" step="0.01" name="original_price" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Discounted Price</label>
                    <input type="number" step="0.01" name="discounted_price" required>
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" required placeholder="e.g. CRM, Marketing">
                </div>
                <div class="form-group">
                    <label>License Type</label>
                    <input type="text" name="license_type" required placeholder="e.g. Lifetime Deal">
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Icon (Lucide name)</label>
                    <select name="icon">
                        <option value="users">Users</option>
                        <option value="rocket">Rocket</option>
                        <option value="database">Database</option>
                        <option value="video">Video</option>
                        <option value="mail">Mail</option>
                        <option value="shield">Shield</option>
                        <option value="zap">Zap</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Theme Color (Hex)</label>
                    <input type="color" name="color" value="#3B82F6" style="height: 42px; padding: 5px;">
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-top: 30px;">
                        <input type="checkbox" name="is_active" checked style="width: auto;">
                        Active (Visible on Store)
                    </label>
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Rating (1-5)</label>
                    <input type="number" step="0.1" name="rating" value="4.9" min="1" max="5">
                </div>
                <div class="form-group">
                    <label>Reviews Count</label>
                    <input type="number" name="reviews" value="0">
                </div>
            </div>

            <div class="form-group">
                <label>Features (One per line)</label>
                <textarea name="features" required
                    placeholder="Unlimited contacts&#10;Email tracking&#10;API access"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</body>

</html>