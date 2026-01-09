<?php
include 'auth_check.php';
include '../includes/db.php';

$products = getAllProducts($pdo);

// Stats for header
$total_products = count($products);
$active_products = array_reduce($products, function ($carry, $item) {
    return $carry + ($item['is_active'] ? 1 : 0);
}, 0);

$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Premium OTT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/top_nav.php'; ?>

        <div class="content-body">
            <!-- Hound Styled Stats Grid -->
            <div class="stats-grid-hound">
                <div class="hound-stat-card card-red">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo number_format($total_orders); ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="shopping-cart" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-orange">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $pending_orders; ?></div>
                        <div class="stat-label">Pending Delivery</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="clock" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-green">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $active_products; ?></div>
                        <div class="stat-label">Active Products</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="check-circle" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-blue">
                    <div class="stat-info">
                        <div class="stat-value">98%</div>
                        <div class="stat-label">Satisfaction</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="thumbs-up" style="width: 32px; height: 32px;"></i></div>
                </div>
            </div>

            <div class="hound-card">
                <div class="card-header-hound">
                    <h3 class="card-title-hound">Manage Products</h3>
                    <a href="add_product.php" class="btn-hound btn-hound-primary">
                        <i data-lucide="plus" style="width: 16px;"></i> <span>Add New</span>
                    </a>
                </div>
                <div class="card-body-hound">
                    <div style="overflow-x: auto;">
                        <table class="hound-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td>
                                            <div
                                                style="width: 50px; height: 50px; border-radius: 4px; overflow: hidden; background: #2D2D2D;">
                                                <?php if ($p['image']): ?>
                                                    <img src="../<?php echo $p['image']; ?>"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <div
                                                        style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                                        <i data-lucide="package" style="width: 20px; color: #555;"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700;"><?php echo htmlspecialchars($p['name']); ?></div>
                                            <div style="font-size: 12px; color: var(--text-dim);">
                                                <?php echo htmlspecialchars($p['tagline']); ?>
                                            </div>
                                        </td>
                                        <td><span
                                                style="font-size: 13px;"><?php echo htmlspecialchars($p['category']); ?></span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700;">
                                                <?php echo $p['currency'] == 'INR' ? '₹' : '$'; ?>
                                                <?php echo $p['discounted_price']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                style="font-size: 11px; font-weight: 800; color: <?php echo $p['is_active'] ? '#2E7D32' : '#F44336'; ?>; text-transform: uppercase;">
                                                <?php echo $p['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn-hound"
                                                    style="background: rgba(255,255,255,0.05); color: #fff; padding: 6px;"><i
                                                        data-lucide="edit-3" style="width: 16px;"></i></a>
                                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn-hound"
                                                    style="background: rgba(244,67,54,0.1); color: #F44336; padding: 6px;"
                                                    onclick="return confirm('Delete this product?')"><i
                                                        data-lucide="trash-2" style="width: 16px;"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>

</html>