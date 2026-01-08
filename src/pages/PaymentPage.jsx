import { useState } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { ChevronLeft, CreditCard, Shield, Lock, AlertCircle } from 'lucide-react'
import { getProductById } from '../data/products'
import './PaymentPage.css'

function PaymentPage() {
  const { id } = useParams()
  const navigate = useNavigate()
  const product = getProductById(id)
  const [formData, setFormData] = useState({
    cardholderName: '',
    cardNumber: '',
    expiry: '',
    cvv: '',
    email: ''
  })
  const [errors, setErrors] = useState({})

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

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
    if (errors[e.target.name]) {
      setErrors({ ...errors, [e.target.name]: '' })
    }
  }

  const validateForm = () => {
    const newErrors = {}
    if (!formData.cardholderName.trim()) newErrors.cardholderName = 'Name is required'
    if (!formData.cardNumber.trim()) newErrors.cardNumber = 'Card number is required'
    if (!formData.expiry.trim()) newErrors.expiry = 'Expiry is required'
    if (!formData.cvv.trim()) newErrors.cvv = 'CVV is required'
    if (!formData.email.trim()) newErrors.email = 'Email is required'
    setErrors(newErrors)
    return Object.keys(newErrors).length === 0
  }

  const handlePayment = () => {
    if (validateForm()) {
      navigate(`/details/${product.id}`)
    }
  }

  return (
    <div className="payment-page">
      <div className="container">
        <Link to={`/product/${id}`} className="back-link">
          <ChevronLeft size={20} />
          Back to product
        </Link>

        <div className="payment-grid">
          <div className="payment-form-section">
            <h1 className="payment-title">Complete Your Purchase</h1>
            
            <div className="payment-form">
              <h3>Payment Details</h3>
              
              <div className="form-group">
                <label>Cardholder Name</label>
                <input 
                  type="text" 
                  name="cardholderName"
                  value={formData.cardholderName}
                  onChange={handleChange}
                  placeholder="John Smith"
                  className={errors.cardholderName ? 'error' : ''}
                />
                {errors.cardholderName && <span className="error-msg">{errors.cardholderName}</span>}
              </div>

              <div className="form-group">
                <label>Card Number</label>
                <div className="card-input-wrapper">
                  <input 
                    type="text" 
                    name="cardNumber"
                    value={formData.cardNumber}
                    onChange={handleChange}
                    placeholder="1234 5678 9012 3456"
                    className={errors.cardNumber ? 'error' : ''}
                  />
                  <CreditCard size={20} className="card-icon" />
                </div>
                {errors.cardNumber && <span className="error-msg">{errors.cardNumber}</span>}
              </div>

              <div className="form-row">
                <div className="form-group">
                  <label>Expiry Date</label>
                  <input 
                    type="text" 
                    name="expiry"
                    value={formData.expiry}
                    onChange={handleChange}
                    placeholder="MM/YY"
                    className={errors.expiry ? 'error' : ''}
                  />
                  {errors.expiry && <span className="error-msg">{errors.expiry}</span>}
                </div>
                <div className="form-group">
                  <label>CVV</label>
                  <input 
                    type="text" 
                    name="cvv"
                    value={formData.cvv}
                    onChange={handleChange}
                    placeholder="123"
                    className={errors.cvv ? 'error' : ''}
                  />
                  {errors.cvv && <span className="error-msg">{errors.cvv}</span>}
                </div>
              </div>

              <div className="form-group">
                <label>Email Address</label>
                <input 
                  type="email" 
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="you@example.com"
                  className={errors.email ? 'error' : ''}
                />
                {errors.email && <span className="error-msg">{errors.email}</span>}
              </div>

              <div className="security-badges">
                <div className="badge">
                  <Shield size={16} />
                  256-bit SSL
                </div>
                <div className="badge">
                  <Lock size={16} />
                  Secure Payment
                </div>
              </div>

              <div className="delivery-reminder">
                <AlertCircle size={18} />
                <div>
                  <strong>Manual Delivery Notice</strong>
                  <p>This product will be delivered manually by our sales team within 24-48 hours after payment verification.</p>
                </div>
              </div>

              <button className="pay-btn" onClick={handlePayment}>
                Pay ${product.discountedPrice}
              </button>
            </div>
          </div>

          <div className="order-summary">
            <h3>Order Summary</h3>
            
            <div className="summary-product">
              <div className="summary-product-info">
                <h4>{product.name}</h4>
                <span className="license-tag">{product.licenseType}</span>
              </div>
            </div>

            <div className="summary-line">
              <span>Original Price</span>
              <span className="original-price">${product.originalPrice}</span>
            </div>
            
            <div className="summary-line discount">
              <span>Discount ({product.discountPercent}% off)</span>
              <span>-${product.originalPrice - product.discountedPrice}</span>
            </div>

            <div className="summary-divider"></div>

            <div className="summary-line total">
              <span>Total</span>
              <span>${product.discountedPrice}</span>
            </div>

            <div className="guarantee-box">
              <Shield size={20} />
              <div>
                <strong>30-Day Money Back Guarantee</strong>
                <p>Not satisfied? Get a full refund, no questions asked.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default PaymentPage
