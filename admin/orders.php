<?php
include 'auth_check.php';
include '../includes/db.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int) $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location: orders.php");
    exit;
}

$stats = [
    'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'pending'")->fetchColumn(),
    'processed' => $pdo->query("SELECT COUNT(*) FROM orders WHERE LOWER(status) = 'processed'")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(total_amount) FROM orders WHERE LOWER(status) = 'processed'")->fetchColumn() ?: 0
];

$stmt = $pdo->query("SELECT o.*, p.name as product_name, p.currency FROM orders o JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders & Analytics - Premium OTT Store Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <div class="admin-container">
        <header class="admin-header">
            <div>
                <h1>Sales & Analytics</h1>
                <p style="color: var(--text-dim); margin-top: 4px; font-size: 14px;">Track your performance and manage
                    customer deliveries</p>
            </div>

            <div class="nav-pills">
                <a href="index.php" class="nav-pill">Products</a>
                <a href="orders.php" class="nav-pill active">Orders</a>
            </div>

            <a href="index.php" class="btn btn-outline">
                <i data-lucide="arrow-left"></i> Back to Products
            </a>
        </header>

        <div class="stats-row">
            <div class="stat-item">
                <span class="stat-label">Total Volume</span>
                <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Pending Delivery</span>
                <div class="stat-value" style="color: #F59E0B;"><?php echo $stats['pending']; ?></div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Success Rate</span>
                <div class="stat-value" style="color: #10B981;"><?php echo $stats['processed']; ?></div>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total Revenue</span>
                <div class="stat-value" style="color: var(--primary);">
                    ₹<?php echo number_format($stats['revenue'], 0); ?></div>
            </div>
        </div>

        <div class="glass-card">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Customer Details</th>
                            <th>WhatsApp</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 60px; color: var(--text-dim);">No orders
                                    found yet</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td style="font-size: 13px; color: var(--text-dim);">
                                    <?php echo date('d M, Y', strtotime($o['order_date'])); ?><br>
                                    <small><?php echo date('H:i', strtotime($o['order_date'])); ?></small>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #fff;">
                                        <?php echo htmlspecialchars($o['product_name']); ?>
                                    </div>
                                    <div
                                        style="font-size: 11px; color: var(--primary); font-weight: 800; text-transform: uppercase;">
                                        Paid:
                                        <?php echo $o['currency'] == 'INR' ? '₹' : '$'; ?>     <?php echo $o['total_amount']; ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">
                                        <?php echo htmlspecialchars($o['customer_name'] ?: 'Guest User'); ?>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-dim);">
                                        <?php echo htmlspecialchars($o['customer_email']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($o['customer_whatsapp']): ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $o['customer_whatsapp']); ?>"
                                            target="_blank" class="btn btn-outline"
                                            style="padding: 6px 12px; font-size: 12px; border-color: rgba(37, 211, 102, 0.3); color: #25D366;">
                                            <i data-lucide="message-circle" style="width: 14px;"></i> Chat
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-dim);">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill status-<?php echo strtolower($o['status']); ?>">
                                        <?php echo $o['status']; ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <?php if (strtolower($o['status']) == 'pending'): ?>
                                        <a href="?id=<?php echo $o['id']; ?>&status=Processed" class="btn btn-primary"
                                            style="padding: 8px 16px; font-size: 12px;">
                                            Deliver
                                        </a>
                                    <?php else: ?>
                                        <i data-lucide="check-circle-2" style="color: var(--primary); width: 20px;"></i>
                                    <?php endif; ?>
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