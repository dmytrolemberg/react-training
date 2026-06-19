import { type ReactElement } from 'react';
import { BsCartPlus } from 'react-icons/bs';
import { href, Link } from 'react-router-dom';
import { type ProductPreview } from '../model/types.ts';
import { ROUTES } from '@/shared/config/routes/routes.ts';
import Button from '@/shared/ui/Button.tsx';

interface ProductCardProps {
  readonly product: ProductPreview;
}

const MAXIMUM_PRICE_FRACTION_DIGITS = 2;

function formatPrice(price: number): string {
  return `$${price.toLocaleString('en-US', {
    maximumFractionDigits: MAXIMUM_PRICE_FRACTION_DIGITS,
  })}`;
}

function getProductDetailPath(slug: ProductPreview['slug']): string {
  return href(ROUTES.PRODUCT, { slug });
}

function getStockStatusClassName(stock: boolean): string {
  return stock ? 'badge status-success' : 'badge status-danger';
}

function ProductCard({ product }: ProductCardProps): ReactElement {
  const productDetailPath = getProductDetailPath(product.slug);

  return (
    <article className="product-card">
      <Link aria-label={`View ${product.name}`} className="product-media" to={productDetailPath} />
      <div className="split product-card-head">
        <div>
          <h3 className="product-title">{product.name}</h3>
          <p className="product-meta">
            {product.brand} · {product.category}
          </p>
        </div>
        <span className="price">{formatPrice(product.price)}</span>
      </div>
      <div className="attribute-list">
        {product.attributeValues.map((attribute) => (
          <span className="badge" key={`${product.slug}-${attribute}`}>
            {attribute}
          </span>
        ))}
      </div>
      <div className="split product-card-meta">
        <span className="rating">
          <span className="stars">{'★'.repeat(Math.round(product.rating))} </span>
          {product.rating.toFixed(1)}
        </span>
        <span className={getStockStatusClassName(product.isStock)}>{product.isStock ? 'In stock' : 'Out'}</span>
      </div>
      <div className="cluster product-card-actions">
        <Button className="secondary-button" to={productDetailPath}>
          Details
        </Button>
        <button
          aria-label={`Add ${product.name} to cart`}
          className="primary-button add-to-cart"
          type="button"
          disabled={!product.isStock}
        >
          <BsCartPlus aria-hidden="true" />
        </button>
      </div>
    </article>
  );
}

export default ProductCard;
