import { Link } from 'react-router-dom'
import { Shield } from 'lucide-react'
import './Footer.css'

function Footer() {
  return (
    <footer className="footer">
      <div className="container">
        <div className="footer-grid">
          <div className="footer-brand">
            <Link to="/" className="footer-logo">
              <div className="logo-icon">S</div>
              <span className="logo-text">Saas<span className="logo-highlight">Deals</span></span>
            </Link>
            <p className="footer-desc">
              Curating the best software deals for startups and entrepreneurs. Save up to 90% on premium tools.
            </p>
          </div>

          <div className="footer-links">
            <h4>Platform</h4>
            <Link to="/">Browse All Deals</Link>
            <Link to="/">Lifetime Licenses</Link>
            <Link to="/">Upcoming Deals</Link>
          </div>

          <div className="footer-links">
            <h4>Support</h4>
            <Link to="/">Help Center</Link>
            <Link to="/">Refund Policy</Link>
            <Link to="/">Contact Us</Link>
          </div>

          <div className="footer-links">
            <h4>Secure Payment</h4>
            <div className="security-badge">
              <Shield size={18} />
              <span>256-bit SSL Encrypted</span>
            </div>
            <p className="payment-note">
              We accept major credit cards and wire transfers for enterprise orders.
            </p>
          </div>
        </div>

        <div className="footer-bottom">
          <p>&copy; 2024 SaaSDeals Inc. All rights reserved.</p>
          <div className="footer-legal">
            <Link to="/">Privacy Policy</Link>
            <Link to="/">Terms of Service</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}

export default Footer
