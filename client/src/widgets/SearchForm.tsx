import { type ReactElement, type SyntheticEvent } from 'react';
import { Form, useNavigate } from 'react-router-dom';
import {
  buildProductsRoute,
  PRODUCTS_SEARCH_PARAMS,
} from '@/shared/config/routes/product';

interface SearchFormProps {
  readonly className?: string;
}

function SearchForm({ className = '' }: SearchFormProps): ReactElement {
  const navigate = useNavigate();
  const productsRoute = buildProductsRoute();

  function handleSubmit(event: SyntheticEvent<HTMLFormElement>): void {
    event.preventDefault();

    const value = new FormData(event.currentTarget).get(
      PRODUCTS_SEARCH_PARAMS.SEARCH,
    );

    void navigate(
      buildProductsRoute({
        search: typeof value === 'string' ? value : '',
      }),
    );
  }

  return (
    <Form
      action={productsRoute}
      method="get"
      onSubmit={handleSubmit}
      role="search"
    >
      <div className={`search-box ${className}`}>
        <span aria-hidden="true">⌕</span>
        <input
          id="searchInput"
          name={PRODUCTS_SEARCH_PARAMS.SEARCH}
          placeholder="Search products..."
          type="search"
        />
      </div>
    </Form>
  );
}

export default SearchForm;
