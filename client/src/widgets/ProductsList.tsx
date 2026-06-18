import { type ReactElement } from 'react';
import { ProductCard, type ProductPreview } from '@/entities/product';
import Button from '@/shared/ui/Button.tsx';
import { ROUTES } from '@/shared/model/routes.ts';

interface ProductsListProps {
  readonly products: readonly ProductPreview[];
  readonly title: string;
  readonly label: string;
}

function ProductsList({
  products,
  title,
  label,
}: ProductsListProps): ReactElement {
  return (
    <section className="section">
      <div className="split">
        <div>
          <p className="eyebrow">{label}</p>
          <h2 className="title-md">{title}</h2>
        </div>
        <Button className="ghost-button" to={ROUTES.PRODUCTS}>
          View catalog →
        </Button>
      </div>
      <div className="grid grid-3 section">
        {products.map((product) => (
          <ProductCard key={product.slug} product={product} />
        ))}
      </div>
    </section>
  );
}

export default ProductsList;
