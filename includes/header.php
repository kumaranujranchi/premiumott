<?php
if (!function_exists('isUserLoggedIn')) {
    include_once __DIR__ . '/user_auth.php';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium OTT Store - Lifetime Digital Deals</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css?v=1.2">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <!-- Social Media Sharing Meta Tags (SEO) -->
    <meta property="og:title" content="Premium OTT Store - Lifetime Digital Deals">
    <meta property="og:description"
        content="Access your favorite streaming platforms without the high monthly costs. Premium OTT and digital subscriptions at unbeatable prices.">
    <meta property="og:image" content="https://premiumott.com/assets/img/logo.png">
    <meta property="og:url" content="https://premiumott.com">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Premium OTT Store - Lifetime Digital Deals">
    <meta name="twitter:description" content="Access your favorite streaming platforms without the high monthly costs.">
    <meta name="twitter:image" content="https://premiumott.com/assets/img/logo.png">
    <style>
        /* ----- Header Layout Fix ----- */
        .header-content {
            gap: 16px !important;
        }
        .nav {
            gap: 20px !important;
            flex-shrink: 1;
        }
        .header-actions {
            flex-shrink: 0;
            gap: 10px !important;
            white-space: nowrap;
        }
        /* ----- User Auth Header Styles ----- */
        .header-login-btn {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--text-secondary); font-size: 14px; font-weight: 600;
            padding: 8px 14px; border-radius: var(--radius-sm);
            border: 1px solid var(--border); transition: all .2s;
        }
        .header-login-btn:hover { color: var(--primary); border-color: var(--primary); }
        .header-register-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary); color: #000; font-size: 14px; font-weight: 700;
            padding: 8px 16px; border-radius: var(--radius-sm); transition: opacity .2s;
        }
        .header-register-btn:hover { opacity: .88; }
        /* User dropdown */
        .user-menu-wrap { position: relative; }
        .user-menu-btn {
            display: flex; align-items: center; gap: 7px;
            background: transparent; border: 1px solid var(--border);
            color: var(--text-primary); font-size: 14px; font-weight: 600;
            padding: 8px 14px; border-radius: var(--radius-sm); cursor: pointer;
            transition: border-color .2s;
        }
        .user-menu-btn:hover { border-color: var(--primary); color: var(--primary); }
        .user-menu-name { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .user-dropdown {
            display: none; position: absolute; top: calc(100% + 8px); right: 0;
            background: var(--bg-primary); border: 1px solid var(--border);
            border-radius: var(--radius-md); min-width: 220px;
            padding: 8px; box-shadow: var(--shadow-lg); z-index: 999;
        }
        .user-dropdown.open { display: block; }
        .user-dropdown-info { padding: 10px 12px 8px; }
        .user-dropdown-name { display: block; font-weight: 700; font-size: 14px; color: var(--text-primary); }
        .user-dropdown-email { display: block; font-size: 12px; color: var(--text-muted); margin-top: 2px; word-break: break-all; }
        .user-dropdown-divider { height: 1px; background: var(--border); margin: 6px 0; }
        .user-dropdown-item {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 12px; border-radius: var(--radius-sm);
            font-size: 14px; font-weight: 500; color: var(--text-secondary);
            transition: all .15s;
        }
        .user-dropdown-item:hover { background: var(--bg-tertiary); color: var(--text-primary); }
        .logout-item:hover { color: var(--danger); }
    </style>
</head>

<body>
    <div class="trust-bar">
        <div class="container">
            <span><i data-lucide="check" style="width: 14px; height: 14px;"></i> Trusted by 10,000+ Founders</span>
            <span><i data-lucide="shield" style="width: 14px; height: 14px;"></i> 30-Day Money Back Guarantee</span>
            <span><i data-lucide="check" style="width: 14px; height: 14px;"></i> Verified Software</span>
        </div>
    </div>
    <header class="header">
        <div class="container header-content">
            <a href="index.php" class="logo">
                <img src="assets/img/logo.png" alt="Premium OTT Store" style="height: 60px; width: auto;">
            </a>

            <nav class="nav" id="mainNav">
                <a href="index.php" class="nav-link">All Products</a>
                <a href="index.php?category=SaaS" class="nav-link">SaaS</a>
                <a href="index.php?category=Operating System" class="nav-link">Operating System</a>
                <a href="index.php?category=Social Media" class="nav-link">Social Media</a>
                <a href="index.php?category=Automation" class="nav-link">Automation</a>
                <a href="index.php?category=Hosting" class="nav-link">Hosting</a>
            </nav>

            <div class="header-actions">
                <a href="#" class="nav-link support-link">Support</a>

                <?php if (isUserLoggedIn()): ?>
                <!-- Logged-in user dropdown -->
                <div class="user-menu-wrap">
                    <button class="user-menu-btn" id="userMenuBtn">
                        <i data-lucide="user-circle" style="width: 22px; height: 22px;"></i>
                        <span class="user-menu-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <i data-lucide="chevron-down" style="width: 14px; height: 14px;"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-info">
                            <span class="user-dropdown-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <span class="user-dropdown-email"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                        </div>
                        <div class="user-dropdown-divider"></div>
                        <a href="profile.php" class="user-dropdown-item">
                            <i data-lucide="user-circle" style="width: 15px; height: 15px;"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="logout.php" class="user-dropdown-item logout-item">
                            <i data-lucide="log-out" style="width: 15px; height: 15px;"></i>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <!-- Guest buttons -->
                <a href="login.php" class="header-login-btn">
                    <i data-lucide="log-in" style="width: 16px; height: 16px;"></i>
                    <span>Sign In</span>
                </a>
                <a href="register.php" class="header-register-btn">
                    <span>Register</span>
                </a>
                <?php endif; ?>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i data-lucide="menu" id="menuIcon" style="width: 24px; height: 24px;"></i>
                </button>
            </div>
        </div>
    </header>
    <main style="flex: 1;">