<?php
include 'includes/db.php';
include 'includes/user_auth.php';
requireUserLogin();
include 'includes/header.php';

$userId    = (int) $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'];

// ── Fetch user row ──────────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login');
    exit;
}

// ── Handle profile update ───────────────────────────────────────────────────
$updateSuccess = false;
$updateError   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $newName = trim($_POST['name'] ?? '');
    $newPass = $_POST['new_password']  ?? '';
    $curPass = $_POST['current_password'] ?? '';

    if (!$newName) {
        $updateError = 'Name cannot be empty.';
    } elseif ($newPass && !$curPass) {
        $updateError = 'Enter your current password to set a new one.';
    } elseif ($newPass && !password_verify($curPass, $user['password'])) {
        $updateError = 'Current password is incorrect.';
    } elseif ($newPass && strlen($newPass) < 8) {
        $updateError = 'New password must be at least 8 characters.';
    } else {
        if ($newPass) {
            $hash = password_hash($newPass, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE users SET name=?, password=? WHERE id=?")->execute([$newName, $hash, $userId]);
        } else {
            $pdo->prepare("UPDATE users SET name=? WHERE id=?")->execute([$newName, $userId]);
        }
        $_SESSION['user_name'] = $newName;
        $user['name']          = $newName;
        $updateSuccess         = true;
    }
}

// ── Fetch purchase history ──────────────────────────────────────────────────
$stmt = $pdo->prepare("
    SELECT o.*, p.name AS product_name, p.license_type, p.currency, p.color, p.image, p.category
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.customer_email = ?
    ORDER BY o.order_date DESC
");
$stmt->execute([$userEmail]);
$orders = $stmt->fetchAll();

// ── Stats ───────────────────────────────────────────────────────────────────
$totalSpent    = 0;
$totalOrders   = count($orders);
$processedCount = 0;
$pendingCount   = 0;
foreach ($orders as $o) {
    $totalSpent += $o['total_amount'];
    if (strtolower($o['status']) === 'processed') $processedCount++;
    if (strtolower($o['status']) === 'pending')   $pendingCount++;
}

$currencyMap = ['USD' => '$', 'INR' => '₹'];
$memberSince = date('F Y', strtotime($user['created_at']));
$initials    = strtoupper(substr($user['name'], 0, 1));
?>

<style>
/* ── Profile page ───────────────────────────────────────── */
.profile-page { padding: 48px 0 80px; }
.profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 28px;
    align-items: start;
}

/* ── Sidebar card ───────────────────────────────────────── */
.profile-sidebar {}
.profile-avatar-card {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px 24px;
    text-align: center;
    margin-bottom: 20px;
}
.avatar-circle {
    width: 80px; height: 80px;
    background: var(--primary);
    color: #000;
    font-size: 32px; font-weight: 800;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.profile-display-name { font-size: 20px; font-weight: 800; margin-bottom: 4px; }
.profile-email { font-size: 13px; color: var(--text-muted); word-break: break-all; margin-bottom: 16px; }
.profile-since { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); background: var(--bg-tertiary); padding: 5px 12px; border-radius: 20px; }

.profile-stats-card {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 24px;
}
.stat-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border); }
.stat-item:last-child { border-bottom: none; }
.stat-label { font-size: 13px; color: var(--text-muted); }
.stat-val { font-size: 16px; font-weight: 800; color: var(--text-primary); }
.stat-val.green { color: var(--primary); }
.stat-val.orange { color: #F59E0B; }

/* ── Main column ────────────────────────────────────────── */
.profile-main {}

/* Edit form card */
.profile-form-card {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px;
    margin-bottom: 28px;
}
.pf-card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.pf-card-title { font-size: 17px; font-weight: 800; }
.pf-card-icon { width: 36px; height: 36px; background: var(--primary-light); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--primary); }
.pf-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.pf-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
.pf-group label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; }
.pf-group input {
    width: 100%; background: #1a1a1a; border: 1px solid var(--border);
    border-radius: var(--radius-sm); color: var(--text-primary);
    font-size: 14px; font-family: inherit; padding: 11px 14px; outline: none;
    transition: border-color .2s;
}
.pf-group input:focus { border-color: var(--primary); }
.pf-group input[readonly] { opacity: .55; cursor: not-allowed; }
.pf-divider { height: 1px; background: var(--border); margin: 20px 0; }
.pf-section-label { font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 16px; }
.pf-alert-success { background: rgba(61,254,2,.08); color: var(--primary); border: 1px solid rgba(61,254,2,.2); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
.pf-alert-error   { background: rgba(239,68,68,.08); color: #f87171; border: 1px solid rgba(239,68,68,.2); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
.pf-save-btn { margin-top: 8px; }

/* Orders table */
.orders-card {
    background: var(--bg-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.orders-card-header { padding: 20px 28px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.orders-table { width: 100%; border-collapse: collapse; }
.orders-table th { padding: 12px 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); text-align: left; background: var(--bg-secondary); }
.orders-table td { padding: 16px 20px; border-top: 1px solid var(--border); vertical-align: middle; }
.order-product-thumb { display: flex; align-items: center; gap: 14px; }
.order-product-icon { width: 40px; height: 40px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
.order-product-icon img { width: 100%; height: 100%; object-fit: cover; }
.order-product-name { font-weight: 700; font-size: 14px; }
.order-product-lic  { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.order-status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
.status-pending   { background: rgba(245,158,11,.12); color: #F59E0B; }
.status-processed { background: rgba(61,254,2,.10); color: var(--primary); }
.status-cancelled { background: rgba(239,68,68,.10); color: #f87171; }
.order-amount { font-weight: 800; font-size: 15px; }
.order-empty { text-align: center; padding: 60px 20px; color: var(--text-muted); }
.order-empty i { width: 48px; height: 48px; margin-bottom: 14px; color: var(--border); display: block; margin-left: auto; margin-right: auto; }
.order-utr { font-size: 12px; color: var(--text-muted); font-family: monospace; background: var(--bg-tertiary); padding: 2px 8px; border-radius: 4px; display: inline-block; margin-top: 4px; }

/* Responsive */
@media (max-width: 900px) {
    .profile-grid { grid-template-columns: 1fr; }
    .pf-grid-3 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .pf-grid-2, .pf-grid-3 { grid-template-columns: 1fr; }
    .orders-table td:nth-child(3), .orders-table th:nth-child(3) { display: none; }
}
</style>

<div class="profile-page">
    <div class="container">

        <div class="profile-grid">
            <!-- ──────── Sidebar ──────── -->
            <aside class="profile-sidebar">
                <div class="profile-avatar-card">
                    <div class="avatar-circle"><?php echo $initials; ?></div>
                    <div class="profile-display-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    <span class="profile-since">
                        <i data-lucide="calendar" style="width:12px;height:12px;"></i>
                        Member since <?php echo $memberSince; ?>
                    </span>
                </div>

                <div class="profile-stats-card">
                    <div class="stat-item">
                        <span class="stat-label">Total Orders</span>
                        <span class="stat-val"><?php echo $totalOrders; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Completed</span>
                        <span class="stat-val green"><?php echo $processedCount; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Pending</span>
                        <span class="stat-val orange"><?php echo $pendingCount; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total Spent</span>
                        <span class="stat-val">₹<?php echo number_format($totalSpent, 0); ?></span>
                    </div>
                </div>
            </aside>

            <!-- ──────── Main ──────── -->
            <div class="profile-main">

                <!-- Edit Profile Form -->
                <div class="profile-form-card">
                    <div class="pf-card-header">
                        <div class="pf-card-icon"><i data-lucide="user-cog" style="width:18px;height:18px;"></i></div>
                        <span class="pf-card-title">Edit Profile</span>
                    </div>

                    <?php if ($updateSuccess): ?>
                        <div class="pf-alert-success"><i data-lucide="check-circle-2" style="width:16px;height:16px;"></i> Profile updated successfully!</div>
                    <?php endif; ?>
                    <?php if ($updateError): ?>
                        <div class="pf-alert-error"><i data-lucide="alert-circle" style="width:16px;height:16px;"></i> <?php echo htmlspecialchars($updateError); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">

                        <p class="pf-section-label">Account Information</p>
                        <div class="pf-grid-2">
                            <div class="pf-group">
                                <label><i data-lucide="user" style="width:12px;height:12px;"></i> Full Name</label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="pf-group">
                                <label><i data-lucide="mail" style="width:12px;height:12px;"></i> Email Address</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="pf-group">
                                <label><i data-lucide="calendar" style="width:12px;height:12px;"></i> Member Since</label>
                                <input type="text" value="<?php echo $memberSince; ?>" readonly>
                            </div>
                            <div class="pf-group">
                                <label><i data-lucide="shopping-bag" style="width:12px;height:12px;"></i> Total Orders</label>
                                <input type="text" value="<?php echo $totalOrders; ?>" readonly>
                            </div>
                        </div>

                        <div class="pf-divider"></div>
                        <p class="pf-section-label">Change Password <span style="font-weight:400;color:var(--text-muted);">(leave blank to keep current)</span></p>
                        <div class="pf-grid-3">
                            <div class="pf-group">
                                <label><i data-lucide="lock" style="width:12px;height:12px;"></i> Current Password</label>
                                <input type="password" name="current_password" placeholder="••••••••">
                            </div>
                            <div class="pf-group">
                                <label><i data-lucide="lock" style="width:12px;height:12px;"></i> New Password</label>
                                <input type="password" name="new_password" placeholder="Min. 8 characters" minlength="8">
                            </div>
                            <div style="display:flex;align-items:flex-end;">
                                <button type="submit" class="btn-primary pf-save-btn" style="width:100%;padding:12px;">
                                    <span>Save Changes</span>
                                    <i data-lucide="save" style="width:16px;height:16px;"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Purchase History -->
                <div class="orders-card">
                    <div class="orders-card-header">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="pf-card-icon"><i data-lucide="receipt" style="width:18px;height:18px;"></i></div>
                            <span class="pf-card-title">Purchase History</span>
                        </div>
                        <span style="font-size:13px;color:var(--text-muted);"><?php echo $totalOrders; ?> order<?php echo $totalOrders != 1 ? 's' : ''; ?></span>
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="order-empty">
                            <i data-lucide="package-open"></i>
                            <p style="font-weight:700;font-size:16px;margin-bottom:6px;">No orders yet</p>
                            <p style="font-size:14px;margin-bottom:20px;">You haven't purchased anything yet.</p>
                            <a href="index" class="btn-primary" style="display:inline-flex;">
                                <span>Browse Deals</span>
                                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x:auto;">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $o):
                                        $sym = $currencyMap[$o['currency'] ?? 'INR'] ?? '₹';
                                        $statusClass = 'status-' . strtolower($o['status']);
                                        $imgPath = $o['image'] ?? '';
                                        $hasImg  = $imgPath && file_exists(__DIR__ . '/' . ltrim($imgPath, '/'));
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="order-product-thumb">
                                                <div class="order-product-icon" style="background:<?php echo $o['color']; ?>18;">
                                                    <?php if ($hasImg): ?>
                                                        <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="">
                                                    <?php else: ?>
                                                        <i data-lucide="package" style="width:20px;height:20px;color:<?php echo $o['color']; ?>;"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="order-product-name"><?php echo htmlspecialchars($o['product_name']); ?></div>
                                                    <div class="order-product-lic"><?php echo htmlspecialchars($o['license_type']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size:13px;color:var(--text-muted);white-space:nowrap;">
                                            <?php echo date('d M Y', strtotime($o['order_date'])); ?>
                                        </td>
                                        <td>
                                            <?php if ($o['transaction_id']): ?>
                                                <span class="order-utr"><?php echo htmlspecialchars($o['transaction_id']); ?></span>
                                            <?php else: ?>
                                                <span style="font-size:12px;color:var(--text-muted);">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="order-amount"><?php echo $sym . number_format($o['total_amount'], 0); ?></span>
                                        </td>
                                        <td>
                                            <span class="order-status-badge <?php echo $statusClass; ?>">
                                                <?php if (strtolower($o['status']) === 'processed'): ?>
                                                    <i data-lucide="check-circle-2" style="width:12px;height:12px;"></i>
                                                <?php elseif (strtolower($o['status']) === 'pending'): ?>
                                                    <i data-lucide="clock" style="width:12px;height:12px;"></i>
                                                <?php else: ?>
                                                    <i data-lucide="x-circle" style="width:12px;height:12px;"></i>
                                                <?php endif; ?>
                                                <?php echo $o['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div><!-- /profile-main -->
        </div><!-- /profile-grid -->
    </div>
</div>

<script>lucide.createIcons();</script>

<?php include 'includes/footer.php'; ?>
