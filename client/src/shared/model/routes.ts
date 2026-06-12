import 'react-router-dom';

export const ROUTES = {
  HOME: '/',
  LOGIN: '/login',
  REGISTER: '/register',
  RESET_PASSWORD: '/reset-password',
  PROFILE: '/profile',
  PRODUCTS: '/products',
  PRODUCT: '/products/:slug',
  CART: '/cart',
} as const;

export interface PathParams {
  [ROUTES.PRODUCT]: {
    slug: string;
  }
}


declare module 'react-router-dom' {
  interface Register {
    params: PathParams;
  }
}
