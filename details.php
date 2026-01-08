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

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $requirements = $_POST['requirements'];

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $name, $email, $product['discounted_price']]);

    // In a real app, you'd also save the whatsapp and requirements in a customer_details table
    // For now, we'll just redirect to confirmation
    header("Location: confirmation.php?id=" . $id);
    exit;
}
?>

<div class="details-page">
    <div class="container">
        <div class="details-content">
            <div class="success-banner">
                <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
                <h2>Payment Successful!</h2>
                <p>Thank you for your purchase. Please provide your details below so we can deliver your product.</p>
            </div>

            <div class="details-card">
                <div class="product-reminder">
                    <span class="reminder-label">Your Purchase:</span>
                    <span class="reminder-product">
                        <?php echo $product['name']; ?> -
                        <?php echo $product['license_type']; ?>
                    </span>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" placeholder="John Smith" required />
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" placeholder="you@example.com" required />
                        <span class="form-hint">We'll send order updates to this email</span>
                    </div>

                    <div class="form-group">
                        <label>WhatsApp Number *</label>
                        <input type="tel" name="whatsapp" placeholder="+1 234 567 8900" required />
                        <span class="form-hint">Our sales team will contact you on WhatsApp</span>
                    </div>

                    <div class="form-group">
                        <label>Additional Requirements</label>
                        <textarea name="requirements"
                            placeholder="Any specific requirements for your license? (e.g., preferred email for account, number of users needed)"
                            rows="4"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        Submit Details
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>