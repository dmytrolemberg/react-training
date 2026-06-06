import { type ReactElement } from 'react';
import './Product.css';
import { useParams } from 'react-router-dom';
import { type PathParams, ROUTES } from '@/shared/model/routes';

function Product(): ReactElement {
  const params = useParams<PathParams[typeof ROUTES.PRODUCT]>();

  return (
    <>
      <h1>Product slug: {params.slug}</h1>
    </>
  );
}

export default Product;
