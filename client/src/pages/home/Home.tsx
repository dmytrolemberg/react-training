import { type ReactElement } from 'react';
import { ROUTES } from '@/shared/model/routes';
import Button from '@/shared/ui/Button.tsx';
import './Home.css';
import SearchForm from '@/shared/ui/SearchForm.tsx';
import ProductCard from '@/shared/ui/ProductCard.tsx';

interface Product {
  name: string;
  slug: string;
  brand: string;
  category: string;
  attribute_values: Record<number, string>;
  price: number;
  rating: number;
  reviews: number;
  stock: boolean;
}

const products: Product[] = [
  {
    name: 'Aero Knit Jacket',
    slug: 'aero-knit-jacket',
    brand: 'Northline',
    category: 'Outerwear',
    attribute_values: ['Graphite', 'Waterproof', 'XS–XL'],
    price: 148,
    rating: 4.2,
    reviews: 120,
    stock: true,
  },
  {
    name: 'Modular Desk Lamp',
    slug: 'modular-desk-lamp',
    brand: 'Luma',
    category: 'Home',
    attribute_values: ['Aluminum', 'USB-C', 'Warm light'],
    price: 89,
    rating: 4.5,
    reviews: 54,
    stock: true,
  },
  {
    name: 'Everyday Carry Pack',
    slug: 'everyday-carry-pack',
    brand: 'Mori',
    category: 'Bags',
    attribute_values: ['18L', 'Recycled', 'Laptop 15”'],
    price: 112,
    rating: 3.3,
    reviews: 219,
    stock: true,
  },
];

function Home(): ReactElement {

  return (
    <>
      <section className="home-hero">
        <div className="home-hero-copy">
          <p className="eyebrow">Minimal online shop</p>
          <h1 className="title-xl">Essentials, sorted with calm precision.</h1>
          <p className="lead">
            A clear e-commerce interface for products with categories, brands,
            attributes, ratings, reviews, cart, checkout, profile, and orders.
          </p>
          <div className="cluster section">
            <Button className="primary-button" to={ROUTES.PRODUCTS}>
              Explore products →
            </Button>
            <Button className="secondary-button" to={ROUTES.BRANDS}>
              Browse brands
            </Button>
          </div>
          <SearchForm
            className={'section'}
            placeholder={'Search products, brands, categories...'}
          />
        </div>

        <div className="home-hero-side">
          <article className="home-featured-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <p className="product-title">Everyday Carry Pack</p>
                <p className="product-meta">Mori · Bags · 18L · Recycled</p>
              </div>
              <span className="price">$112</span>
            </div>
            <div className="attribute-list">
              <span className="badge">Laptop 15”</span>
              <span className="badge">Water resistant</span>
              <span className="badge">4.9 rating</span>
            </div>
            <Button
              className="primary-button button-full"
              to={'sdfsdf-sdf-sdf'}
            >
              View product
            </Button>
          </article>

          <aside className="home-checkout-preview">
            <p
              className="eyebrow"
              style={{ color: 'currentColor', opacity: 0.55 }}
            >
              Fast checkout
            </p>
            <h2 className="title-sm" style={{ color: 'currentColor' }}>
              2 items ready
            </h2>
            <p className="text-small" style={{ opacity: 0.68 }}>
              Free delivery, saved address, secure payment, order tracking.
            </p>
            <Button className="secondary-button section" to={ROUTES.CHECKOUT}>
              Continue checkout
            </Button>
          </aside>
        </div>
      </section>

      <section className="section">
        <div className="split">
          <div>
            <p className="eyebrow">Shop by category</p>
            <h2 className="title-md">
              Clear categories with useful attributes.
            </h2>
          </div>
          <Button className="ghost-button" to={ROUTES.BRANDS}>
            See all →
          </Button>
        </div>
        <div className="grid grid-4 section">
          <Button className="category-card" to={ROUTES.PRODUCTS}>
            <span className="badge">Outerwear</span>
            <h3 className="title-sm section">Weather-ready pieces</h3>
            <p className="muted text-small">
              Filter by material, size, color, season, and rating.
            </p>
          </Button>
          <Button className="category-card" to={ROUTES.PRODUCTS}>
            <span className="badge">Home</span>
            <h3 className="title-sm section">Quiet objects</h3>
            <p className="muted text-small">
              Lighting, desk tools, storage, and decor.
            </p>
          </Button>
          <Button className="category-card" to={ROUTES.PRODUCTS}>
            <span className="badge">Bags</span>
            <h3 className="title-sm section">Daily carry</h3>
            <p className="muted text-small">
              Volume, laptop size, textile, color, availability.
            </p>
          </Button>
          <Button className="category-card" to={ROUTES.PRODUCTS}>
            <span className="badge">Accessories</span>
            <h3 className="title-sm section">Small essentials</h3>
            <p className="muted text-small">
              Minimal add-ons with clean product details.
            </p>
          </Button>
        </div>
      </section>

      <section className="section">
        <div className="split">
          <div>
            <p className="eyebrow">Featured products</p>
            <h2 className="title-md">High-rated products in stock.</h2>
          </div>
          <Button className="ghost-button" to={ROUTES.PRODUCTS}>
            View catalog →
          </Button>
        </div>
        <div className="grid grid-3 section">
          {products.map((product) => (
            <ProductCard key={product.slug} product={product} />
          ))}
        </div>
      </section>
    </>
  );
}

export default Home;
