import { type ReactElement } from 'react';
import { ProductCard, type ProductPreview } from '@/entities/product';

interface FavoriteProductProps {
  readonly favoriteProduct: ProductPreview;
}

function FavoriteProduct({
  favoriteProduct,
}: FavoriteProductProps): ReactElement {
  return <ProductCard product={favoriteProduct} />;
}

export default FavoriteProduct;
