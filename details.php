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

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $requirements = $_POST['requirements'];

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, customer_whatsapp, requirements, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id, $name, $email, $whatsapp, $requirements, $product['discounted_price']]);

    header("Location: confirmation.php?id=" . $id);
    exit;
}
?>

<div class="details-page-premium">
    <div class="container small-container">
        <!-- Progress Steps -->
        <div class="order-progress">
            <div class="progress-step completed">
                <div class="step-dot"><i data-lucide="check"></i></div>
                <span>Selection</span>
            </div>
            <div class="progress-line active"></div>
            <div class="progress-step active">
                <div class="step-dot">2</div>
                <span>Your Details</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-dot">3</div>
                <span>Access</span>
            </div>
        </div>

        <div class="success-header-card">
            <div class="success-icon-animate">
                <i data-lucide="party-popper"></i>
            </div>
            <h1>Payment Successful!</h1>
            <p>Thank you for choosing <strong><?php echo $product['name']; ?></strong>. Please provide your delivery
                info below.</p>
        </div>

        <div class="details-form-card">
            <div class="form-header">
                <div class="purchase-badge">
                    <i data-lucide="shopping-bag"></i>
                    <span>Order: <?php echo $product['name']; ?> (<?php echo $product['license_type']; ?>)</span>
                </div>
            </div>

            <form method="POST" class="premium-form">
                <div class="form-grid-2">
                    <div class="form-group-premium">
                        <label><i data-lucide="user"></i> Full Name *</label>
                        <div class="input-wrapper">
                            <input type="text" name="name" placeholder="John Smith" required />
                        </div>
                    </div>

                    <div class="form-group-premium">
                        <label><i data-lucide="mail"></i> Email Address *</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" placeholder="you@example.com" required />
                        </div>
                        <span class="input-hint">Order updates will be sent here</span>
                    </div>

                    <div class="form-group-premium">
                        <label><i data-lucide="phone"></i> WhatsApp Number *</label>
                        <div class="input-wrapper">
                            <input type="tel" name="whatsapp" placeholder="+91 91234 56789" required />
                        </div>
                        <span class="input-hint">Our team will contact you for delivery</span>
                    </div>

                    <div class="form-group-premium">
                        <label><i data-lucide="message-square"></i> Special Requirements</label>
                        <div class="input-wrapper">
                            <textarea name="requirements"
                                placeholder="e.g. Preferred email for activation, company name, etc."
                                rows="4"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <p class="privacy-note"><i data-lucide="lock" style="width: 14px;"></i> Your data is 100% secure and
                        encrypted.</p>
                    <button type="submit" class="submit-btn-premium">
                        <span>Submit & Get Access</span>
                        <i data-lucide="arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>