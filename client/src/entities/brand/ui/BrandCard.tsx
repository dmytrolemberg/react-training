import { type ReactElement } from 'react';
import type { BrandPreview } from '../model/types.ts';
import { buildProductsRoute } from '@/shared/config/routes/product';
import Button from '@/shared/ui/Button.tsx';

interface BrandCardProps {
  readonly brand: BrandPreview;
}

function BrandCard({ brand }: BrandCardProps): ReactElement {
  return (
    <Button
      className="category-card"
      to={buildProductsRoute({ brandId: brand.id })}
    >
      <h3 className="title-sm section">{brand.name}</h3>
      <p className="muted text-small">{brand.description}</p>
    </Button>
  );
}

export default BrandCard;
