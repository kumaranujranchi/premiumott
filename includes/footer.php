</main>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/img/logo.png" alt="Premium OTT Store" style="height: 40px; width: auto;">
                </div>
                <p class="footer-desc">
                    The world's leading marketplace for premium OTT and digital subscriptions. We provide access to your
                    favorite streaming platforms without the high monthly costs.
                </p>
            </div>

            <div class="footer-links">
                <h4>Marketplace</h4>
                <a href="index.php">Premium Deals</a>
                <a href="#">New Arrivals</a>
                <a href="#">Success Stories</a>
                <a href="#">Refer a Friend</a>
            </div>

            <div class="footer-links">
                <h4>Help & Support</h4>
                <a href="#">Help Center</a>
                <a href="#">Redemption Guide</a>
                <a href="#">Contact Support</a>
                <a href="#">Become a Partner</a>
            </div>

            <div class="footer-links">
                <h4>Secure Checkout</h4>
                <div class="security-badge">
                    <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
                    256-bit SSL Secured
                </div>
                <p class="payment-note">
                    Safe & secure payments via Stripe, PayPal, and major credit cards.
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy;
                <?php echo date('Y'); ?> Premium OTT Store. All rights reserved.
            </p>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');
    const menuIcon = document.getElementById('menuIcon');

    mobileMenuBtn.addEventListener('click', () => {
        mainNav.classList.toggle('nav-open');
        const isOpen = mainNav.classList.contains('nav-open');
        menuIcon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
        lucide.createIcons();
    });
</script>
</body>

</html>