import {
  type ReactElement,
} from 'react';
import Button from '@/shared/ui/Button.tsx';
import { href } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

interface Product {
  name: string;
  slug: string;
  brand: string;
  category: string;
  attribute_values: Record<string, string>;
  price: number;
  rating: number;
  reviews: number;
  stock: boolean;
}
interface ProductCardProps {
  readonly product: Product;
}

function ProductCard({ product }: ProductCardProps): ReactElement {

  return (
    <article className="product-card">
      <div className="product-media"></div>
      <div className="split">
        <div>
          <h3 className="product-title">{product.name}</h3>
          <p className="product-meta">
            {product.brand} · {product.category}
          </p>
        </div>
        <span className="price">${product.price.toFixed(2)}</span>
      </div>
      <div className="attribute-list">
        {Object.entries(product.attribute_values).map(([key, value]) => (
          <span className="badge" key={product.slug + key}>
            {value}
          </span>
        ))}
      </div>
      <div className="split">
        <span className="rating">
          <span className="stars">{'★'.repeat(Math.round(product.rating))} </span>
          {product.rating.toFixed(1)}
        </span>
        <Button className="secondary-button" to={href(ROUTES.PRODUCT, { slug: product.slug })}>
          Details
        </Button>
      </div>
    </article>
  );
}

export default ProductCard;
