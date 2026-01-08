import { useParams, Link } from 'react-router-dom'
import { CheckCircle2, Clock, MessageCircle, Mail, ArrowRight } from 'lucide-react'
import { getProductById } from '../data/products'
import './ConfirmationPage.css'

function ConfirmationPage() {
  const { id } = useParams()
  const product = getProductById(id)

  if (!product) {
    return (
      <div className="container" style={{ padding: '60px 20px', textAlign: 'center' }}>
        <h2>Product not found</h2>
        <Link to="/" className="btn-primary" style={{ marginTop: '20px', display: 'inline-flex' }}>
          Back to Home
        </Link>
      </div>
    )
  }

  const orderNumber = `ORD-${Date.now().toString(36).toUpperCase()}`

  return (
    <div className="confirmation-page">
      <div className="container">
        <div className="confirmation-content">
          <div className="success-illustration">
            <div className="success-circle">
              <CheckCircle2 size={64} />
            </div>
          </div>

          <h1 className="confirmation-title">Order Confirmed!</h1>
          <p className="confirmation-subtitle">
            Our sales team will contact you shortly to deliver your product.
          </p>

          <div className="order-card">
            <div className="order-header">
              <div>
                <span className="order-label">Order Number</span>
                <span className="order-number">{orderNumber}</span>
              </div>
              <span className="order-status">
                <Clock size={16} />
                Processing
              </span>
            </div>

            <div className="order-product">
              <div className="order-product-info">
                <h3>{product.name}</h3>
                <span>{product.licenseType}</span>
              </div>
              <span className="order-price">${product.discountedPrice}</span>
            </div>

            <div className="order-total">
              <span>Total Paid</span>
              <span>${product.discountedPrice}</span>
            </div>
          </div>

          <div className="next-steps">
            <h3>What Happens Next?</h3>
            <div className="steps-list">
              <div className="step-item">
                <div className="step-icon">
                  <Clock size={20} />
                </div>
                <div className="step-content">
                  <strong>Verification (1-2 hours)</strong>
                  <p>We verify your payment and prepare your license</p>
                </div>
              </div>
              <div className="step-item">
                <div className="step-icon">
                  <MessageCircle size={20} />
                </div>
                <div className="step-content">
                  <strong>Contact (Within 24 hours)</strong>
                  <p>Our sales team will reach out via WhatsApp or email</p>
                </div>
              </div>
              <div className="step-item">
                <div className="step-icon">
                  <Mail size={20} />
                </div>
                <div className="step-content">
                  <strong>Delivery</strong>
                  <p>You'll receive your license credentials and setup guide</p>
                </div>
              </div>
            </div>
          </div>

          <div className="confirmation-actions">
            <Link to="/" className="browse-more-btn">
              Browse More Deals <ArrowRight size={18} />
            </Link>
          </div>

          <p className="support-note">
            Have questions? Contact us at <a href="mailto:support@saasdeals.com">support@saasdeals.com</a>
          </p>
        </div>
      </div>
    </div>
  )
}

export default ConfirmationPage
