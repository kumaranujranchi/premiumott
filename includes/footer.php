</main>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="index.php" class="footer-logo">
                    <img src="assets/img/logo.png" alt="Premium OTT Store">
                </a>
                <p class="footer-desc">
                    The world's leading marketplace for premium OTT and digital subscriptions. We provide access to your
                    favorite streaming platforms without the high monthly costs.
                </p>
                <div class="social-links">
                    <a href="#" class="social-icon"><i data-lucide="twitter"></i></a>
                    <a href="#" class="social-icon"><i data-lucide="facebook"></i></a>
                    <a href="#" class="social-icon"><i data-lucide="instagram"></i></a>
                    <a href="#" class="social-icon"><i data-lucide="linkedin"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Marketplace</h4>
                <div class="footer-links-list">
                    <a href="index.php">Premium Deals</a>
                    <a href="#">New Arrivals</a>
                    <a href="#">Success Stories</a>
                    <a href="#">Refer a Friend</a>
                    <a href="admin/login.php">Admin Login</a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Help & Support</h4>
                <div class="footer-links-list">
                    <a href="#">Help Center</a>
                    <a href="#">Redemption Guide</a>
                    <a href="#">Contact Support</a>
                    <a href="#">Become a Partner</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>

            <div class="footer-links">
                <div class="security-section">
                    <h4>Secure Checkout</h4>
                    <div class="security-badge">
                        <i data-lucide="shield-check"></i>
                        <span>256-bit SSL Secured</span>
                    </div>
                    <p class="payment-note">
                        Safe & secure payments via Stripe, PayPal, and major credit cards.
                    </p>
                    <div class="payment-methods">
                        <div class="pay-card"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                        </div>
                        <div class="pay-card"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                alt="Mastercard"></div>
                        <div class="pay-card"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg"
                                alt="PayPal"></div>
                        <div class="pay-card"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg"
                                alt="Stripe"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Premium OTT Store. All rights reserved.</p>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/916361007446" class="whatsapp-btn-premium" target="_blank" title="Contact us on WhatsApp">
    <div class="whatsapp-pulse"></div>
    <i data-lucide="message-circle"></i>
</a>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.getElementById('mainNav');
    const menuIcon = document.getElementById('menuIcon');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            mainNav.classList.toggle('nav-open');
            const isOpen = mainNav.classList.contains('nav-open');
            menuIcon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            lucide.createIcons();
        });
    }
</script>
</body>

</html>