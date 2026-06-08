import { type ReactElement } from 'react';
import { NavLink } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

function getNavLinkClassName({ isActive }: { isActive: boolean }): string {
  return isActive ? 'nav-link is-active' : 'nav-link';
}
function Navbar(): ReactElement {

  return (
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
  );
}

export default Navbar;
