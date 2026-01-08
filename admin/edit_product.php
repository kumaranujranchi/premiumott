<?php
include '../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$product = getProduct($pdo, $id);
if (!$product) {
    die("Product not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discounted_price = $_POST['discounted_price'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $discount_percent = round((($original_price - $discounted_price) / $original_price) * 100);
    $category = $_POST['category'];
    $license_type = $_POST['license_type'];
    $icon = $_POST['icon'];
    $color = $_POST['color'];
    $features = explode("\n", str_replace("\r", "", $_POST['features']));

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE products SET name=?, tagline=?, description=?, original_price=?, discounted_price=?, rating=?, reviews=?, discount_percent=?, category=?, license_type=?, icon=?, color=? WHERE id=?");
        $stmt->execute([$name, $tagline, $description, $original_price, $discounted_price, $rating, $reviews, $discount_percent, $category, $license_type, $icon, $color, $id]);

        // Update features: delete old ones and insert new ones
        $pdo->prepare("DELETE FROM product_features WHERE product_id = ?")->execute([$id]);

        foreach ($features as $feature) {
            $feature = trim($feature);
            if (!empty($feature)) {
                $fStmt = $pdo->prepare("INSERT INTO product_features (product_id, feature_text) VALUES (?, ?)");
                $fStmt->execute([$id, $feature]);
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

$features_text = implode("\n", $product['features']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Premium OTT Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3B82F6;
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
            <h1>Edit Product:
                <?php echo htmlspecialchars($product['name']); ?>
            </h1>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: #FEE2E2; color: #B91C1C; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="card">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" required value="<?php echo htmlspecialchars($product['tagline']); ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"
                    required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Original Price ($)</label>
                    <input type="number" step="0.01" name="original_price" required
                        value="<?php echo $product['original_price']; ?>">
                </div>
                <div class="form-group">
                    <label>Discounted Price ($)</label>
                    <input type="number" step="0.01" name="discounted_price" required
                        value="<?php echo $product['discounted_price']; ?>">
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" required
                        value="<?php echo htmlspecialchars($product['category']); ?>">
                </div>
                <div class="form-group">
                    <label>License Type</label>
                    <input type="text" name="license_type" required
                        value="<?php echo htmlspecialchars($product['license_type']); ?>">
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Icon (Lucide name)</label>
                    <select name="icon">
                        <option value="users" <?php echo $product['icon'] == 'users' ? 'selected' : ''; ?>>Users</option>
                        <option value="rocket" <?php echo $product['icon'] == 'rocket' ? 'selected' : ''; ?>>Rocket
                        </option>
                        <option value="database" <?php echo $product['icon'] == 'database' ? 'selected' : ''; ?>>Database
                        </option>
                        <option value="video" <?php echo $product['icon'] == 'video' ? 'selected' : ''; ?>>Video</option>
                        <option value="mail" <?php echo $product['icon'] == 'mail' ? 'selected' : ''; ?>>Mail</option>
                        <option value="shield" <?php echo $product['icon'] == 'shield' ? 'selected' : ''; ?>>Shield
                        </option>
                        <option value="zap" <?php echo $product['icon'] == 'zap' ? 'selected' : ''; ?>>Zap</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Theme Color (Hex)</label>
                    <input type="color" name="color" value="<?php echo $product['color']; ?>"
                        style="height: 42px; padding: 5px;">
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Rating (1-5)</label>
                    <input type="number" step="0.1" name="rating" value="<?php echo $product['rating']; ?>" min="1"
                        max="5">
                </div>
                <div class="form-group">
                    <label>Reviews Count</label>
                    <input type="number" name="reviews" value="<?php echo $product['reviews']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Features (One per line)</label>
                <textarea name="features" required><?php echo htmlspecialchars($features_text); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>

</html>