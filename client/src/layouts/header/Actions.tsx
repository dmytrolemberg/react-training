import { type ReactElement } from 'react';
import { BsBoxArrowInRight, BsCart3, BsPersonCircle } from 'react-icons/bs';
import { NavLink } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

function getActionClassName({ isActive }: { isActive: boolean }): string {
  return isActive ? 'icon-button is-active' : 'icon-button';
}

function Actions(): ReactElement {

  return (
    <div className="header-actions">
      <NavLink
        className={getActionClassName}
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
      <a className="icon-button" href="cart.html" aria-label="Cart">
        <BsCart3 aria-hidden="true" />
        <span className="cart-count">2</span>
      </a>
    </div>
  );
}

export default Actions;
