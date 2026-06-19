import { type ReactElement } from 'react';
import { ROUTES } from '@/shared/config/routes/routes.ts';
import Button from '@/shared/ui/Button.tsx';
import SearchForm from '@/widgets/SearchForm.tsx';

function MinimalOnlineShop(): ReactElement {
  return (
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
      <SearchForm className="section" />
    </div>
  );
}

export default MinimalOnlineShop;
