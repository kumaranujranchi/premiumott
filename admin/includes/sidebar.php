<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Overlay: covers page content when sidebar is open on mobile -->
<div class="sidebar-overlay" onclick="toggleAdminSidebar()"></div>

<aside class="sidebar">
    <div class="sidebar-header">
        <img src="../assets/img/logo.png" alt="Logo" class="sidebar-logo">
        <span style="font-weight: 800; font-size: 18px; color: #fff;">Premium <span
                style="color: var(--stat-red);">OTT</span></span>
        <button class="sidebar-close-btn" onclick="toggleAdminSidebar()" aria-label="Close menu">
            <i data-lucide="x" style="width:20px;height:20px;"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <span class="menu-label">Main</span>
            <a href="index" class="menu-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i data-lucide="layout-grid"></i> Dashboard
            </a>
            <a href="orders" class="menu-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                <i data-lucide="shopping-bag"></i> Orders
            </a>
            <a href="add_product"
                class="menu-item <?php echo $current_page == 'add_product.php' ? 'active' : ''; ?>">
                <i data-lucide="plus-circle"></i> Add Product
            </a>
        </div>
    </div>
</aside>

<script>
function toggleAdminSidebar() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.sidebar-overlay').classList.toggle('open');
    document.body.classList.toggle('sidebar-open');
}
// Auto-close sidebar when a menu link is tapped on mobile
document.querySelectorAll('.sidebar .menu-item').forEach(function(item) {
    item.addEventListener('click', function() {
        if (window.innerWidth <= 768) toggleAdminSidebar();
    });
});
</script>