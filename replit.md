# SaaSDeals - Premium Software Marketplace

## Overview
SaaSDeals is a modern, high-conversion ecommerce website for selling discounted software and SaaS subscriptions. The website features a clean, professional design with trust indicators, discount visibility, and a transparent delivery process.

## Tech Stack
- **Frontend**: React 18 with Vite
- **Routing**: React Router DOM v6
- **Icons**: Lucide React
- **Styling**: Custom CSS with CSS Variables

## Project Structure
```
src/
├── components/          # Reusable UI components
│   ├── Header.jsx/css   # Navigation and trust bar
│   ├── Footer.jsx/css   # Site footer with links
│   └── ProductCard.jsx/css  # Product listing card
├── pages/               # Route pages
│   ├── HomePage.jsx/css       # Product listing with hero
│   ├── ProductPage.jsx/css    # Product details
│   ├── PaymentPage.jsx/css    # Checkout form
│   ├── DetailsFormPage.jsx/css  # Post-payment info collection
│   └── ConfirmationPage.jsx/css # Order confirmation
├── data/
│   └── products.js      # Product data and helper functions
├── App.jsx              # Main app with routing
├── main.jsx             # Entry point
└── index.css            # Global styles and CSS variables
```

## Key Features
1. **Homepage**: Hero section with trust badges, featured deals grid
2. **Product Page**: Detailed info, delivery process, mandatory checkbox
3. **Payment Page**: Clean checkout with order summary
4. **Details Form**: Post-payment info collection (name, email, WhatsApp)
5. **Confirmation**: Order summary with next steps

## Design System
- Primary: #3B82F6 (Blue)
- Success: #10B981 (Green - used for discount badges)
- Warning: #F59E0B (Orange)
- Font: Inter (Google Fonts)

## Running the Project
```bash
npm run dev    # Start development server on port 5000
npm run build  # Build for production
```

## Recent Changes
- Initial project setup with all 5 pages
- Mobile-responsive design
- Trust indicators and security badges
- Manual delivery process explanation
