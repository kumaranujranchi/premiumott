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
                    <i data-lucide="scan-line" class="header-icon"></i>
                    <h2>Scan & Pay</h2>
                </div>

                <div class="qr-payment-section" style="text-align: center; margin-bottom: 20px;">
                    <!-- DYNAMIC QR CODE -->
                    <!-- REPLACE 'yourupi@okaxis' WITH YOUR ACTUAL UPI ID -->
                    <?php
                    $upi_id = "yourupi@okaxis";
                    $payee_name = "PremiumOTT";
                    $amount = $product['discounted_price'];
                    $note = "Order " . $product['id']; // Or some unique order ref if available before logic
                    
                    // UPI URL Format: upi://pay?pa=UPI_ID&pn=NAME&am=AMOUNT&tn=NOTE
                    $upi_url = "upi://pay?pa={$upi_id}&pn={$payee_name}&am={$amount}&tn={$note}";
                    $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($upi_url);
                    ?>

                    <div class="qr-container"
                        style="background: white; padding: 10px; display: inline-block; border-radius: 10px;">
                        <img src="<?php echo $qr_api; ?>" alt="Scan to Pay" style="width: 200px; height: 200px;">
                    </div>

                    <p style="margin-top: 10px; font-size: 14px; color: var(--text-dim);">
                        Scan with any UPI App (GPay, PhonePe, Paytm)
                    </p>
                    <div style="font-size: 18px; font-weight: 700; color: var(--primary); margin-top: 5px;">
                        Amount: <?php echo $symbol . $amount; ?>
                    </div>
                </div>

                <?php
                // Fetch existing order details if order_id is present
                $order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
                $prefill_name = '';
                $prefill_email = '';

                if ($order_id) {
                    $stmt = $pdo->prepare("SELECT customer_name, customer_email FROM orders WHERE id = ?");
                    $stmt->execute([$order_id]);
                    $existing_order = $stmt->fetch();
                    if ($existing_order) {
                        $prefill_name = $existing_order['customer_name'];
                        $prefill_email = $existing_order['customer_email'];
                    }
                }
                ?>

                <form class="checkout-form-premium" action="process_order.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <!-- We pass order_id so process_order.php knows to UPDATE instead of INSERT -->

                    <div class="form-section-hound">
                        <label><i data-lucide="user"></i> Your Name</label>
                        <div class="premium-input-box">
                            <input type="text" name="name" placeholder="John Doe"
                                value="<?php echo htmlspecialchars($prefill_name); ?>" required <?php echo $prefill_name ? 'readonly' : ''; ?> />
                        </div>
                    </div>

                    <div class="form-section-hound">
                        <label><i data-lucide="mail"></i> Email Address</label>
                        <div class="premium-input-box">
                            <input type="email" name="email" placeholder="john@example.com"
                                value="<?php echo htmlspecialchars($prefill_email); ?>" required <?php echo $prefill_email ? 'readonly' : ''; ?> />
                        </div>
                    </div>

                    <div class="form-section-hound"
                        style="background: rgba(var(--primary-rgb), 0.1); padding: 15px; border-radius: 8px; border: 1px dashed var(--primary);">
                        <label style="color: var(--primary); font-weight: 700;"><i data-lucide="hash"></i> Transaction
                            ID / UTR / Ref No</label>
                        <div class="premium-input-box">
                            <input type="text" name="transaction_id" placeholder="Enter the 12-digit UTR from SMS/App"
                                required style="font-weight: bold; letter-spacing: 1px;"
                                messages="Please enter valid UTR" pattern="\d{12,}"
                                title="Please enter valid 12 digit UTR number" />
                        </div>
                        <small style="display: block; margin-top: 5px; color: var(--text-dim); font-size: 11px;">
                            IMPORTANT: Enter the correct UTR number from your payment SMS/App to get instant access.
                        </small>
                    </div>

                    <button type="submit" class="submit-pay-btn" style="margin-top: 20px;">
                        <span>Verify & Complete Order</span>
                        <i data-lucide="check-circle"></i>
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