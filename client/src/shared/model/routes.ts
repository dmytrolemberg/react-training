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
  BRANDS: '/brands',
  REVIEWS: '/reviews',
  CHECKOUT: '/checkout',
  ORDERS: '/orders',
  ORDER: '/orders/:number',
} as const;

export interface PathParams {
  [ROUTES.PRODUCT]: {
    slug: string;
  };
  [ROUTES.ORDER]: {
    number: string;
  };
}


declare module 'react-router-dom' {
  interface Register {
    params: PathParams;
  }
}
