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
?>

<div class="payment-page">
    <div class="container">
        <a href="product.php?id=<?php echo $id; ?>" class="back-link">
            <i data-lucide="chevron-left" style="width: 20px; height: 20px;"></i>
            Back to product
        </a>

        <div class="payment-grid">
            <div class="payment-form-section">
                <h1 class="payment-title">Complete Your Purchase</h1>

                <form class="payment-form" id="paymentForm" onsubmit="return handlePayment(event)">
                    <h3>Payment Details</h3>

                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" name="cardholderName" id="cardholderName" placeholder="John Smith"
                            required />
                        <span class="error-msg" id="err-cardholderName"></span>
                    </div>

                    <div class="form-group">
                        <label>Card Number</label>
                        <div class="card-input-wrapper">
                            <input type="text" name="cardNumber" id="cardNumber" placeholder="1234 5678 9012 3456"
                                required />
                            <i data-lucide="credit-card" style="width: 20px; height: 20px;" class="card-icon"></i>
                        </div>
                        <span class="error-msg" id="err-cardNumber"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="text" name="expiry" id="expiry" placeholder="MM/YY" required />
                            <span class="error-msg" id="err-expiry"></span>
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="text" name="cvv" id="cvv" placeholder="123" required />
                            <span class="error-msg" id="err-cvv"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="email" placeholder="you@example.com" required />
                        <span class="error-msg" id="err-email"></span>
                    </div>

                    <div class="security-badges">
                        <div class="badge">
                            <i data-lucide="shield" style="width: 16px; height: 16px;"></i>
                            256-bit SSL
                        </div>
                        <div class="badge">
                            <i data-lucide="lock" style="width: 16px; height: 16px;"></i>
                            Secure Payment
                        </div>
                    </div>

                    <div class="delivery-reminder">
                        <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                        <div>
                            <strong>Manual Delivery Notice</strong>
                            <p>This product will be delivered manually by our sales team within 24-48 hours after
                                payment verification.</p>
                        </div>
                    </div>

                    <button type="submit" class="pay-btn">
                        Pay $
                        <?php echo $product['discounted_price']; ?>
                    </button>
                </form>
            </div>

            <div class="order-summary">
                <h3>Order Summary</h3>

                <div class="summary-product">
                    <div class="summary-product-info">
                        <h4>
                            <?php echo $product['name']; ?>
                        </h4>
                        <span class="license-tag">
                            <?php echo $product['license_type']; ?>
                        </span>
                    </div>
                </div>

                <div class="summary-line">
                    <span>Original Price</span>
                    <span class="original-price">$
                        <?php echo $product['original_price']; ?>
                    </span>
                </div>

                <div class="summary-line discount">
                    <span>Discount (
                        <?php echo $product['discount_percent']; ?>% off)
                    </span>
                    <span>-$
                        <?php echo $product['original_price'] - $product['discounted_price']; ?>
                    </span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-line total">
                    <span>Total</span>
                    <span>$
                        <?php echo $product['discounted_price']; ?>
                    </span>
                </div>

                <div class="guarantee-box">
                    <i data-lucide="shield" style="width: 20px; height: 20px;"></i>
                    <div>
                        <strong>30-Day Money Back Guarantee</strong>
                        <p>Not satisfied? Get a full refund, no questions asked.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handlePayment(e) {
        e.preventDefault();
        // Simple client side simulation
        window.location.href = 'details.php?id=<?php echo $id; ?>';
        return false;
    }
</script>

<?php include 'includes/footer.php'; ?>