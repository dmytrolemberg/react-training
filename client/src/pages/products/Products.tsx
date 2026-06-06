import { type ReactElement } from 'react';
import './Products.css';
import { Link, href } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

function Products(): ReactElement {

  return (
    <>
      <h1>Products:</h1>
      <Link to={href(ROUTES.PRODUCT, { slug: 'product-1' })}>Product 1</Link>
      <Link to={href(ROUTES.PRODUCT, { slug: 'product-2' })}>Product 2</Link>
      <Link to={href(ROUTES.PRODUCT, { slug: 'product-3' })}>Product 3</Link>
    </>
  );
}

export default Products;
