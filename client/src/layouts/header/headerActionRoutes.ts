import { ROUTES } from '@/shared/config/routes/routes.ts';

const AUTH_ACTION_ROUTES = [
  ROUTES.LOGIN,
  ROUTES.REGISTER,
  ROUTES.RESET_PASSWORD,
] as const;

const CART_ACTION_ROUTES = [ROUTES.CART, ROUTES.CHECKOUT] as const;

export function isAuthActionRoute(pathname: string): boolean {
  return AUTH_ACTION_ROUTES.some((route) => route === pathname);
}

export function isCartActionRoute(pathname: string): boolean {
  return CART_ACTION_ROUTES.some((route) => route === pathname);
}
