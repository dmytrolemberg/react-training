import { type ReactElement } from 'react';
import { PRODUCT_PREVIEWS } from '@/entities/product';
import './Home.css';
import { BRAND_PREVIEWS } from '@/entities/brand';
import MinimalOnlineShop from './MinimalOnlineShop.tsx';
import FavoriteProduct from './FavoriteProduct.tsx';
import FastCheckout from './FastCheckout.tsx';
import BrandList from '@/widgets/BrandList.tsx';
import ProductsList from '@/widgets/ProductsList.tsx';

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

      <BrandList brands={brands} title="Clear brands with useful attributes." label="Shop by brand" />
      <ProductsList products={products} title="High-rated products in stock." label="Featured products" />
    </>
  );
}

export default Home;
