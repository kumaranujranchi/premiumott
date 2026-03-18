<?php
// ALL processing happens BEFORE any HTML output so header() redirects work
include 'includes/db.php';
include 'includes/user_auth.php';
requireUserLogin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = getProduct($pdo, $id);

$postError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
    $name         = trim($_POST['name']         ?? '');
    $email        = trim($_POST['email']        ?? '');
    $whatsapp     = trim($_POST['whatsapp']     ?? '');
    $requirements = trim($_POST['requirements'] ?? '');

    try {
        $columnsStmt     = $pdo->query("SHOW COLUMNS FROM orders");
        $availableColumns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $orderData = [
            'product_id'         => $id,
            'customer_name'      => $name,
            'customer_email'     => $email,
            'customer_whatsapp'  => $whatsapp,
            'requirements'       => $requirements,
            'total_amount'       => $product['discounted_price'],
            'status'             => 'Pending'
        ];

        $insertData = [];
        foreach ($orderData as $column => $value) {
            if (in_array($column, $availableColumns, true)) {
                $insertData[$column] = $value;
            }
        }

        $columnNames  = array_keys($insertData);
        $placeholders = implode(', ', array_fill(0, count($columnNames), '?'));
        $sql          = "INSERT INTO orders (" . implode(', ', $columnNames) . ") VALUES (" . $placeholders . ")";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($insertData));
        $new_order_id = $pdo->lastInsertId();

        // Redirect BEFORE any HTML output
        header("Location: payment.php?id=" . $id . "&order_id=" . $new_order_id);
        exit;
    } catch (Throwable $e) {
        $postError = $e->getMessage();
    }
}

// Only now output HTML
include 'includes/header.php';

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

if ($postError) {
    echo '<div class="container" style="padding: 60px 20px; text-align: center;">
            <div class="hound-card" style="max-width: 580px; margin: 0 auto; padding: 40px;">
                <i data-lucide="alert-triangle" style="width: 48px; height: 48px; color: #f59e0b; margin-bottom: 20px;"></i>
                <h2 style="margin-bottom: 16px;">Order could not be created</h2>
                <p style="color: var(--text-muted); margin-bottom: 8px; font-size: 13px; word-break: break-word;">'
                    . htmlspecialchars($postError) . '</p>
                <a href="details.php?id=' . $id . '" class="btn-primary" style="margin-top:20px;">
                  <span>Try Again</span>
                </a>
            </div>
          </div>';
    include 'includes/footer.php';
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