<?php
include 'auth_check.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discounted_price = $_POST['discounted_price'];
    $rating = $_POST['rating'] ?: 5.0;
    $reviews = $_POST['reviews'] ?: 0;
    $discount_percent = $_POST['discount_percent'];
    $category = $_POST['category'];
    $license_type = $_POST['license_type'];
    $icon = $_POST['icon'];
    $color = $_POST['color'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $currency = $_POST['currency'] ?: 'USD';
    $features = explode("\n", str_replace("\r", "", $_POST['features']));

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../uploads/products/';
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0777, true);
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
            $image = 'uploads/products/' . $file_name;
        }
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO products (name, tagline, description, original_price, discounted_price, rating, reviews, discount_percent, category, license_type, icon, color, image, currency, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $tagline, $description, $original_price, $discounted_price, $rating, $reviews, $discount_percent, $category, $license_type, $icon, $color, $image, $currency, $is_active]);
        $productId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO product_features (product_id, feature_text) VALUES (?, ?)");
        foreach ($features as $feature) {
            if (trim($feature))
                $stmt->execute([$productId, trim($feature)]);
        }
        $pdo->commit();
        header("Location: index.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product - Premium OTT Store Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <div class="admin-container" style="max-width: 900px;">
        <header class="admin-header">
            <div>
                <h1>Create New Product</h1>
                <p style="color: var(--text-dim); margin-top: 4px; font-size: 14px;">Define a new premium subscription
                    plan for your store</p>
            </div>
            <a href="index.php" class="btn btn-outline">
                <i data-lucide="x"></i> Cancel
            </a>
        </header>

        <form action="" method="POST" enctype="multipart/form-data" class="glass-card">
            <div class="form-grid">
                <div class="form-group full">
                    <label>Product Image</label>
                    <div class="upload-area" onclick="document.getElementById('image-input').click()">
                        <div id="image-preview" style="display: none; margin-bottom: 15px;">
                            <img id="preview-img"
                                style="max-height: 200px; border-radius: 12px; margin: 0 auto; display: block;">
                        </div>
                        <div id="upload-prompt">
                            <i data-lucide="image"
                                style="width: 48px; height: 48px; color: var(--text-dim); margin-bottom: 10px;"></i>
                            <p style="margin: 0; font-weight: 600;">Click to upload banner image</p>
                            <p style="font-size: 12px; color: var(--text-dim); margin-top: 4px;">Recommended: 800x450px
                                (16:9)</p>
                        </div>
                        <input type="file" name="image" id="image-input" style="display: none;"
                            onchange="previewImage(this)">
                    </div>
                </div>

                <div class="form-group full">
                    <label>Product Name</label>
                    <input type="text" name="name" placeholder="e.g. Netflix Premium UHD" required>
                </div>

                <div class="form-group">
                    <label>Tagline (Short Highlight)</label>
                    <input type="text" name="tagline" placeholder="e.g. 1-Year Shared Account">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="Streaming">Streaming</option>
                        <option value="Education">Education</option>
                        <option value="Productivity">Productivity</option>
                        <option value="Gaming">Gaming</option>
                        <option value="Design">Design</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Detailed product description..."></textarea>
                </div>

                <div class="form-group">
                    <label>Currency</label>
                    <select name="currency">
                        <option value="USD">USD ($)</option>
                        <option value="INR">INR (₹)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>License Type</label>
                    <input type="text" name="license_type" placeholder="e.g. 1 Year Access">
                </div>

                <div class="form-group">
                    <label>Original Price</label>
                    <input type="number" step="0.01" name="original_price" required>
                </div>

                <div class="form-group">
                    <label>Discounted Price</label>
                    <input type="number" step="0.01" name="discounted_price" required>
                </div>

                <div class="form-group">
                    <label>Discount Percentage (%)</label>
                    <input type="number" name="discount_percent" placeholder="e.g. 50">
                </div>

                <div class="form-group">
                    <label>Rating (1-5)</label>
                    <input type="number" step="0.1" name="rating" value="4.9" max="5">
                </div>

                <div class="form-group">
                    <label>Theme Color</label>
                    <input type="color" name="color" value="#3DFE02" style="height: 50px; padding: 5px;">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-top: 30px;">
                        <input type="checkbox" name="is_active" checked style="width: auto;">
                        Active & Published
                    </label>
                </div>

                <div class="form-group full">
                    <label>Features (One per line)</label>
                    <textarea name="features" rows="6" placeholder="Feature 1
Feature 2
Feature 3..."></textarea>
                </div>

                <div class="form-group full" style="padding-top: 20px;">
                    <button type="submit" class="btn btn-primary"
                        style="width: 100%; justify-content: center; padding: 16px;">
                        Create Product Plan
                    </button>
                </div>
            </div>
            <!-- Hidden inputs for backward compatibility if needed -->
            <input type="hidden" name="icon" value="package">
        </form>
    </div>

    <script>
        lucide.createIcons();
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                    document.getElementById('upload-prompt').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>