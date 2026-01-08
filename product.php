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

$iconMap = [
    'users' => 'users',
    'rocket' => 'rocket',
    'barchart' => 'bar-chart-2',
    'database' => 'database',
    'form' => 'file-text',
    'video' => 'video',
    'tasks' => 'check-square',
    'mail' => 'mail',
];

$currencyMap = [
    'USD' => '$',
    'INR' => '₹'
];

$icon = isset($iconMap[$product['icon']]) ? $iconMap[$product['icon']] : 'users';
$symbol = $currencyMap[$product['currency'] ?? 'USD'];
?>

<div class="product-page">
    <div class="container">
        <a href="index.php" class="back-link">
            <i data-lucide="chevron-left" style="width: 20px; height: 20px;"></i>
            Back to deals
        </a>

        <div class="product-detail-grid">
            <div class="product-main">
                <?php if (!empty($product['image'])): ?>
                    <div class="product-banner-lg">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                            style="width: 100%; height: auto; border-radius: 12px; margin-bottom: 24px; border: 1px solid var(--border);">
                    </div>
                <?php else: ?>
                    <div class="product-hero-icon" style="background: <?php echo $product['color']; ?>15;">
                        <i data-lucide="<?php echo $icon; ?>"
                            style="width: 48px; height: 48px; color: <?php echo $product['color']; ?>;"></i>
                    </div>
                <?php endif; ?>

                <div class="product-badges">
                    <span class="discount-badge-lg">
                        <?php echo $product['discount_percent']; ?>% OFF
                    </span>
                    <span class="category-badge">
                        <?php echo $product['category']; ?>
                    </span>
                </div>

                <h1 class="product-title">
                    <?php echo $product['name']; ?>
                </h1>
                <p class="product-tagline-lg">
                    <?php echo $product['tagline']; ?>
                </p>

                <div class="product-rating-lg">
                    <div class="stars">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" style="width: 18px; height: 18px; fill: #F59E0B; color: #F59E0B;"></i>
                        <?php endfor; ?>
                    </div>
                    <span>
                        <?php echo $product['rating']; ?> (
                        <?php echo $product['reviews']; ?> reviews)
                    </span>
                </div>

                <p class="product-description-lg">
                    <?php echo $product['description']; ?>
                </p>

                <div class="features-list">
                    <h3>What's Included</h3>
                    <div class="features-grid">
                        <?php foreach ($product['features'] as $feature): ?>
                            <div class="feature-item">
                                <i data-lucide="check" style="width: 18px; height: 18px;" class="feature-check"></i>
                                <span>
                                    <?php echo $feature; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="product-sidebar">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <div class="price-display">
                            <span class="price-discounted"><?php echo $symbol; ?>
                                <?php echo $product['discounted_price']; ?>
                            </span>
                            <span class="price-original-lg"><?php echo $symbol; ?>
                                <?php echo $product['original_price']; ?>
                            </span>
                        </div>
                        <span class="license-type">
                            <?php echo $product['license_type']; ?>
                        </span>
                    </div>

                    <div class="savings-banner">
                        You save <?php echo $symbol; ?>
                        <?php echo $product['original_price'] - $product['discounted_price']; ?> (
                        <?php echo $product['discount_percent']; ?>% off)
                    </div>

                    <div class="delivery-section">
                        <h4>
                            <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                            How You Will Get This Product
                        </h4>
                        <ol class="delivery-steps">
                            <li>
                                <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i>
                                <span>Pay securely via our checkout</span>
                            </li>
                            <li>
                                <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
                                <span>Provide your required details</span>
                            </li>
                            <li>
                                <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                                <span>Our sales team contacts you</span>
                            </li>
                            <li>
                                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                                <span>Access delivered manually</span>
                            </li>
                        </ol>
                    </div>

                    <label class="delivery-checkbox">
                        <input type="checkbox" id="deliveryCheck" />
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-label">I understand the delivery process</span>
                    </label>

                    <button id="proceedBtn" class="proceed-btn disabled" disabled
                        onclick="window.location.href='details.php?id=<?php echo $product['id']; ?>'">
                        Proceed to Payment
                    </button>

                    <p class="guarantee-text">
                        30-day money-back guarantee
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const deliveryCheck = document.getElementById('deliveryCheck');
    const proceedBtn = document.getElementById('proceedBtn');

    deliveryCheck.addEventListener('change', (e) => {
        if (e.target.checked) {
            proceedBtn.classList.remove('disabled');
            proceedBtn.classList.add('active');
            proceedBtn.disabled = false;
        } else {
            proceedBtn.classList.remove('active');
            proceedBtn.classList.add('disabled');
            proceedBtn.disabled = true;
        }
    });

    // Note: The React flow went to payment but normally details form comes first
    // In App.jsx: /details/:id -> DetailsFormPage
</script>

<?php include 'includes/footer.php'; ?>