import { type ReactElement } from 'react';
import { ProductCard, PRODUCT_PREVIEWS } from '@/entities/product';
import { ROUTES } from '@/shared/model/routes';
import Button from '@/shared/ui/Button.tsx';
import SearchForm from '@/shared/ui/SearchForm.tsx';
import './Home.css';
import { BRAND_PREVIEWS, BrandCard } from '@/entities/brand';

function Home(): ReactElement {

  const productPreview = PRODUCT_PREVIEWS[0];
  const products = PRODUCT_PREVIEWS;
  const brands = BRAND_PREVIEWS;

  return (
    <>
      <section className="home-hero">
        <div className="home-hero-copy">
          <p className="eyebrow">Minimal online shop</p>
          <h1 className="title-xl">Essentials, sorted with calm precision.</h1>
          <p className="lead">
            A clear e-commerce interface for products with categories, brands, attributes, ratings, reviews, cart,
            checkout, profile, and orders.
          </p>
          <div className="cluster section">
            <Button className="primary-button" to={ROUTES.PRODUCTS}>
              Explore products →
            </Button>
          </div>
          <SearchForm className={'section'} placeholder={'Search products, brands, categories...'} />
        </div>

        <div className="home-hero-side">
          <ProductCard product={productPreview} />

          <aside className="home-checkout-preview">
            <p className="eyebrow" style={{ color: 'currentColor', opacity: 0.55 }}>
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
            <h2 className="title-md">Clear categories with useful attributes.</h2>
          </div>
        </div>
        <div className="grid grid-4 section">
          {brands.map((brand) => (
            <BrandCard key={brand.id} brand={brand} />
          ))}
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
