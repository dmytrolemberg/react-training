import { type ReactElement } from 'react';
import { ProductCard, PRODUCT_PREVIEWS } from '@/entities/product';
import { ROUTES } from '@/shared/model/routes';
import Button from '@/shared/ui/Button.tsx';
import './Home.css';
import { BRAND_PREVIEWS, BrandCard } from '@/entities/brand';
import MinimalOnlineShop from './MinimalOnlineShop.tsx';
import FavoriteProduct from './FavoriteProduct.tsx';
import FastCheckout from './FastCheckout.tsx';

function Home(): ReactElement {

  const favoriteProduct = PRODUCT_PREVIEWS[0];
  const products = PRODUCT_PREVIEWS;
  const brands = BRAND_PREVIEWS;

  return (
    <>
      <section className="home-hero">
        <MinimalOnlineShop />

        <div className="home-hero-side">
          <FavoriteProduct favoriteProduct={favoriteProduct} />
          <FastCheckout />
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
