import { type ReactElement } from 'react';
import { ProductCard, PRODUCT_PREVIEWS } from '@/entities/product';
import { ROUTES } from '@/shared/model/routes';
import Button from '@/shared/ui/Button.tsx';
import SearchForm from '@/shared/ui/SearchForm.tsx';
import './Home.css';

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
          <ProductCard product={PRODUCT_PREVIEWS[2]} />

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
          {PRODUCT_PREVIEWS.map((product) => (
            <ProductCard key={product.slug} product={product} />
          ))}
        </div>
      </section>
    </>
  );
}

export default Home;
