import { type ReactElement } from 'react';
import { NavLink } from 'react-router-dom';
import { ROUTES } from '@/shared/config/routes/routes.ts';

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
      <NavLink className={getNavLinkClassName} to={ROUTES.ORDERS}>
        Orders
      </NavLink>
      <NavLink className={getNavLinkClassName} to={ROUTES.REVIEWS}>
        Reviews
      </NavLink>
    </nav>
  );
}

export default Navbar;
