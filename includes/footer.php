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
    <svg viewBox="0 0 24 24" width="32" height="32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
    </svg>
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
            document.body.classList.toggle('menu-open');
            const isOpen = mainNav.classList.contains('nav-open');
            menuIcon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            lucide.createIcons();
        });
    }
</script>
</body>

</html>