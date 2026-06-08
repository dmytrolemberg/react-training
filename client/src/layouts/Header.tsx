import { type ReactElement } from 'react';
import { BsAmazon, BsBoxArrowInRight } from 'react-icons/bs';
import { Link, NavLink, useLocation } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes';

function getNavLinkClassName({ isActive }: { isActive: boolean }): string {
  return isActive ? 'nav-link is-active' : 'nav-link';
}

function Header(): ReactElement {
  const location = useLocation();
  const isAuthPage =
    location.pathname === ROUTES.LOGIN ||
    location.pathname === ROUTES.REGISTER ||
    location.pathname === ROUTES.RESET_PASSWORD;

  return (
    <header className="site-header">
      <Link className="brand" to={ROUTES.HOME} aria-label="North Shop home">
        <span className="brand-mark">
          <BsAmazon />
        </span>
        <span className="brand-text">North Shop</span>
      </Link>
      {!isAuthPage && (
        <>
          <nav className="site-nav" aria-label="Main navigation">
            <NavLink className={getNavLinkClassName} to={ROUTES.HOME}>
              Home
            </NavLink>
            <NavLink className={getNavLinkClassName} to={ROUTES.PRODUCTS}>
              Products
            </NavLink>
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
            <Link className="icon-button" to={ROUTES.LOGIN}>
              <BsBoxArrowInRight aria-hidden="true" />
            </Link>
            <Link
              className="icon-button"
              to={ROUTES.PROFILE}
              aria-label="Profile"
            >
              ◌
            </Link>
            <a className="icon-button" href="cart.html" aria-label="Cart">
              ⌁<span className="cart-count">2</span>
            </a>
          </div>
        </>
      )}
    </header>
  );
}

export default Header;
