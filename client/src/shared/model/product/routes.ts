import { ROUTES } from '../routes.ts';

export const PRODUCTS_SEARCH_PARAMS = {
  BRAND_ID: 'brand_id',
} as const;

interface ProductsRouteOptions {
  readonly brandId?: number;
}

export function buildProductsRoute({
  brandId,
}: ProductsRouteOptions = {}): string {
  const searchParams = new URLSearchParams();

  if (brandId !== undefined) {
    searchParams.set(PRODUCTS_SEARCH_PARAMS.BRAND_ID, String(brandId));
  }

  const search = searchParams.toString();

  return search === '' ? ROUTES.PRODUCTS : `${ROUTES.PRODUCTS}?${search}`;
}
