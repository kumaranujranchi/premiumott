<?php
include 'includes/db.php';
include 'includes/header.php';

$products = getAllProducts($pdo, true);

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
?>

<div class="home-page">
    <section class="hero">
        <div class="container">
            <div class="hero-badge">
                <span class="badge-dot"></span>
                New deals added weekly
            </div>
            <h1 class="hero-title">
                Premium OTT Accounts at<br />
                <span class="hero-highlight">Unbeatable Prices</span>
            </h1>
            <p class="hero-subtitle">
                Stop paying monthly subscriptions. Get lifetime and premium access to your favorite OTT platforms for a
                fraction of the cost.
            </p>
            <div class="hero-actions">
                <a href="#deals" class="btn-primary">
                    <span>Browse Deals</span> <i data-lucide="arrow-right"
                        style="width: 18px; height: 18px; margin-left: 8px;"></i>
                </a>
                <a href="#how-it-works" class="btn-secondary">
                    <span>How it Works</span>
                </a>
            </div>
            <div class="trust-logos">
                <span class="trust-logo">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 0L10.2 5.3L16 6L12 10L13 16L8 13.3L3 16L4 10L0 6L5.8 5.3L8 0Z" />
                    </svg>
                    TechCrunch
                </span>
                <span class="trust-logo">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.5" fill="none" />
                        <path d="M6 8L7.5 9.5L10 6.5" stroke="currentColor" stroke-width="1.5" fill="none" />
                    </svg>
                    ProductHunt
                </span>
                <span class="trust-logo">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M2 4L8 2L14 4L8 6L2 4Z" />
                        <path d="M2 4V10L8 12V6L2 4Z" />
                        <path d="M8 6V12L14 10V4L8 6Z" />
                    </svg>
                    IndieHackers
                </span>
            </div>
        </div>
    </section>

    <section id="deals" class="deals-section">
        <div class="container">
            <?php
            $selectedCategory = $_GET['category'] ?? null;
            $selectedSection = $_GET['section'] ?? null;

            if ($selectedCategory || $selectedSection) {
                if ($selectedCategory) {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND is_active = 1 ORDER BY created_at DESC");
                    $stmt->execute([$selectedCategory]);
                    $title = $selectedCategory . " Deals";
                    $subtitle = "Exclusive offers in " . $selectedCategory . " category.";
                } else {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE section = ? AND is_active = 1 ORDER BY created_at DESC");
                    $stmt->execute([$selectedSection]);
                    $title = $selectedSection;
                    $subtitle = "All deals in " . $selectedSection . " section.";
                }
                $filteredProducts = $stmt->fetchAll();
                ?>
                <div class="section-header">
                    <div>
                        <h2 class="section-title"><?php echo htmlspecialchars($title); ?></h2>
                        <p class="section-subtitle"><?php echo htmlspecialchars($subtitle); ?></p>
                    </div>
                    <a href="index.php" class="view-all-link">
                        Clear Filter <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
                <div class="products-grid">
                    <?php if (empty($filteredProducts)): ?>
                        <p style="grid-column: span 3; text-align: center; padding: 40px; color: var(--text-muted);">No products
                            found here.</p>
                    <?php else: ?>
                        <?php foreach ($filteredProducts as $product):
                            $icon = isset($iconMap[$product['icon']]) ? $iconMap[$product['icon']] : 'users';
                            ?>
                            <div class="product-card">
                                <div class="discount-badge"><?php echo $product['discount_percent']; ?>% OFF</div>
                                <?php if (!empty($product['image'])): ?>
                                    <div class="product-banner">
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                                            style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
                                    </div>
                                <?php else: ?>
                                    <div class="product-icon" style="background: <?php echo $product['color']; ?>15;">
                                        <i data-lucide="<?php echo $icon; ?>"
                                            style="width: 32px; height: 32px; color: <?php echo $product['color']; ?>;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="product-header">
                                    <h3 class="product-name"><?php echo $product['name']; ?></h3>
                                    <div class="product-rating">
                                        <i data-lucide="star" style="width: 14px; height: 14px; fill: #F59E0B; color: #F59E0B;"></i>
                                        <span><?php echo $product['rating']; ?></span>
                                    </div>
                                </div>
                                <p class="product-tagline"><?php echo $product['tagline']; ?></p>
                                <p class="product-description"><?php echo $product['description']; ?></p>
                                <div class="product-pricing">
                                    <div class="prices">
                                        <span
                                            class="price-current"><?php echo $currencyMap[$product['currency'] ?? 'USD']; ?><?php echo $product['discounted_price']; ?></span>
                                        <span
                                            class="price-original"><?php echo $currencyMap[$product['currency'] ?? 'USD']; ?><?php echo $product['original_price']; ?></span>
                                    </div>
                                    <span class="license-badge"><?php echo $product['license_type']; ?></span>
                                </div>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="view-deal-btn">
                                    <span>View Deal</span> <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php
            } else {
                // Show multiple sections
                $sections = ['New Arrivals', 'Hot Deals', 'Limited Stock', 'Coming Soon', 'Special Offers'];
                foreach ($sections as $section) {
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE section = ? AND is_active = 1 ORDER BY created_at DESC LIMIT 6");
                    $stmt->execute([$section]);
                    $sectionProducts = $stmt->fetchAll();

                    if (empty($sectionProducts))
                        continue;
                    ?>
                    <div class="section-header" style="margin-top: <?php echo $section == 'New Arrivals' ? '0' : '60px'; ?>">
                        <div>
                            <h2 class="section-title"><?php echo $section; ?></h2>
                            <p class="section-subtitle">
                                <?php
                                echo match ($section) {
                                    'New Arrivals' => 'Latest software added to our marketplace.',
                                    'Hot Deals' => 'Trending software with massive savings.',
                                    'Limited Stock' => 'Grab these before they are gone forever.',
                                    'Coming Soon' => 'Get ready for these upcoming amazing deals.',
                                    'Special Offers' => 'Exclusive discounts for our premium members.',
                                    default => 'Hand-picked software with the biggest discounts.'
                                };
                                ?>
                            </p>
                        </div>
                        <a href="index.php?section=<?php echo urlencode($section); ?>" class="view-all-link">View All <i
                                data-lucide="arrow-right" style="width: 16px; height: 16px;"></i></a>
                    </div>

                    <div class="products-grid">
                        <?php foreach ($sectionProducts as $product):
                            $icon = isset($iconMap[$product['icon']]) ? $iconMap[$product['icon']] : 'users';
                            ?>
                            <div class="product-card">
                                <div class="discount-badge"><?php echo $product['discount_percent']; ?>% OFF</div>
                                <?php if (!empty($product['image'])): ?>
                                    <div class="product-banner">
                                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"
                                            style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 16px;">
                                    </div>
                                <?php else: ?>
                                    <div class="product-icon" style="background: <?php echo $product['color']; ?>15;">
                                        <i data-lucide="<?php echo $icon; ?>"
                                            style="width: 32px; height: 32px; color: <?php echo $product['color']; ?>;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="product-header">
                                    <h3 class="product-name"><?php echo $product['name']; ?></h3>
                                    <div class="product-rating">
                                        <i data-lucide="star" style="width: 14px; height: 14px; fill: #F59E0B; color: #F59E0B;"></i>
                                        <span><?php echo $product['rating']; ?></span>
                                    </div>
                                </div>
                                <p class="product-tagline"><?php echo $product['tagline']; ?></p>
                                <p class="product-description"><?php echo $product['description']; ?></p>
                                <div class="product-pricing">
                                    <div class="prices">
                                        <span
                                            class="price-current"><?php echo $currencyMap[$product['currency'] ?? 'USD']; ?><?php echo $product['discounted_price']; ?></span>
                                        <span
                                            class="price-original"><?php echo $currencyMap[$product['currency'] ?? 'USD']; ?><?php echo $product['original_price']; ?></span>
                                    </div>
                                    <span class="license-badge"><?php echo $product['license_type']; ?></span>
                                </div>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="view-deal-btn">
                                    <span>View Deal</span> <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>

    <section id="how-it-works" class="features-section">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-lucide="shield" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3>Vetted Software</h3>
                    <p>Every tool is tested by our team to ensure quality and reliability before listing.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon highlight">
                        <i data-lucide="zap" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3>Instant Savings</h3>
                    <p>Save up to 95% on software costs with our exclusive lifetime deals.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i data-lucide="trending-up" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3>Growth Focused</h3>
                    <p>Tools selected specifically to help founders and SMBs scale faster.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>