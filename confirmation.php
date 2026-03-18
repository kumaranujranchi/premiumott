<?php
include 'includes/db.php';
include 'includes/header.php';

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$order = null;

if ($order_id) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
}

// Fallback for legacy URLs (e.g. confirmation.php?id=19)
if (!$order && isset($_GET['id'])) {
    $prod_id = (int) $_GET['id'];
    $order = [
        'id'             => 0,
        'product_id'     => $prod_id,
        'status'         => 'Processed',
        'transaction_id' => 'N/A',
        'upi_payer_name' => '',
        'amount_entered' => ''
    ];
}

if (!$order) {
    echo "Invalid Order ID";
    exit;
}

$product = getProduct($pdo, $order['product_id']);

if (!$product) {
    echo '<div class="container" style="padding: 100px 20px; text-align: center;">
            <div class="hound-card" style="max-width: 500px; margin: 0 auto; padding: 40px;">
                <i data-lucide="search-x" style="width: 48px; height: 48px; color: var(--danger); margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 20px;">Product not found</h2>
                <a href="index.php" class="btn-primary">
                  <span>Back to Home</span>
                </a>
            </div>
          </div>';
    include 'includes/footer.php';
    exit;
}

$isPending = strtolower($order['status']) === 'pending';

$currencyMap = ['USD' => '$', 'INR' => '₹'];
$symbol = $currencyMap[$product['currency'] ?? 'USD'];
?>

<style>
.conf-verify-banner {
    background: linear-gradient(135deg, rgba(255,179,0,0.12), rgba(255,179,0,0.04));
    border: 1px solid rgba(255,179,0,0.35);
    border-radius: var(--radius-lg);
    padding: 24px 28px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 18px;
}
.conf-verify-banner .banner-icon {
    width: 44px;
    height: 44px;
    background: rgba(255,179,0,0.18);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.conf-verify-banner .banner-icon i { color: #FFB300; }
.conf-verify-banner h3  { font-size: 15px; font-weight: 700; color: #FFB300; margin: 0 0 6px; }
.conf-verify-banner p   { font-size: 13px; color: var(--text-dim); margin: 0; line-height: 1.6; }
.payment-submitted-details {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 24px;
    margin-bottom: 24px;
}
.payment-submitted-details h4 {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-dim);
    margin: 0 0 16px;
}
.submitted-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    font-size: 14px;
}
.submitted-row:last-child { border-bottom: none; }
.submitted-row .s-label  { color: var(--text-dim); }
.submitted-row .s-value  { font-weight: 700; color: var(--text-primary); font-family: 'Courier New', monospace; }
.utr-badge {
    background: rgba(61,254,2,0.08);
    color: var(--primary);
    border: 1px solid rgba(61,254,2,0.25);
    border-radius: 6px;
    padding: 3px 10px;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: .04em;
}
@keyframes spin { to { transform: rotate(360deg); } }
.spin-icon { animation: spin 1.2s linear infinite; }
</style>

<div class="confirmation-page-premium">
    <div class="container small-container">

        <!-- 4-Step Progress Bar -->
        <div class="order-progress">
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Selection</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Details</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Payment</span>
            </div>
            <div class="progress-line <?php echo $isPending ? '' : 'active'; ?>"></div>
            <div class="progress-step <?php echo $isPending ? 'active' : 'completed'; ?>">
                <div class="step-dot">
                    <?php if ($isPending): ?>
                        <i data-lucide="loader-2" class="spin-icon"></i>
                    <?php else: ?>
                        <i data-lucide="check"></i>
                    <?php endif; ?>
                </div>
                <span>Access</span>
            </div>
        </div>

        <!-- Hero -->
        <div class="confirmation-hero">
            <div class="conf-icon-box" style="<?php echo $isPending ? 'background: rgba(255,179,0,0.18);' : ''; ?>">
                <?php if ($isPending): ?>
                    <i data-lucide="loader-2" class="spin-icon" style="color:#FFB300;"></i>
                <?php else: ?>
                    <i data-lucide="shield-check"></i>
                <?php endif; ?>
            </div>

            <?php if ($isPending): ?>
                <h1 style="color:#FFB300;">Payment Under Verification</h1>
                <p>Your payment details have been submitted. Our team will verify your UPI transaction and activate your subscription within <strong>30 minutes</strong>.</p>
            <?php else: ?>
                <h1>Order Confirmed!</h1>
                <p>Your order for <strong><?php echo htmlspecialchars($product['name']); ?></strong> has been successfully processed.</p>
            <?php endif; ?>
        </div>

        <?php if ($isPending): ?>
        <!-- Verification banner -->
        <div class="conf-verify-banner">
            <div class="banner-icon">
                <i data-lucide="shield-alert" style="width:20px;height:20px;"></i>
            </div>
            <div>
                <h3>Awaiting Admin Approval</h3>
                <p>We've received your UTR details and are cross-checking the transaction. You'll receive access credentials on WhatsApp once verified.</p>
                <p style="margin-top:8px; font-size:12px;">Refreshing in <span id="timerCount" style="color:#FFB300;font-weight:700;">30</span>s</p>
            </div>
        </div>

        <!-- Submitted payment details -->
        <div class="payment-submitted-details">
            <h4>Payment Details Submitted</h4>
            <?php if (!empty($order['transaction_id'])): ?>
            <div class="submitted-row">
                <span class="s-label">UTR / Transaction ID</span>
                <span class="utr-badge"><?php echo htmlspecialchars($order['transaction_id']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($order['upi_payer_name'])): ?>
            <div class="submitted-row">
                <span class="s-label">UPI Payer Name</span>
                <span class="s-value"><?php echo htmlspecialchars($order['upi_payer_name']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($order['amount_entered'])): ?>
            <div class="submitted-row">
                <span class="s-label">Amount Submitted</span>
                <span class="s-value"><?php echo $symbol . number_format((float)$order['amount_entered'], 2); ?></span>
            </div>
            <?php endif; ?>
            <div class="submitted-row">
                <span class="s-label">Order ID</span>
                <span class="s-value">#<?php echo (int)$order['id']; ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Order Summary Card -->
        <div class="order-summary-card">
            <div class="summary-header">
                <div class="order-id-box">
                    <span class="label">Product</span>
                    <span class="value"><?php echo htmlspecialchars($product['name']); ?></span>
                </div>
                <div class="status-pill-hound"
                    style="<?php echo $isPending
                        ? 'background: rgba(255,179,0,0.1); color: #F57F17;'
                        : 'background: rgba(46,125,50,0.1); color: #2E7D32;'; ?>">
                    <span class="pulse-dot"
                        style="<?php echo $isPending ? 'background: #F57F17;' : 'background: #2E7D32;'; ?>"></span>
                    <?php echo htmlspecialchars($order['status']); ?>
                </div>
            </div>
            <div class="summary-body">
                <div class="product-info-row">
                    <div class="prod-details">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['license_type']); ?></p>
                    </div>
                    <div class="prod-price"><?php echo $symbol . $product['discounted_price']; ?></div>
                </div>
                <div class="total-row">
                    <span>Total Amount</span>
                    <strong><?php echo $symbol . $product['discounted_price']; ?></strong>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="next-steps-premium">
            <h2>What Happens Next</h2>
            <div class="steps-grid-hound">
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="search-check"></i></div>
                    <h3>Payment Verified</h3>
                    <p>Admin verifies your UTR against UPI records — usually within 30 minutes.</p>
                </div>
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="message-circle"></i></div>
                    <h3>WhatsApp Contact</h3>
                    <p>Our team reaches out on your registered WhatsApp number for activation.</p>
                </div>
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="key"></i></div>
                    <h3>Credentials Delivered</h3>
                    <p>Login details and setup guide sent directly to you instantly.</p>
                </div>
            </div>
        </div>

        <div class="confirmation-footer">
            <a href="index.php" class="btn-primary browse-btn-lg">
                <span>Browse More Deals</span>
                <i data-lucide="arrow-right"></i>
            </a>
            <?php if ($isPending): ?>
            <a href="profile.php"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:var(--radius-lg);border:1px solid var(--border);color:var(--text-dim);text-decoration:none;font-weight:600;font-size:14px;margin-left:12px;">
                <i data-lucide="user" style="width:16px;height:16px;"></i>
                <span>My Orders</span>
            </a>
            <?php endif; ?>
            <p class="support-text">Questions? <a href="mailto:support@premiumottstore.com">Contact Support</a></p>
        </div>

    </div>
</div>

<?php if ($isPending): ?>
<script>
    let seconds = 30;
    const el = document.getElementById('timerCount');
    const iv = setInterval(function () {
        seconds--;
        if (el) el.textContent = seconds;
        if (seconds <= 0) { clearInterval(iv); location.reload(); }
    }, 1000);
</script>
<?php endif; ?>

<script>lucide.createIcons();</script>

<?php include 'includes/footer.php'; ?>
