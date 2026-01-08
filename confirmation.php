<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

if (!$product) {
    echo '<div class="container" style="padding: 60px 20px; text-align: center;">
            <h2>Product not found</h2>
            <a href="index.php" class="btn-primary" style="margin-top: 20px; display: inline-flex;">
              Back to Home
            </a>
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

<div class="confirmation-page">
    <div class="container">
        <div class="confirmation-content">
            <div class="success-illustration">
                <div class="success-circle">
                    <i data-lucide="check-circle-2" style="width: 64px; height: 64px;"></i>
                </div>
            </div>

            <h1 class="confirmation-title">Order Confirmed!</h1>
            <p class="confirmation-subtitle">
                Our sales team will contact you shortly to deliver your product.
            </p>

            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-label">Order Number</span>
                        <span class="order-number">
                            <?php echo $orderNumber; ?>
                        </span>
                    </div>
                    <span class="order-status">
                        <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                        Processing
                    </span>
                </div>

                <div class="order-product">
                    <div class="order-product-info">
                        <h3>
                            <?php echo $product['name']; ?>
                        </h3>
                        <span>
                            <?php echo $product['license_type']; ?>
                        </span>
                    </div>
                    <span class="order-price"><?php echo $symbol; ?>
                        <?php echo $product['discounted_price']; ?>
                    </span>
                </div>

                <div class="order-total">
                    <span>Total Paid</span>
                    <span><?php echo $symbol; ?>
                        <?php echo $product['discounted_price']; ?>
                    </span>
                </div>
            </div>

            <div class="next-steps">
                <h3>What Happens Next?</h3>
                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-icon">
                            <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div class="step-content">
                            <strong>Verification (1-2 hours)</strong>
                            <p>We verify your payment and prepare your license</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i data-lucide="message-circle" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div class="step-content">
                            <strong>Contact (Within 24 hours)</strong>
                            <p>Our sales team will reach out via WhatsApp or email</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i data-lucide="mail" style="width: 20px; height: 20px;"></i>
                        </div>
                        <div class="step-content">
                            <strong>Delivery</strong>
                            <p>You'll receive your license credentials and setup guide</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="index.php" class="browse-more-btn">
                    <span>Browse More Deals</span> <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </a>
            </div>

            <p class="support-note">
                Have questions? Contact us at <a
                    href="mailto:support@premiumottstore.com">support@premiumottstore.com</a>
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>