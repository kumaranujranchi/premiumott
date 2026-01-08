<?php
include '../includes/db.php';

// Handle status update
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int) $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: orders.php");
    exit;
}

// Fetch analytics
$stats = [
    'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'pending'")->fetchColumn(),
    'processed' => $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'processed'")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE LOWER(status) = 'processed'")->fetchColumn() ?: 0
];

// Fetch orders with product names
$stmt = $pdo->query("SELECT o.*, p.name as product_name, p.currency FROM orders o JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll();

$currencyMap = ['USD' => '$', 'INR' => '₹'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders & Analytics - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-top: 8px;
            color: var(--text);
        }

        .card {
            background: var(--card);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
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
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-processed {
            background: #D1FAE5;
            color: #065F46;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-process {
            background: var(--primary);
            color: #000;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #6B7280;
            font-weight: 600;
        }

        .nav-links a.active {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Orders & Analytics</h1>
            <div class="nav-links">
                <a href="index.php">Products</a>
                <a href="orders.php" class="active">Orders</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pending Orders</div>
                <div class="stat-value" style="color: #F59E0B;"><?php echo $stats['pending']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Processed Orders</div>
                <div class="stat-value" style="color: #10B981;"><?php echo $stats['processed']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Revenue (Processed)</div>
                <div class="stat-value">₹<?php echo number_format($stats['revenue'], 2); ?></div>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">No orders found</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td><?php echo date('d M, Y H:i', strtotime($o['order_date'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($o['product_name']); ?></strong></td>
                            <td>
                                <div><?php echo htmlspecialchars($o['customer_name']); ?></div>
                                <div style="font-size: 12px; color: #6B7280;">
                                    <?php echo htmlspecialchars($o['customer_email']); ?></div>
                            </td>
                            <td>
                                <div><a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $o['customer_whatsapp']); ?>"
                                        target="_blank"
                                        style="color: #25D366; text-decoration: none; font-weight: 600;">WhatsApp</a></div>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($o['status']); ?>">
                                    <?php echo $o['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (strtolower($o['status']) == 'pending'): ?>
                                    <a href="?id=<?php echo $o['id']; ?>&status=Processed" class="btn btn-process">Mark
                                        Processed</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>