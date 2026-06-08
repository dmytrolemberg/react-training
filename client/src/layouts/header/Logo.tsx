import { type ReactElement } from 'react';
import { BsAmazon } from 'react-icons/bs';
import { Link} from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

function Logo(): ReactElement {

  return (
      <Link className="brand" to={ROUTES.HOME} aria-label="North Shop home">
        <span className="brand-mark">
          <BsAmazon />
        </span>
        <span className="brand-text">North Shop</span>
      </Link>
  );
}

export default Logo;
