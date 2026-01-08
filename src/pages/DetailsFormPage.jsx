import { useState } from 'react'
import { useParams, useNavigate, Link } from 'react-router-dom'
import { CheckCircle, ChevronLeft } from 'lucide-react'
import { getProductById } from '../data/products'
import './DetailsFormPage.css'

function DetailsFormPage() {
  const { id } = useParams()
  const navigate = useNavigate()
  const product = getProductById(id)
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    whatsapp: '',
    requirements: ''
  })

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
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    })
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    navigate(`/confirmation/${product.id}`)
  }

  return (
    <div className="details-page">
      <div className="container">
        <div className="details-content">
          <div className="success-banner">
            <CheckCircle size={48} />
            <h2>Payment Successful!</h2>
            <p>Thank you for your purchase. Please provide your details below so we can deliver your product.</p>
          </div>

          <div className="details-card">
            <div className="product-reminder">
              <span className="reminder-label">Your Purchase:</span>
              <span className="reminder-product">{product.name} - {product.licenseType}</span>
            </div>

            <form onSubmit={handleSubmit}>
              <div className="form-group">
                <label>Full Name *</label>
                <input 
                  type="text" 
                  name="name"
                  value={formData.name}
                  onChange={handleChange}
                  placeholder="John Smith"
                  required
                />
              </div>

              <div className="form-group">
                <label>Email Address *</label>
                <input 
                  type="email" 
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="you@example.com"
                  required
                />
                <span className="form-hint">We'll send order updates to this email</span>
              </div>

              <div className="form-group">
                <label>WhatsApp Number *</label>
                <input 
                  type="tel" 
                  name="whatsapp"
                  value={formData.whatsapp}
                  onChange={handleChange}
                  placeholder="+1 234 567 8900"
                  required
                />
                <span className="form-hint">Our sales team will contact you on WhatsApp</span>
              </div>

              <div className="form-group">
                <label>Additional Requirements</label>
                <textarea 
                  name="requirements"
                  value={formData.requirements}
                  onChange={handleChange}
                  placeholder="Any specific requirements for your license? (e.g., preferred email for account, number of users needed)"
                  rows={4}
                />
              </div>

              <button type="submit" className="submit-btn">
                Submit Details
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  )
}

export default DetailsFormPage
