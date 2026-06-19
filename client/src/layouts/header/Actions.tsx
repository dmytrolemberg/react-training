import { type ReactElement } from 'react';
import { BsBoxArrowInRight, BsCart3, BsPersonCircle } from 'react-icons/bs';
import { NavLink, useLocation } from 'react-router-dom';
import {
  isAuthActionRoute,
  isCartActionRoute,
} from './headerActionRoutes.ts';
import { ROUTES } from '@/shared/config/routes/routes.ts';

function getActionClassName({ isActive }: { isActive: boolean }): string {
  return isActive ? 'icon-button is-active' : 'icon-button';
}

function Actions(): ReactElement {
  const { pathname } = useLocation();
  const isAuthActionActive = isAuthActionRoute(pathname);
  const isCartActionActive = isCartActionRoute(pathname);

  return (
    <div className="header-actions">
      <NavLink
        className={({ isActive }) =>
          getActionClassName({ isActive: isActive || isAuthActionActive })
        }
        to={ROUTES.LOGIN}
        aria-label="Login"
      >
        <BsBoxArrowInRight aria-hidden="true" />
      </NavLink>
      <NavLink
        className={getActionClassName}
        to={ROUTES.PROFILE}
        aria-label="Profile"
      >
        <BsPersonCircle aria-hidden="true" />
      </NavLink>
      <NavLink
        className={({ isActive }) =>
          getActionClassName({ isActive: isActive || isCartActionActive })
        }
        to={ROUTES.CART}
        aria-label="Cart"
      >
        <BsCart3 aria-hidden="true" />
        <span className="cart-count">2</span>
      </NavLink>
    </div>
  );
}

export default Actions;
