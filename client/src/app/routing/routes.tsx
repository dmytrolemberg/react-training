import { ROUTES } from '@/shared/model/routes.ts';
import {
  createBrowserRouter,
  type LazyRouteFunction,
  type NonIndexRouteObject,
} from 'react-router-dom';
import App from '@/app';

type LazyRouteResult = ReturnType<LazyRouteFunction<NonIndexRouteObject>>;

export const router = createBrowserRouter([
  {
    path: ROUTES.HOME,
    element: <App />,
    children: [
      {
        path: ROUTES.LOGIN,
        lazy: async (): LazyRouteResult => {
          const { default: Login } = await import('@/pages/auth/login');

          return { Component: Login };
        },
      },
      {
        path: ROUTES.REGISTER,
        lazy: async (): LazyRouteResult => {
          const { default: Register } = await import('@/pages/auth/register');

          return { Component: Register };
        },
      },
      {
        path: ROUTES.RESET_PASSWORD,
        lazy: async (): LazyRouteResult => {
          const { default: ResetPassword } =
            await import('@/pages/auth/reset-password');

          return { Component: ResetPassword };
        },
      },
      {
        path: ROUTES.PROFILE,
        lazy: async (): LazyRouteResult => {
          const { default: Profile } = await import('@/pages/profile');

          return { Component: Profile };
        },
      },
      {
        path: ROUTES.REVIEWS,
        lazy: async (): LazyRouteResult => {
          const { default: Reviews } = await import('@/pages/reviews');

          return { Component: Reviews };
        },
      },
      {
        path: ROUTES.PRODUCTS,
        lazy: async (): LazyRouteResult => {
          const { default: Products } = await import('@/pages/products');

          return { Component: Products };
        },
      },
      {
        path: ROUTES.PRODUCT,
        lazy: async (): LazyRouteResult => {
          const { default: Product } = await import('@/pages/product');

          return { Component: Product };
        },
      },
      {
        path: ROUTES.CART,
        lazy: async (): LazyRouteResult => {
          const { default: Cart } = await import('@/pages/cart');

          return { Component: Cart };
        },
      },
      {
        path: ROUTES.CHECKOUT,
        lazy: async (): LazyRouteResult => {
          const { default: Checkout } = await import('@/pages/checkout');

          return { Component: Checkout };
        },
      },
      {
        path: ROUTES.ORDERS,
        lazy: async (): LazyRouteResult => {
          const { default: Orders } = await import('@/pages/orders');

          return { Component: Orders };
        },
      },
      {
        path: ROUTES.ORDER,
        lazy: async (): LazyRouteResult => {
          const { default: Order } = await import('@/pages/order');

          return { Component: Order };
        },
      },
      {
        index: true,
        lazy: async (): LazyRouteResult => {
          const { default: Home } = await import('@/pages/home');

          return { Component: Home };
        },
      },
    ],
  },
]);
