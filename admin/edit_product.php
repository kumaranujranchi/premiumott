<?php
include 'auth_check.php';
include '../includes/db.php';

$id = (int) $_GET['id'];
$product = getProduct($pdo, $id);

if (!$product) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $description = $_POST['description'];
    $original_price = $_POST['original_price'];
    $discounted_price = $_POST['discounted_price'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];
    $discount_percent = $_POST['discount_percent'];
    $category = $_POST['category'];
    $license_type = $_POST['license_type'];
    $icon = $_POST['icon'];
    $color = $_POST['color'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $currency = $_POST['currency'];
    $features = explode("\n", str_replace("\r", "", $_POST['features']));

    $image = $product['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../uploads/products/';
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0777, true);
        if ($image && file_exists('../' . $image))
            @unlink('../' . $image);
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = time() . '_' . uniqid() . '.' . $file_ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
            $image = 'uploads/products/' . $file_name;
        }
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE products SET name=?, tagline=?, description=?, original_price=?, discounted_price=?, rating=?, reviews=?, discount_percent=?, category=?, license_type=?, icon=?, color=?, image=?, currency=?, is_active=? WHERE id=?");
        $stmt->execute([$name, $tagline, $description, $original_price, $discounted_price, $rating, $reviews, $discount_percent, $category, $license_type, $icon, $color, $image, $currency, $is_active, $id]);
        $pdo->prepare("DELETE FROM product_features WHERE product_id = ?")->execute([$id]);
        $stmt = $pdo->prepare("INSERT INTO product_features (product_id, feature_text) VALUES (?, ?)");
        foreach ($features as $feature) {
            if (trim($feature))
                $stmt->execute([$id, trim($feature)]);
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
    <title>Edit Product - Premium OTT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/top_nav.php'; ?>

        <div class="content-body">
            <div class="hound-card" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header-hound">
                    <h3 class="card-title-hound">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h3>
                    <a href="index.php" class="btn-hound" style="background: rgba(255,255,255,0.05); color: #fff;">
                        <i data-lucide="x" style="width: 16px;"></i> Cancel
                    </a>
                </div>
                <div class="card-body-hound">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-hound-group">
                            <label>Product Banner Image</label>
                            <div class="upload-area" onclick="document.getElementById('image-input').click()"
                                style="background: var(--bg-input); border: 2px dashed var(--border); padding: 40px; text-align: center; border-radius: 4px; cursor: pointer;">
                                <?php if ($product['image']): ?>
                                    <div id="image-preview">
                                        <img id="preview-img" src="../<?php echo $product['image']; ?>"
                                            style="max-height: 200px; border-radius: 4px; margin: 0 auto; display: block;">
                                        <p style="margin-top: 10px; font-size: 11px; color: var(--text-dim);">Click to
                                            replace current image</p>
                                    </div>
                                    <div id="upload-prompt" style="display: none;">
                                        <i data-lucide="image"
                                            style="width: 48px; height: 48px; color: var(--text-dim); margin-bottom: 10px;"></i>
                                        <p style="margin: 0; font-weight: 600;">Click to upload banner</p>
                                    </div>
                                <?php else: ?>
                                    <div id="image-preview" style="display: none;">
                                        <img id="preview-img"
                                            style="max-height: 200px; border-radius: 4px; margin: 0 auto; display: block;">
                                    </div>
                                    <div id="upload-prompt">
                                        <i data-lucide="image"
                                            style="width: 48px; height: 48px; color: var(--text-dim); margin-bottom: 10px;"></i>
                                        <p style="margin: 0; font-weight: 600;">Click to upload banner</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" id="image-input" style="display: none;"
                                    onchange="previewImage(this)">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-hound-group" style="grid-column: span 2;">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-hound-control"
                                    value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>

                            <div class="form-hound-group">
                                <label>Tagline</label>
                                <input type="text" name="tagline" class="form-hound-control"
                                    value="<?php echo htmlspecialchars($product['tagline']); ?>">
                            </div>

                            <div class="form-hound-group">
                                <label>Category</label>
                                <select name="category" class="form-hound-control">
                                    <option value="Streaming" <?php echo $product['category'] == 'Streaming' ? 'selected' : ''; ?>>Streaming</option>
                                    <option value="Education" <?php echo $product['category'] == 'Education' ? 'selected' : ''; ?>>Education</option>
                                    <option value="Productivity" <?php echo $product['category'] == 'Productivity' ? 'selected' : ''; ?>>Productivity</option>
                                    <option value="Gaming" <?php echo $product['category'] == 'Gaming' ? 'selected' : ''; ?>>Gaming</option>
                                    <option value="Design" <?php echo $product['category'] == 'Design' ? 'selected' : ''; ?>>Design</option>
                                </select>
                            </div>

                            <div class="form-hound-group" style="grid-column: span 2;">
                                <label>Description</label>
                                <textarea name="description" class="form-hound-control"
                                    rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>

                            <div class="form-hound-group">
                                <label>Currency</label>
                                <select name="currency" class="form-hound-control">
                                    <option value="USD" <?php echo $product['currency'] == 'USD' ? 'selected' : ''; ?>>USD
                                        ($)</option>
                                    <option value="INR" <?php echo $product['currency'] == 'INR' ? 'selected' : ''; ?>>INR
                                        (₹)</option>
                                </select>
                            </div>

                            <div class="form-hound-group">
                                <label>License Type</label>
                                <input type="text" name="license_type" class="form-hound-control"
                                    value="<?php echo htmlspecialchars($product['license_type']); ?>">
                            </div>

                            <div class="form-hound-group">
                                <label>Original Price</label>
                                <input type="number" step="0.01" name="original_price" class="form-hound-control"
                                    value="<?php echo $product['original_price']; ?>" required>
                            </div>

                            <div class="form-hound-group">
                                <label>Discounted Price</label>
                                <input type="number" step="0.01" name="discounted_price" class="form-hound-control"
                                    value="<?php echo $product['discounted_price']; ?>" required>
                            </div>

                            <div class="form-hound-group">
                                <label>Discount %</label>
                                <input type="number" name="discount_percent" class="form-hound-control"
                                    value="<?php echo $product['discount_percent']; ?>">
                            </div>

                            <div class="form-hound-group">
                                <label>Rating (1-5)</label>
                                <input type="number" step="0.1" name="rating" class="form-hound-control"
                                    value="<?php echo $product['rating']; ?>">
                            </div>

                            <div class="form-hound-group">
                                <label>Theme Color</label>
                                <input type="color" name="color" value="<?php echo $product['color']; ?>"
                                    class="form-hound-control" style="height: 45px; padding: 5px;">
                            </div>

                            <div class="form-hound-group">
                                <label>Status</label>
                                <div style="display: flex; align-items: center; gap: 10px; height: 45px;">
                                    <input type="checkbox" name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?> style="width: 18px; height: 18px; accent-color: var(--stat-red);">
                                    <span style="font-size: 14px; font-weight: 600;">Active & Visible</span>
                                </div>
                            </div>

                            <div class="form-hound-group" style="grid-column: span 2;">
                                <label>Features (One per line)</label>
                                <textarea name="features" class="form-hound-control"
                                    rows="5"><?php echo implode("\n", $product['features']); ?></textarea>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn-hound btn-hound-primary"
                                style="width: 100%; justify-content: center; padding: 15px; font-size: 15px;">
                                <i data-lucide="save" style="width: 18px;"></i> <span>Save Product Changes</span>
                            </button>
                        </div>
                        <input type="hidden" name="icon" value="package">
                        <input type="hidden" name="reviews" value="<?php echo $product['reviews']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </main>

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