import { ArrowRight, Shield, Zap, TrendingUp } from 'lucide-react'
import ProductCard from '../components/ProductCard'
import { products } from '../data/products'
import './HomePage.css'

function HomePage() {
  return (
    <div className="home-page">
      <section className="hero">
        <div className="container">
          <div className="hero-badge">
            <span className="badge-dot"></span>
            New deals added weekly
          </div>
          <h1 className="hero-title">
            Premium SaaS Tools at<br />
            <span className="hero-highlight">Unbeatable Prices</span>
          </h1>
          <p className="hero-subtitle">
            Stop paying monthly subscriptions. Get lifetime deals on verified software and grow your business for a fraction of the cost.
          </p>
          <div className="hero-actions">
            <a href="#deals" className="btn-primary">
              Browse Deals <ArrowRight size={18} />
            </a>
            <a href="#how-it-works" className="btn-secondary">
              How it Works
            </a>
          </div>
          <div className="trust-logos">
            <span className="trust-logo">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M8 0L10.2 5.3L16 6L12 10L13 16L8 13.3L3 16L4 10L0 6L5.8 5.3L8 0Z"/></svg>
              TechCrunch
            </span>
            <span className="trust-logo">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="8" r="7" stroke="currentColor" strokeWidth="1.5" fill="none"/><path d="M6 8L7.5 9.5L10 6.5" stroke="currentColor" strokeWidth="1.5" fill="none"/></svg>
              ProductHunt
            </span>
            <span className="trust-logo">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M2 4L8 2L14 4L8 6L2 4Z"/><path d="M2 4V10L8 12V6L2 4Z"/><path d="M8 6V12L14 10V4L8 6Z"/></svg>
              IndieHackers
            </span>
          </div>
        </div>
      </section>

      <section id="deals" className="deals-section">
        <div className="container">
          <div className="section-header">
            <div>
              <h2 className="section-title">Featured Deals</h2>
              <p className="section-subtitle">Hand-picked software with the biggest discounts.</p>
            </div>
            <a href="#" className="view-all-link">
              View All <ArrowRight size={16} />
            </a>
          </div>

          <div className="products-grid">
            {products.map(product => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        </div>
      </section>

      <section id="how-it-works" className="features-section">
        <div className="container">
          <div className="features-grid">
            <div className="feature-card">
              <div className="feature-icon">
                <Shield size={28} />
              </div>
              <h3>Vetted Software</h3>
              <p>Every tool is tested by our team to ensure quality and reliability before listing.</p>
            </div>
            <div className="feature-card">
              <div className="feature-icon highlight">
                <Zap size={28} />
              </div>
              <h3>Instant Savings</h3>
              <p>Save up to 95% on software costs with our exclusive lifetime deals.</p>
            </div>
            <div className="feature-card">
              <div className="feature-icon">
                <TrendingUp size={28} />
              </div>
              <h3>Growth Focused</h3>
              <p>Tools selected specifically to help founders and SMBs scale faster.</p>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}

export default HomePage
