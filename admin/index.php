<?php
include 'auth_check.php';
include '../includes/db.php';
$products = getAllProducts($pdo);
?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Premium OTT Store Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <div class="admin-container">
        <header class="admin-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p style="color: var(--text-dim); margin-top: 4px; font-size: 14px;">Manage your premium products and
                    store offerings</p>
            </div>

            <div class="nav-pills">
                <a href="index.php" class="nav-pill active">Products</a>
                <a href="orders.php" class="nav-pill">Orders</a>
            </div>

            <a href="add_product.php" class="btn btn-primary">
                <i data-lucide="plus"></i> Add New Product
            </a>
        </header>

        <div class="glass-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product Info</th>
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
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <div
                                            style="width: 48px; height: 48px; border-radius: 10px; background: <?php echo $p['color']; ?>20; display: flex; align-items: center; justify-content: center; color: <?php echo $p['color']; ?>;">
                                            <?php if ($p['image']): ?>
                                                <img src="../<?php echo $p['image']; ?>"
                                                    style="width: 100%; height: 100%; border-radius: 10px; object-fit: cover;">
                                            <?php else: ?>
                                                <i data-lucide="package" style="width: 24px; height: 24px;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; font-size: 15px; margin-bottom: 2px;">
                                                <?php echo htmlspecialchars($p['name']); ?>
                                            </div>
                                            <div style="font-size: 12px; color: var(--text-dim);">
                                                <?php echo htmlspecialchars($p['tagline']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        style="font-size: 13px; font-weight: 500; color: var(--text-dim);"><?php echo htmlspecialchars($p['category']); ?></span>
                                </td>
                                <td>
                                    <div style="font-weight: 700; font-size: 15px;">
                                        <?php echo $p['currency'] == 'INR' ? '₹' : '$'; ?>
                                        <?php echo $p['discounted_price']; ?>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-dim); text-decoration: line-through;">
                                        <?php echo $p['currency'] == 'INR' ? '₹' : '$'; ?>
                                        <?php echo $p['original_price']; ?>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="status-pill <?php echo $p['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $p['is_active'] ? 'Active' : 'Hidden'; ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline"
                                            style="padding: 8px 12px;">
                                            <i data-lucide="edit-3" style="width: 16px;"></i>
                                        </a>
                                        <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline"
                                            style="padding: 8px 12px; color: #EF4444;"
                                            onclick="return confirm('Silently delete this product?')">
                                            <i data-lucide="trash-2" style="width: 16px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>