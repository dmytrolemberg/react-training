import { type ReactElement } from 'react';
import { BsAmazon } from 'react-icons/bs';

function Header(): ReactElement {
  return (
    <header className="site-header">
      <a
        className="brand"
        href="../index.html"
        aria-label="North Shop home"
      >
        <span className="brand-mark">
          <BsAmazon />
        </span>
        <span className="brand-text">North Shop</span>
      </a>
      <nav className="site-nav" aria-label="Main navigation">
        <a className="nav-link is-active" href="../index.html">
          Home
        </a>
        <a className="nav-link" href="products.html">
          Products
        </a>
        <a className="nav-link" href="brands.html">
          Brands
        </a>
        <a className="nav-link" href="orders.html">
          Orders
        </a>
        <a className="nav-link" href="reviews.html">
          Reviews
        </a>
      </nav>
      <div className="header-actions">
        <a className="icon-button" href="profile.html" aria-label="Profile">
          ◌
        </a>
        <a className="icon-button" href="cart.html" aria-label="Cart">
          ⌁<span className="cart-count">2</span>
        </a>
      </div>
    </header>
  );
}

export default Header;
