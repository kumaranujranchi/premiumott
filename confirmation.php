<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

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
            <div class="conf-icon-box">
                <i data-lucide="shield-check"></i>
            </div>
            <h1>Order Confirmed!</h1>
            <p>Your order for <strong><?php echo $product['name']; ?></strong> has been received and is now being
                processed.</p>
        </div>

        <div class="order-summary-card">
            <div class="summary-header">
                <div class="order-id-box">
                    <span class="label">Order Number</span>
                    <span class="value"><?php echo $orderNumber; ?></span>
                </div>
                <div class="status-pill-hound">
                    <span class="pulse-dot"></span>
                    Processing
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