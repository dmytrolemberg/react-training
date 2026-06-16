import { type ReactElement, type ReactNode } from 'react';
import { Link, type To } from 'react-router-dom';

interface ButtonProps {
  readonly children: ReactNode;
  readonly className?: string;
  readonly to?: To;
  readonly type?: 'button' | 'submit';
}

function Button({
  children,
  className = 'primary-button',
  to,
  type = 'button',
}: ButtonProps): ReactElement {
  if (to !== undefined) {
    return (
      <Link className={className} to={to}>
        {children}
      </Link>
    );
  }

  return (
    <button className={className} type={type}>
      {children}
    </button>
  );
}

export default Button;
