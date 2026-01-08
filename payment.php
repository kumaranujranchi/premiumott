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

$currencyMap = [
    'USD' => '$',
    'INR' => '₹'
];
$symbol = $currencyMap[$product['currency'] ?? 'USD'];
?>

<div class="payment-page-premium">
    <div class="container">
        <!-- Progress Steps -->
        <div class="order-progress">
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Selection</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step active">
                <div class="step-dot">2</div>
                <span>Payment</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-dot">3</div>
                <span>Details & Access</span>
            </div>
        </div>

        <div class="payment-layout-hound">
            <!-- Left: Payment Form -->
            <div class="payment-form-card">
                <div class="card-header-premium">
                    <i data-lucide="lock" class="header-icon"></i>
                    <h2>Secure Checkout</h2>
                </div>

                <form class="checkout-form-premium"
                    onsubmit="event.preventDefault(); window.location.href='details.php?id=<?php echo $id; ?>';">
                    <div class="form-section-hound">
                        <label><i data-lucide="user"></i> Cardholder Name</label>
                        <div class="premium-input-box">
                            <input type="text" placeholder="John Smith" required />
                        </div>
                    </div>

                    <div class="form-section-hound">
                        <label><i data-lucide="credit-card"></i> Card Number</label>
                        <div class="premium-input-box">
                            <input type="text" placeholder="0000 0000 0000 0000" required />
                            <div class="card-brands">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg"
                                    alt="Visa">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                    alt="Mastercard">
                            </div>
                        </div>
                    </div>

                    <div class="form-row-hound">
                        <div class="form-section-hound">
                            <label>Expiry Date</label>
                            <div class="premium-input-box">
                                <input type="text" placeholder="MM/YY" required />
                            </div>
                        </div>
                        <div class="form-section-hound">
                            <label>CVC / CVV</label>
                            <div class="premium-input-box">
                                <input type="text" placeholder="123" required />
                            </div>
                        </div>
                    </div>

                    <div class="payment-badges-row">
                        <div class="secure-badge-hound">
                            <i data-lucide="shield-check"></i>
                            <span>256-bit SSL Encrypted</span>
                        </div>
                        <div class="secure-badge-hound">
                            <i data-lucide="lock"></i>
                            <span>Secure Payment Gateway</span>
                        </div>
                    </div>

                    <button type="submit" class="submit-pay-btn">
                        <span>Pay <?php echo $symbol; ?><?php echo $product['discounted_price']; ?> Now</span>
                        <i data-lucide="chevron-right"></i>
                    </button>

                    <p class="safe-note">By clicking, you agree to our Terms and 30-day money-back guarantee.</p>
                </form>
            </div>

            <!-- Right: Simple Summary -->
            <div class="order-sidebar-hound">
                <div class="summary-card-hound">
                    <h3>Order Summary</h3>

                    <div class="summary-product-item">
                        <div class="p-img-thumb" style="background: <?php echo $product['color']; ?>15">
                            <i data-lucide="package" style="color: <?php echo $product['color']; ?>"></i>
                        </div>
                        <div class="p-info-thumb">
                            <strong><?php echo $product['name']; ?></strong>
                            <span><?php echo $product['license_type']; ?></span>
                        </div>
                    </div>

                    <div class="calculation-hound">
                        <div class="calc-row">
                            <span>Subtotal</span>
                            <span><?php echo $symbol; ?><?php echo $product['original_price']; ?></span>
                        </div>
                        <div class="calc-row discount">
                            <span>Discount (<?php echo $product['discount_percent']; ?>% off)</span>
                            <span>-<?php echo $symbol; ?><?php echo $product['original_price'] - $product['discounted_price']; ?></span>
                        </div>
                        <div class="calc-divider"></div>
                        <div class="calc-row total">
                            <span>Total</span>
                            <strong><?php echo $symbol; ?><?php echo $product['discounted_price']; ?></strong>
                        </div>
                    </div>
                </div>

                <div class="guarantee-sidebar-card">
                    <i data-lucide="shield"></i>
                    <div>
                        <strong>Buyer Protection</strong>
                        <p>30 Days money back guarantee for peace of mind.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>