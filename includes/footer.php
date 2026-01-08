</main>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="assets/img/logo.png" alt="Premium OTT Store" style="height: 55px; width: auto;">
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

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/916361007446" class="whatsapp-btn" target="_blank" title="Contact us on WhatsApp">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</a>

<style>
    .whatsapp-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #25d366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        transition: transform 0.3s ease;
    }

    .whatsapp-btn:hover {
        transform: scale(1.1);
    }

    .whatsapp-btn img {
        width: 35px;
        height: 35px;
    }
</style>

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