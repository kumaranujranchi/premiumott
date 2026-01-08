import { Link } from 'react-router-dom'
import { Star, ArrowRight, Users, Rocket, BarChart2, Database, FileText, Video, CheckSquare, Mail } from 'lucide-react'
import './ProductCard.css'

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

function ProductCard({ product }) {
  const Icon = iconMap[product.icon] || Users

  return (
    <div className="product-card">
      <div className="discount-badge">{product.discountPercent}% OFF</div>
      
      <div className="product-icon" style={{ background: `${product.color}15` }}>
        <Icon size={32} color={product.color} />
      </div>

      <div className="product-header">
        <h3 className="product-name">{product.name}</h3>
        <div className="product-rating">
          <Star size={14} fill="#F59E0B" color="#F59E0B" />
          <span>{product.rating}</span>
        </div>
      </div>

      <p className="product-tagline">{product.tagline}</p>
      <p className="product-description">{product.description}</p>

      <div className="product-pricing">
        <div className="prices">
          <span className="price-current">${product.discountedPrice}</span>
          <span className="price-original">${product.originalPrice}</span>
        </div>
        <span className="license-badge">{product.licenseType}</span>
      </div>

      <Link to={`/product/${product.id}`} className="view-deal-btn">
        View Deal <ArrowRight size={16} />
      </Link>
    </div>
  )
}

export default ProductCard
