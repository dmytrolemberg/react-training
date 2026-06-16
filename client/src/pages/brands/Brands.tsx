import { type ReactElement } from 'react';
import './Brands.css';
import { Link } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';
import Button from '@/shared/ui/Button.tsx';

function Brands(): ReactElement {
  return (
    <>
      <section className="split">
        <div>
          <p className="eyebrow">Brands & categories</p>
          <h1 className="title-lg">A simple structure for discovery.</h1>
          <p className="lead">
            Use this page as a directory for categories, brands, and key product
            attributes.
          </p>
        </div>
        <Button to={ROUTES.PRODUCTS}>
          Open catalog
        </Button>
      </section>

      <section className="section">
        <p className="eyebrow">Categories</p>
        <div className="category-strip">
          <Link className="category-pill-card" to={ROUTES.PRODUCTS}>
            <strong>Outerwear</strong>
            <p className="muted text-small">Jackets, layers, weather pieces</p>
          </Link>
          <Link className="category-pill-card" to={ROUTES.PRODUCTS}>
            <strong>Home</strong>
            <p className="muted text-small">Lighting, desk, storage</p>
          </Link>
          <Link className="category-pill-card" to={ROUTES.PRODUCTS}>
            <strong>Bags</strong>
            <p className="muted text-small">Backpacks, pouches, carry</p>
          </Link>
          <Link className="category-pill-card" to={ROUTES.PRODUCTS}>
            <strong>Accessories</strong>
            <p className="muted text-small">Beanies, wallets, small goods</p>
          </Link>
          <Link className="category-pill-card" to={ROUTES.PRODUCTS}>
            <strong>Electronics</strong>
            <p className="muted text-small">Cables, docks, essentials</p>
          </Link>
        </div>
      </section>

      <section className="section">
        <div className="split">
          <div>
            <p className="eyebrow">Brands</p>
            <h2 className="title-md">Focused brand cards.</h2>
          </div>
          <div className="search-box" style={{ maxWidth: 360 }}>
            <span aria-hidden="true">⌕</span>
            <input
              type="search"
              id="brandSearch"
              placeholder="Search brand..."
            />
          </div>
        </div>
        <div className="brand-directory section" id="brandDirectory">
          <Link
            className="brand-card"
            to={ROUTES.PRODUCTS}
            data-brand="Northline"
          >
            <div className="brand-logo">N</div>
            <h3 className="title-sm">Northline</h3>
            <p className="muted text-small">
              Outerwear and accessories focused on calm utility.
            </p>
            <div className="attribute-list">
              <span className="badge">Outerwear</span>
              <span className="badge">Accessories</span>
            </div>
          </Link>
          <Link className="brand-card" to={ROUTES.PRODUCTS} data-brand="Luma">
            <div className="brand-logo">L</div>
            <h3 className="title-sm">Luma</h3>
            <p className="muted text-small">
              Minimal home objects, lamps, and warm lighting.
            </p>
            <div className="attribute-list">
              <span className="badge">Home</span>
              <span className="badge">Lighting</span>
            </div>
          </Link>
          <Link className="brand-card" to={ROUTES.PRODUCTS} data-brand="Mori">
            <div className="brand-logo">M</div>
            <h3 className="title-sm">Mori</h3>
            <p className="muted text-small">
              Daily carry bags with recycled materials.
            </p>
            <div className="attribute-list">
              <span className="badge">Bags</span>
              <span className="badge">Travel</span>
            </div>
          </Link>
          <Link
            className="brand-card"
            to={ROUTES.PRODUCTS}
            data-brand="Studio Base"
          >
            <div className="brand-logo">S</div>
            <h3 className="title-sm">Studio Base</h3>
            <p className="muted text-small">
              Desk tools, organizers, and quiet workspace details.
            </p>
            <div className="attribute-list">
              <span className="badge">Desk</span>
              <span className="badge">Home</span>
            </div>
          </Link>
          <Link className="brand-card" to={ROUTES.PRODUCTS} data-brand="Ever">
            <div className="brand-logo">E</div>
            <h3 className="title-sm">Ever</h3>
            <p className="muted text-small">
              Timeless essentials for everyday use.
            </p>
            <div className="attribute-list">
              <span className="badge">Essentials</span>
              <span className="badge">Basics</span>
            </div>
          </Link>
          <Link
            className="brand-card"
            to={ROUTES.PRODUCTS}
            data-brand="Plain Works"
          >
            <div className="brand-logo">P</div>
            <h3 className="title-sm">Plain Works</h3>
            <p className="muted text-small">
              Clear, simple objects with practical attributes.
            </p>
            <div className="attribute-list">
              <span className="badge">Objects</span>
              <span className="badge">Utility</span>
            </div>
          </Link>
        </div>
      </section>
    </>
  );
}

export default Brands;
