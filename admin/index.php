<?php
include '../includes/db.php';

$products = getAllProducts($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium OTT Store - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #3B82F6;
            --bg: #F9FAFB;
            --card: #FFFFFF;
            --text: #111827;
            --text-light: #6B7280;
            --border: #E5E7EB;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: #EF4444;
            color: white;
        }

        .btn-edit {
            background: #F59E0B;
            color: white;
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: #F3F4F6;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-light);
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Premium OTT Store Admin</h1>
            <a href="add_product.php" class="btn btn-primary">
                <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Add New Product
            </a>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-icon"
                                        style="background: <?php echo $p['color']; ?>15; color: <?php echo $p['color']; ?>;">
                                        <i data-lucide="package" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">
                                            <?php echo htmlspecialchars($p['name']); ?>
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-light);">
                                            <?php echo htmlspecialchars($p['tagline']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($p['category']); ?>
                            </td>
                            <td>
                                <div style="font-weight: 600;">$
                                    <?php echo $p['discounted_price']; ?>
                                </div>
                                <div style="font-size: 12px; color: var(--text-light); text-decoration: line-through;">$
                                    <?php echo $p['original_price']; ?>
                                </div>
                            </td>
                            <td class="actions">
                                <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-edit"
                                    style="padding: 6px 12px; font-size: 14px;">
                                    <i data-lucide="edit-3" style="width: 16px; height: 16px;"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-danger"
                                    style="padding: 6px 12px; font-size: 14px;" onclick="return confirm('Are you sure?')">
                                    <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>