import { type ReactElement } from 'react';
import { BrandCard, type BrandPreview } from '@/entities/brand';

interface BrandListProps {
  readonly brands: readonly BrandPreview[];
  readonly title: string;
  readonly label: string;
}

function BrandList({ brands, title, label }: BrandListProps): ReactElement {
  return (
    <section className="section">
      <div className="split">
        <div>
          <p className="eyebrow">{label}</p>
          <h2 className="title-md">{title}</h2>
        </div>
      </div>
      <div className="grid grid-4 section">
        {brands.map((brand) => (
          <BrandCard key={brand.id} brand={brand} />
        ))}
      </div>
    </section>
  );
}

export default BrandList;
