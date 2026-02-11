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

// Auto-refresh if Pending
if ($order['status'] == 'Pending') {
    header("Refresh: 10");
}

$orderNumber = 'ORD-' . strtoupper(dechex(time())) . strtoupper(substr(md5(rand()), 0, 4));

$currencyMap = [
    'USD' => '$',
    'INR' => '₹'
];
$symbol = $currencyMap[$product['currency'] ?? 'USD'];
?>

<div class="confirmation-page-premium">
    <div class="container small-container">
        <!-- Progress Steps -->
        <div class="order-progress">
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Selection</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Your Details</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step active">
                <div class="step-dot"><i data-lucide="package"></i></div>
                <span>Access</span>
            </div>
        </div>

        <div class="confirmation-hero">
            <div class="conf-icon-box"
                style="<?php echo $order['status'] == 'Pending' ? 'background: #FFB300;' : ''; ?>">
                <?php if ($order['status'] == 'Pending'): ?>
                    <i data-lucide="loader-2" class="spin-icon"></i>
                <?php else: ?>
                    <i data-lucide="shield-check"></i>
                <?php endif; ?>
            </div>

            <?php if ($order['status'] == 'Pending'): ?>
                <h1>Payment Verifying...</h1>
                <p>We are verifying your transaction ID
                    <strong><?php echo htmlspecialchars($order['transaction_id']); ?></strong>. <br>Please wait, this page
                    will auto-refresh.
                </p>
            <?php else: ?>
                <h1>Order Confirmed!</h1>
                <p>Your order for <strong><?php echo $product['name']; ?></strong> has been successfully processed.</p>
            <?php endif; ?>
        </div>

        <div class="order-summary-card">
            <div class="summary-header">
                <div class="order-id-box">
                    <span class="label">Order Number</span>
                    <span class="value"><?php echo $orderNumber; ?></span>
                </div>
                <div class="status-pill-hound"
                    style="<?php echo $order['status'] == 'Pending' ? 'background: rgba(255, 179, 0, 0.1); color: #F57F17;' : 'background: rgba(46, 125, 50, 0.1); color: #2E7D32;'; ?>">
                    <span class="pulse-dot"
                        style="<?php echo $order['status'] == 'Pending' ? 'background: #F57F17;' : 'background: #2E7D32;'; ?>"></span>
                    <?php echo $order['status']; ?>
                </div>
            </div>

            <div class="summary-body">
                <div class="product-info-row">
                    <div class="prod-details">
                        <h3><?php echo $product['name']; ?></h3>
                        <p><?php echo $product['license_type']; ?></p>
                    </div>
                    <div class="prod-price">
                        <?php echo $symbol; ?><?php echo $product['discounted_price']; ?>
                    </div>
                </div>
                <div class="total-row">
                    <span>Total Amount Paid</span>
                    <strong><?php echo $symbol; ?><?php echo $product['discounted_price']; ?></strong>
                </div>
            </div>
        </div>

        <div class="next-steps-premium">
            <h2>Next Steps</h2>
            <div class="steps-grid-hound">
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="mail-check"></i></div>
                    <h3>Verification</h3>
                    <p>We'll verify your payment and details within 1-2 hours.</p>
                </div>
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="message-circle"></i></div>
                    <h3>Direct Contact</h3>
                    <p>Our sales team will reach out via WhatsApp for activation.</p>
                </div>
                <div class="step-card-hound">
                    <div class="step-icon-white"><i data-lucide="key"></i></div>
                    <h3>Delivery</h3>
                    <p>Receive your unique credentials and setup guide instantly after contact.</p>
                </div>
            </div>
        </div>

        <div class="confirmation-footer">
            <a href="index.php" class="btn-primary browse-btn-lg">
                <span>Browse More Deals</span>
                <i data-lucide="arrow-right"></i>
            </a>
            <p class="support-text">Questions about your order? <a href="mailto:support@premiumottstore.com">Contact
                    Support</a></p>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>