import { type ReactElement, useEffect, useMemo, useState } from 'react';
import './Products.css';
import {
  ProductCard,
  PRODUCT_PREVIEWS,
  type ProductPreview,
} from '@/entities/product';
import SearchForm from '@/shared/ui/SearchForm.tsx';

type SelectName = 'rating' | 'sort';

interface SelectOption {
  value: string;
  label: string;
}

const RATING_OPTIONS = [
  { value: '0', label: 'Any rating' },
  { value: '4.5', label: '4.5+' },
  { value: '4.7', label: '4.7+' },
  { value: '4.8', label: '4.8+' },
];

const SORT_OPTIONS = [
  { value: 'featured', label: 'Featured' },
  { value: 'priceAsc', label: 'Price: low to high' },
  { value: 'priceDesc', label: 'Price: high to low' },
  { value: 'ratingDesc', label: 'Rating' },
];

function getSelectedLabel(options: SelectOption[], value: string): string {
  return (
    options.find((option) => option.value === value)?.label ?? options[0].label
  );
}

function Products(): ReactElement {
  const [openSelect, setOpenSelect] = useState<SelectName | null>(null);
  const [ratingFilter, setRatingFilter] = useState('0');
  const [sortSelect, setSortSelect] = useState('featured');

  const products = useMemo((): readonly ProductPreview[] => {
    const minRating = Number(ratingFilter);
    const filteredProducts = PRODUCT_PREVIEWS.filter(
      (product) => product.rating >= minRating,
    );

    if (sortSelect === 'priceAsc') {
      return [...filteredProducts].sort((a, b) => a.price - b.price);
    }

    if (sortSelect === 'priceDesc') {
      return [...filteredProducts].sort((a, b) => b.price - a.price);
    }

    if (sortSelect === 'ratingDesc') {
      return [...filteredProducts].sort((a, b) => b.rating - a.rating);
    }

    return filteredProducts;
  }, [ratingFilter, sortSelect]);

  useEffect(() => {
    function closeSelects(): void {
      setOpenSelect(null);
    }

    function closeSelectsOnEscape(event: KeyboardEvent): void {
      if (event.key === 'Escape') {
        closeSelects();
      }
    }

    document.addEventListener('click', closeSelects);
    document.addEventListener('keydown', closeSelectsOnEscape);

    return (): void => {
      document.removeEventListener('click', closeSelects);
      document.removeEventListener('keydown', closeSelectsOnEscape);
    };
  }, []);

  function toggleSelect(selectName: SelectName): void {
    setOpenSelect((currentSelect) =>
      currentSelect === selectName ? null : selectName,
    );
  }

  return (
    <>
      <section className="split">
        <div>
          <p className="eyebrow">Catalog</p>
          <h1 className="title-lg">Search, filter, compare.</h1>
          <p className="lead">
            Products are structured by category, brand, price, material, attributes, availability, review count, and
            rating.
          </p>
        </div>
        <span className="badge badge-dark" id="resultCount">
          {products.length} product{products.length === 1 ? '' : 's'}
        </span>
      </section>

      <section className="catalog-layout section">
        <aside className="filter-sidebar" aria-label="Product filters">
          <div className="split filter-header">
            <strong>Filters</strong>
            <button className="ghost-button" type="button" id="clearFilters">
              Clear
            </button>
          </div>

          <div className="filter-group">
            <span className="filter-title">Category</span>
            <div className="filter-options" data-filter="category">
              <label className="choice">
                <input type="checkbox" value="Outerwear" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Outerwear</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Home" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Home</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Bags" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Bags</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Accessories" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Accessories</span>
              </label>
            </div>
          </div>

          <div className="filter-group">
            <span className="filter-title">Brand</span>
            <div className="filter-options" data-filter="brand">
              <label className="choice">
                <input type="checkbox" value="Northline" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Northline</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Luma" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Luma</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Mori" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Mori</span>
              </label>
              <label className="choice">
                <input type="checkbox" value="Studio Base" />
                <span className="choice-box" aria-hidden="true"></span>
                <span>Studio Base</span>
              </label>
            </div>
          </div>

          <div className="filter-group">
            <span className="filter-title">Minimum rating</span>
            <div
              className={`custom-select is-flipped ${openSelect === 'rating' ? 'is-open' : ''}`}
              data-custom-select
              onClick={(event) => {
                event.stopPropagation();
              }}
            >
              <input type="hidden" id="ratingFilter" value={ratingFilter} readOnly />
              <button
                className="custom-select-button"
                type="button"
                aria-haspopup="listbox"
                aria-expanded={openSelect === 'rating'}
                onClick={() => {
                  toggleSelect('rating');
                }}
              >
                <span data-selected-label>{getSelectedLabel(RATING_OPTIONS, ratingFilter)}</span>
              </button>
              <div className="custom-select-menu" role="listbox" aria-label="Minimum rating">
                {RATING_OPTIONS.map((option) => {
                  const isSelected = ratingFilter === option.value;

                  return (
                    <button
                      className={`custom-select-option ${isSelected ? 'is-selected' : ''}`}
                      type="button"
                      data-value={option.value}
                      role="option"
                      aria-selected={isSelected}
                      key={option.value}
                      onClick={() => {
                        setRatingFilter(option.value);
                        setOpenSelect(null);
                      }}
                    >
                      {option.label}
                    </button>
                  );
                })}
              </div>
            </div>
          </div>

          <div className="filter-group">
            <span className="filter-title">Availability</span>
            <label className="choice">
              <input type="checkbox" id="stockFilter" />
              <span className="choice-box" aria-hidden="true"></span>
              <span>In stock only</span>
            </label>
          </div>
        </aside>

        <div className="catalog-results">
          <div className="catalog-toolbar">
            <SearchForm placeholder={'Search products...'} id={'searchInput'} />
            <div
              className={`custom-select ${openSelect === 'sort' ? 'is-open' : ''}`}
              data-custom-select
              onClick={(event) => {
                event.stopPropagation();
              }}
            >
              <input type="hidden" id="sortSelect" value={sortSelect} readOnly />
              <button
                className="custom-select-button"
                type="button"
                aria-haspopup="listbox"
                aria-expanded={openSelect === 'sort'}
                onClick={() => {
                  toggleSelect('sort');
                }}
              >
                <span data-selected-label>{getSelectedLabel(SORT_OPTIONS, sortSelect)}</span>
              </button>
              <div className="custom-select-menu custom-select-menu-right" role="listbox" aria-label="Sort products">
                {SORT_OPTIONS.map((option) => {
                  const isSelected = sortSelect === option.value;

                  return (
                    <button
                      className={`custom-select-option ${isSelected ? 'is-selected' : ''}`}
                      type="button"
                      data-value={option.value}
                      role="option"
                      aria-selected={isSelected}
                      key={option.value}
                      onClick={() => {
                        setSortSelect(option.value);
                        setOpenSelect(null);
                      }}
                    >
                      {option.label}
                    </button>
                  );
                })}
              </div>
            </div>
          </div>

          <div className="product-grid" id="productGrid">
            {products.map((product) => (
              <ProductCard key={product.slug} product={product} />
            ))}
          </div>
          <div className="empty-state card section" id="emptyState" hidden={products.length > 0}>
            <h2 className="title-sm">No products found</h2>
            <p className="muted">Try another search term or remove some filters.</p>
          </div>
        </div>
      </section>
    </>
  );
}

export default Products;
