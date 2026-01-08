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
            <i data-lucide="arrow-left"></i>
            <span>Back to all deals</span>
        </a>

        <div class="product-detail-layout">
            <!-- Left Side: Product Info -->
            <div class="product-info-column">
                <div class="product-main-card">
                    <?php if (!empty($product['image'])): ?>
                        <div class="product-banner-wrapper">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                                class="banner-img">
                            <div class="discount-pill"><?php echo $product['discount_percent']; ?>% OFF</div>
                        </div>
                    <?php endif; ?>

                    <div class="product-header-content">
                        <div class="category-tag"><?php echo $product['category']; ?></div>
                        <h1 class="product-title"><?php echo $product['name']; ?></h1>
                        <p class="product-tagline-hero"><?php echo $product['tagline']; ?></p>

                        <div class="rating-display">
                            <div class="stars-row">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i data-lucide="star" class="star-icon"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-text"><strong><?php echo $product['rating']; ?></strong>
                                (<?php echo $product['reviews']; ?> verified reviews)</span>
                        </div>
                    </div>

                    <div class="product-description-section">
                        <h3 class="section-heading">About this product</h3>
                        <div class="description-text">
                            <?php echo $product['description']; ?>
                        </div>
                    </div>

                    <div class="features-section-lg">
                        <h3 class="section-heading">What's Included</h3>
                        <div class="features-grid-premium">
                            <?php foreach ($product['features'] as $feature): ?>
                                <div class="feature-pill">
                                    <i data-lucide="check-circle-2"></i>
                                    <span><?php echo $feature; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Pricing & Checkout -->
            <div class="product-sidebar-column">
                <div class="checkout-card-hound">
                    <div class="pricing-card-header">
                        <div class="price-stack">
                            <span
                                class="current-price"><?php echo $symbol; ?><?php echo $product['discounted_price']; ?></span>
                            <span
                                class="old-price"><?php echo $symbol; ?><?php echo $product['original_price']; ?></span>
                        </div>
                        <div class="license-tag-premium"><?php echo $product['license_type']; ?></div>
                    </div>

                    <div class="savings-highlight">
                        <i data-lucide="arrow-down-right"></i>
                        <span>Save
                            <?php echo $symbol; ?><?php echo $product['original_price'] - $product['discounted_price']; ?>
                            (<?php echo $product['discount_percent']; ?>%)</span>
                    </div>

                    <div class="delivery-workflow">
                        <h4 class="workflow-title">
                            <i data-lucide="zap"></i>
                            Delivery Process
                        </h4>
                        <div class="workflow-steps">
                            <div class="step-line">
                                <div class="step-icon-box"><i data-lucide="credit-card"></i></div>
                                <div class="step-info">
                                    <strong>Secure Payment</strong>
                                    <p>Safe & encrypted checkout</p>
                                </div>
                            </div>
                            <div class="step-line">
                                <div class="step-icon-box"><i data-lucide="file-edit"></i></div>
                                <div class="step-info">
                                    <strong>Details Submission</strong>
                                    <p>Provide your setup info</p>
                                </div>
                            </div>
                            <div class="step-line">
                                <div class="step-icon-box"><i data-lucide="message-square"></i></div>
                                <div class="step-info">
                                    <strong>Team Contact</strong>
                                    <p>Our support reaches out</p>
                                </div>
                            </div>
                            <div class="step-line last">
                                <div class="step-icon-box"><i data-lucide="package-check"></i></div>
                                <div class="step-info">
                                    <strong>Full Access</strong>
                                    <p>Manual delivery of license</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <label class="premium-checkbox">
                            <input type="checkbox" id="deliveryCheck">
                            <span class="check-box"></span>
                            <span class="check-label">I understand the delivery process</span>
                        </label>

                        <button id="proceedBtn" class="proceed-btn-hound disabled" disabled
                            onclick="window.location.href='details.php?id=<?php echo $product['id']; ?>'">
                            <span>Proceed to Payment</span>
                            <i data-lucide="chevron-right"></i>
                        </button>

                        <div class="guarantee-badge">
                            <i data-lucide="shield-check"></i>
                            <span>30-Day Money Back Guarantee</span>
                        </div>
                    </div>
                </div>

                <div class="trust-features-card">
                    <div class="trust-item">
                        <i data-lucide="clock"></i>
                        <div>
                            <strong>Fast Response</strong>
                            <span>Support within 2-4 hours</span>
                        </div>
                    </div>
                    <div class="trust-item">
                        <i data-lucide="lock"></i>
                        <div>
                            <strong>Secure Access</strong>
                            <span>100% Genuine Licenses</span>
                        </div>
                    </div>
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

    lucide.createIcons();
</script>

<?php include 'includes/footer.php'; ?>