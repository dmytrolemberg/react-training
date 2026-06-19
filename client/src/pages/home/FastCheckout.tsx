import { type ReactElement } from 'react';
import { ROUTES } from '@/shared/config/routes/routes.ts';
import Button from '@/shared/ui/Button.tsx';

function FastCheckout(): ReactElement {
  return (
    <aside className="home-checkout-preview">
      <p className="eyebrow" style={{ color: 'currentColor', opacity: 0.55 }}>
        Fast checkout
      </p>
      <h2 className="title-sm" style={{ color: 'currentColor' }}>
        2 items ready
      </h2>
      <p className="text-small" style={{ opacity: 0.68 }}>
        Free delivery, saved address, secure payment, order tracking.
      </p>
      <Button className="secondary-button section" to={ROUTES.CHECKOUT}>
        Continue checkout
      </Button>
    </aside>
  );
}

export default FastCheckout;
