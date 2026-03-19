<nav class="top-nav">
    <!-- Hamburger: only visible on mobile -->
    <button class="mob-menu-btn" onclick="toggleAdminSidebar()" aria-label="Toggle menu">
        <i data-lucide="menu" style="width:22px;height:22px;"></i>
    </button>

    <div class="search-bar">
        <i data-lucide="search" style="width: 16px; color: var(--text-dim); margin-right: 10px;"></i>
        <input type="text" placeholder="Search products, orders...">
    </div>

    <div class="nav-actions">
        <a href="../index" class="nav-link" target="_blank"
            style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; padding: 6px 12px; border: 1px solid var(--border); border-radius: 4px; margin-right: 10px;">
            <i data-lucide="external-link" style="width: 16px;"></i>
            <span class="go-to-website-text">Go to Website</span>
        </a>
        <a href="#" class="nav-link"><i data-lucide="settings" style="width: 20px;"></i></a>
        <a href="#" class="nav-link"><i data-lucide="bell" style="width: 20px;"></i></a>
        <div class="user-profile">
            <div class="user-avatar"
                style="background: var(--stat-red); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: #fff;">
                A</div>
            <span style="font-size: 14px; font-weight: 600;">Admin</span>
            <a href="logout" class="nav-link" style="margin-left: 10px;"><i data-lucide="log-out"
                    style="width: 18px;"></i></a>
        </div>
    </div>
</nav>