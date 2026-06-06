import { type ReactElement } from 'react';
import './Home.css';

function Home(): ReactElement {

  return (
    <>
      <section className="home-hero">
        <div className="home-hero-copy">
          <p className="eyebrow">Minimal online shop</p>
          <h1 className="title-xl">
            Essentials, sorted with calm precision.
          </h1>
          <p className="lead">
            A clear e-commerce interface for products with categories,
            brands, attributes, ratings, reviews, cart, checkout, profile,
            and orders.
          </p>
          <div className="cluster section">
            <a className="primary-button" href="products.html">
              Explore products →
            </a>
            <a className="secondary-button" href="brands.html">
              Browse brands
            </a>
          </div>
          <div className="search-box section" role="search">
            <span aria-hidden="true">⌕</span>
            <input
              type="search"
              placeholder="Search jackets, lamps, bags, accessories..."
            />
          </div>
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
            <a className="primary-button button-full" href="product.html">
              View product
            </a>
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
            <a className="secondary-button section" href="checkout.html">
              Continue checkout
            </a>
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
          <a className="ghost-button" href="brands.html">
            See all →
          </a>
        </div>
        <div className="grid grid-4 section">
          <a className="category-card" href="products.html">
            <span className="badge">Outerwear</span>
            <h3 className="title-sm section">Weather-ready pieces</h3>
            <p className="muted text-small">
              Filter by material, size, color, season, and rating.
            </p>
          </a>
          <a className="category-card" href="products.html">
            <span className="badge">Home</span>
            <h3 className="title-sm section">Quiet objects</h3>
            <p className="muted text-small">
              Lighting, desk tools, storage, and decor.
            </p>
          </a>
          <a className="category-card" href="products.html">
            <span className="badge">Bags</span>
            <h3 className="title-sm section">Daily carry</h3>
            <p className="muted text-small">
              Volume, laptop size, textile, color, availability.
            </p>
          </a>
          <a className="category-card" href="products.html">
            <span className="badge">Accessories</span>
            <h3 className="title-sm section">Small essentials</h3>
            <p className="muted text-small">
              Minimal add-ons with clean product details.
            </p>
          </a>
        </div>
      </section>

      <section className="section">
        <div className="split">
          <div>
            <p className="eyebrow">Featured products</p>
            <h2 className="title-md">High-rated products in stock.</h2>
          </div>
          <a className="ghost-button" href="products.html">
            View catalog →
          </a>
        </div>
        <div className="grid grid-3 section">
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Aero Knit Jacket</h3>
                <p className="product-meta">Northline · Outerwear</p>
              </div>
              <span className="price">$148</span>
            </div>
            <div className="attribute-list">
              <span className="badge">Graphite</span>
              <span className="badge">Waterproof</span>
              <span className="badge">XS–XL</span>
            </div>
            <div className="split">
              <span className="rating">
                <span className="stars">★★★★★</span> 4.8
              </span>
              <a className="secondary-button" href="product.html">
                Details
              </a>
            </div>
          </article>
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Modular Desk Lamp</h3>
                <p className="product-meta">Luma · Home</p>
              </div>
              <span className="price">$89</span>
            </div>
            <div className="attribute-list">
              <span className="badge">Aluminum</span>
              <span className="badge">USB-C</span>
              <span className="badge">Warm light</span>
            </div>
            <div className="split">
              <span className="rating">
                <span className="stars">★★★★★</span> 4.7
              </span>
              <a className="secondary-button" href="product.html">
                Details
              </a>
            </div>
          </article>
          <article className="product-card">
            <div className="product-media"></div>
            <div className="split">
              <div>
                <h3 className="product-title">Everyday Carry Pack</h3>
                <p className="product-meta">Mori · Bags</p>
              </div>
              <span className="price">$112</span>
            </div>
            <div className="attribute-list">
              <span className="badge">18L</span>
              <span className="badge">Recycled</span>
              <span className="badge">Laptop 15”</span>
            </div>
            <div className="split">
              <span className="rating">
                <span className="stars">★★★★★</span> 4.9
              </span>
              <a className="secondary-button" href="product.html">
                Details
              </a>
            </div>
          </article>
        </div>
      </section>
    </>
  );
}

export default Home;
