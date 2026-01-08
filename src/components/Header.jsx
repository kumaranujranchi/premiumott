import { Link } from 'react-router-dom'
import { ShoppingCart, Menu, X, Shield, Check } from 'lucide-react'
import { useState } from 'react'
import './Header.css'

function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  return (
    <>
      <div className="trust-bar">
        <div className="container">
          <span><Check size={14} /> Trusted by 10,000+ Founders</span>
          <span><Shield size={14} /> 30-Day Money Back Guarantee</span>
          <span><Check size={14} /> Verified Software</span>
        </div>
      </div>
      <header className="header">
        <div className="container header-content">
          <Link to="/" className="logo">
            <div className="logo-icon">S</div>
            <span className="logo-text">Saas<span className="logo-highlight">Deals</span></span>
          </Link>
          
          <nav className={`nav ${mobileMenuOpen ? 'nav-open' : ''}`}>
            <Link to="/" className="nav-link" onClick={() => setMobileMenuOpen(false)}>Software Deals</Link>
            <Link to="/" className="nav-link" onClick={() => setMobileMenuOpen(false)}>Lifetime Licenses</Link>
            <Link to="/" className="nav-link" onClick={() => setMobileMenuOpen(false)}>How it Works</Link>
          </nav>

          <div className="header-actions">
            <Link to="/" className="nav-link support-link">Support</Link>
            <button className="cart-btn">
              <ShoppingCart size={20} />
            </button>
            <button className="mobile-menu-btn" onClick={() => setMobileMenuOpen(!mobileMenuOpen)}>
              {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
            </button>
          </div>
        </div>
      </header>
    </>
  )
}

export default Header
