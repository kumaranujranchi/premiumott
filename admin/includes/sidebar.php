<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/img/logo.png" alt="Logo" class="sidebar-logo">
        <span style="font-weight: 800; font-size: 18px; color: #fff;">Premium <span
                style="color: var(--stat-red);">OTT</span></span>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <span class="menu-label">Main</span>
            <a href="index.php" class="menu-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i data-lucide="layout-grid"></i> Dashboard
            </a>
            <a href="orders.php" class="menu-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                <i data-lucide="shopping-bag"></i> Orders
            </a>
            <a href="add_product.php"
                class="menu-item <?php echo $current_page == 'add_product.php' ? 'active' : ''; ?>">
                <i data-lucide="plus-circle"></i> Add Product
            </a>
        </div>
    </div>
</aside>