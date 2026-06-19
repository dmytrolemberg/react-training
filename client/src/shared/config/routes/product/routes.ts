import { ROUTES } from '../routes.ts';

export const PRODUCTS_SEARCH_PARAMS = {
  BRAND_ID: 'brand_id',
  SEARCH: 'search',
} as const;

interface ProductsRouteOptions {
  readonly brandId?: number;
  readonly search?: string;
}

export function buildProductsRoute({
  brandId,
  search,
}: ProductsRouteOptions = {}): string {
  const searchParams = new URLSearchParams();

  if (brandId !== undefined) {
    searchParams.set(PRODUCTS_SEARCH_PARAMS.BRAND_ID, String(brandId));
  }

  if (search !== undefined) {
    const normalizedSearch = search.trim();

    if (normalizedSearch === '') {
      searchParams.delete(PRODUCTS_SEARCH_PARAMS.SEARCH);
    } else {
      searchParams.set(PRODUCTS_SEARCH_PARAMS.SEARCH, normalizedSearch);
    }
  }

  const query = searchParams.toString();

  return query === '' ? ROUTES.PRODUCTS : `${ROUTES.PRODUCTS}?${query}`;
}
