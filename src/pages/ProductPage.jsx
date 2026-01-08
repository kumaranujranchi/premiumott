import { useState } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { Star, Check, CreditCard, Mail, MessageCircle, Package, ChevronLeft, Users, Rocket, BarChart2, Database, FileText, Video, CheckSquare } from 'lucide-react'
import { getProductById } from '../data/products'
import './ProductPage.css'

const iconMap = {
  users: Users,
  rocket: Rocket,
  barchart: BarChart2,
  database: Database,
  form: FileText,
  video: Video,
  tasks: CheckSquare,
  mail: Mail,
}

function ProductPage() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [deliveryChecked, setDeliveryChecked] = useState(false)
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

  const Icon = iconMap[product.icon] || Users

  const handleProceed = () => {
    if (deliveryChecked) {
      navigate(`/payment/${product.id}`)
    }
  }

  return (
    <div className="product-page">
      <div className="container">
        <Link to="/" className="back-link">
          <ChevronLeft size={20} />
          Back to deals
        </Link>

        <div className="product-detail-grid">
          <div className="product-main">
            <div className="product-hero-icon" style={{ background: `${product.color}15` }}>
              <Icon size={48} color={product.color} />
            </div>
            
            <div className="product-badges">
              <span className="discount-badge-lg">{product.discountPercent}% OFF</span>
              <span className="category-badge">{product.category}</span>
            </div>

            <h1 className="product-title">{product.name}</h1>
            <p className="product-tagline-lg">{product.tagline}</p>

            <div className="product-rating-lg">
              <div className="stars">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} size={18} fill="#F59E0B" color="#F59E0B" />
                ))}
              </div>
              <span>{product.rating} ({product.reviews} reviews)</span>
            </div>

            <p className="product-description-lg">{product.description}</p>

            <div className="features-list">
              <h3>What's Included</h3>
              <div className="features-grid">
                {product.features.map((feature, index) => (
                  <div key={index} className="feature-item">
                    <Check size={18} className="feature-check" />
                    <span>{feature}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="product-sidebar">
            <div className="pricing-card">
              <div className="pricing-header">
                <div className="price-display">
                  <span className="price-discounted">${product.discountedPrice}</span>
                  <span className="price-original-lg">${product.originalPrice}</span>
                </div>
                <span className="license-type">{product.licenseType}</span>
              </div>

              <div className="savings-banner">
                You save ${product.originalPrice - product.discountedPrice} ({product.discountPercent}% off)
              </div>

              <div className="delivery-section">
                <h4>
                  <Package size={18} />
                  How You Will Get This Product
                </h4>
                <ol className="delivery-steps">
                  <li>
                    <CreditCard size={16} />
                    <span>Pay securely via our checkout</span>
                  </li>
                  <li>
                    <Mail size={16} />
                    <span>Provide your required details</span>
                  </li>
                  <li>
                    <MessageCircle size={16} />
                    <span>Our sales team contacts you</span>
                  </li>
                  <li>
                    <Check size={16} />
                    <span>Access delivered manually</span>
                  </li>
                </ol>
              </div>

              <label className="delivery-checkbox">
                <input 
                  type="checkbox" 
                  checked={deliveryChecked}
                  onChange={(e) => setDeliveryChecked(e.target.checked)}
                />
                <span className="checkbox-custom"></span>
                <span className="checkbox-label">I understand the delivery process</span>
              </label>

              <button 
                className={`proceed-btn ${deliveryChecked ? 'active' : 'disabled'}`}
                onClick={handleProceed}
                disabled={!deliveryChecked}
              >
                Proceed to Payment
              </button>

              <p className="guarantee-text">
                30-day money-back guarantee
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default ProductPage
