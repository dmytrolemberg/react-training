import { type ReactElement, useMemo, useState } from 'react';
import { ROUTES } from '@/shared/model/routes.ts';
import Button from '@/shared/ui/Button.tsx';
import './Cart.css';

interface CartItem {
  id: string;
  name: string;
  meta: string;
  price: number;
  qty: number;
}

const TAX_RATE = 0.08;

const INITIAL_CART_ITEMS: CartItem[] = [
  {
    id: 'everyday-carry-pack',
    name: 'Everyday Carry Pack',
    meta: 'Mori · Bags · Graphite',
    price: 112,
    qty: 1,
  },
  {
    id: 'modular-desk-lamp',
    name: 'Modular Desk Lamp',
    meta: 'Luma · Home · Warm light',
    price: 89,
    qty: 1,
  },
];

function formatPrice(value: number): string {
  return `$${String(value)}`;
}

function Cart(): ReactElement {
  const [cartItems, setCartItems] = useState<CartItem[]>(INITIAL_CART_ITEMS);

  const subtotal = useMemo(
    () =>
      cartItems.reduce((sum, item) => sum + item.price * item.qty, 0),
    [cartItems],
  );
  const tax = Math.round(subtotal * TAX_RATE);
  const total = subtotal + tax;

  function decrementQuantity(itemId: string): void {
    setCartItems((items) =>
      items.map((item) =>
        item.id === itemId
          ? { ...item, qty: Math.max(1, item.qty - 1) }
          : item,
      ),
    );
  }

  function incrementQuantity(itemId: string): void {
    setCartItems((items) =>
      items.map((item) =>
        item.id === itemId ? { ...item, qty: item.qty + 1 } : item,
      ),
    );
  }

  function removeItem(itemId: string): void {
    setCartItems((items) => items.filter((item) => item.id !== itemId));
  }

  return (
    <>
      <section className="split">
        <div>
          <p className="eyebrow">Cart</p>
          <h1 className="title-lg">Review your items.</h1>
          <p className="lead">Adjust quantities before moving to checkout.</p>
        </div>
        <Button className="secondary-button" to={ROUTES.PRODUCTS}>
          Continue shopping
        </Button>
      </section>

      <section className="cart-layout section">
        <div className="stack" id="cartItems">
          {cartItems.map((item) => (
            <article className="cart-item" key={item.id}>
              <div className="product-media small"></div>
              <div>
                <h2 className="product-title">{item.name}</h2>
                <p className="product-meta">{item.meta}</p>
                <button
                  className="ghost-button"
                  type="button"
                  onClick={() => {
                    removeItem(item.id);
                  }}
                >
                  Remove
                </button>
              </div>
              <div className="stack cart-item-controls">
                <strong>{formatPrice(item.price)}</strong>
                <div
                  className="quantity-control"
                  aria-label={`${item.name} quantity selector`}
                >
                  <button
                    className="quantity-button"
                    type="button"
                    onClick={() => {
                      decrementQuantity(item.id);
                    }}
                  >
                    −
                  </button>
                  <span className="quantity-value">{item.qty}</span>
                  <button
                    className="quantity-button"
                    type="button"
                    onClick={() => {
                      incrementQuantity(item.id);
                    }}
                  >
                    +
                  </button>
                </div>
              </div>
            </article>
          ))}

          {cartItems.length === 0 && (
            <div className="empty-state card">
              <h2 className="title-sm">Your cart is empty</h2>
              <p className="muted">Add products from the catalog.</p>
            </div>
          )}
        </div>
        <aside className="card card-pad">
          <h2 className="title-sm">Order summary</h2>
          <div className="divider"></div>
          <div className="stack">
            <div className="summary-row">
              <span>Subtotal</span>
              <strong id="subtotal">{formatPrice(subtotal)}</strong>
            </div>
            <div className="summary-row">
              <span>Delivery</span>
              <strong id="delivery">Free</strong>
            </div>
            <div className="summary-row">
              <span>Tax estimate</span>
              <strong id="tax">{formatPrice(tax)}</strong>
            </div>
          </div>
          <div className="divider"></div>
          <div className="summary-row">
            <span>Total</span>
            <strong className="summary-total" id="total">
              {formatPrice(total)}
            </strong>
          </div>
          <Button
            className="primary-button button-full section"
            to={ROUTES.CHECKOUT}
          >
            Checkout
          </Button>
          <p className="muted text-small">
            Secure payment, saved address, and order tracking.
          </p>
        </aside>
      </section>
    </>
  );
}

export default Cart;
