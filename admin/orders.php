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

$stmt = $pdo->query("SELECT o.*, p.name as product_name, p.currency FROM orders o JOIN products p ON o.product_id = p.id ORDER BY o.order_date DESC");
$orders = $stmt->fetchAll();

// Analytics
$stats = [
    'total_orders' => count($orders),
    'pending' => array_reduce($orders, function ($carry, $item) {
        return $carry + (strtolower($item['status']) == 'pending' ? 1 : 0); }, 0),
    'processed' => array_reduce($orders, function ($carry, $item) {
        return $carry + (strtolower($item['status']) == 'processed' ? 1 : 0); }, 0),
    'revenue' => array_reduce($orders, function ($carry, $item) {
        return $carry + (strtolower($item['status']) == 'processed' ? $item['total_amount'] : 0); }, 0)
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Premium OTT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'includes/top_nav.php'; ?>

        <div class="content-body">
            <div class="stats-grid-hound">
                <div class="hound-stat-card card-red">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $stats['total_orders']; ?></div>
                        <div class="stat-label">Total Visits</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="users" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-orange">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $stats['pending']; ?></div>
                        <div class="stat-label">Bounce Rate</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="refresh-cw" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-green">
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $stats['processed']; ?></div>
                        <div class="stat-label">Conversions</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="check-square" style="width: 32px; height: 32px;"></i></div>
                </div>
                <div class="hound-stat-card card-blue">
                    <div class="stat-info">
                        <div class="stat-value">₹<?php echo number_format($stats['revenue'], 0); ?></div>
                        <div class="stat-label">Revenue</div>
                    </div>
                    <div class="stat-icon"><i data-lucide="trending-up" style="width: 32px; height: 32px;"></i></div>
                </div>
            </div>

            <div class="hound-card">
                <div class="card-header-hound">
                    <h3 class="card-title-hound">Recent Orders</h3>
                </div>
                <div class="card-body-hound">
                    <div style="overflow-x: auto;">
                        <table class="hound-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>WhatsApp</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-dim);">
                                            No orders found</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($orders as $o): ?>
                                    <tr>
                                        <td style="font-size: 13px; color: var(--text-dim);">
                                            <?php echo date('d M, Y', strtotime($o['order_date'])); ?>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700;">
                                                <?php echo htmlspecialchars($o['product_name']); ?></div>
                                            <div style="font-size: 11px; font-weight: 800; color: var(--primary);">
                                                <?php echo $o['currency'] == 'INR' ? '₹' : '$'; ?>    <?php echo $o['total_amount']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600;">
                                                <?php echo htmlspecialchars($o['customer_name']); ?></div>
                                            <div style="font-size: 12px; color: var(--text-dim);">
                                                <?php echo htmlspecialchars($o['customer_email']); ?></div>
                                        </td>
                                        <td>
                                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $o['customer_whatsapp']); ?>"
                                                target="_blank"
                                                style="color: #25D366; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                                <i data-lucide="message-circle" style="width: 14px;"></i> Chat
                                            </a>
                                        </td>
                                        <td>
                                            <span
                                                style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: <?php echo strtolower($o['status']) == 'pending' ? '#FFB300' : '#2E7D32'; ?>;">
                                                <?php echo $o['status']; ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <?php if (strtolower($o['status']) == 'pending'): ?>
                                                <a href="?id=<?php echo $o['id']; ?>&status=Processed"
                                                    class="btn-hound btn-hound-primary"
                                                    style="padding: 6px 12px; font-size: 11px;">
                                                    Deliver
                                                </a>
                                            <?php else: ?>
                                                <i data-lucide="check-circle-2" style="color: #2E7D32; width: 18px;"></i>
                                            <?php endif; ?>
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