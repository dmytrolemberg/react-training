import {
  type ReactElement,
  type SyntheticEvent,
  useEffect,
  useMemo,
  useState,
} from 'react';
import './Checkout.css';

type DeliveryMethod = 'standard' | 'express' | 'pickup';
type PaymentMethod = 'saved-card' | 'new-card';

interface DeliveryOption {
  value: DeliveryMethod;
  label: string;
  price: number;
}

interface SummaryItem {
  name: string;
  quantity: string;
  price: number;
}

const SUMMARY_ITEMS: SummaryItem[] = [
  { name: 'Everyday Carry Pack', quantity: 'Qty 1', price: 112 },
  { name: 'Modular Desk Lamp', quantity: 'Qty 1', price: 89 },
];

const TAX_ESTIMATE = 16;
const DELIVERY_OPTIONS: DeliveryOption[] = [
  { value: 'standard', label: 'Standard delivery · Free', price: 0 },
  { value: 'express', label: 'Express delivery · $12', price: 12 },
  { value: 'pickup', label: 'Pickup point · Free', price: 0 },
];

function formatPrice(value: number): string {
  if (value === 0) {
    return 'Free';
  }

  return `$${String(value)}`;
}

function getDeliveryLabel(value: DeliveryMethod): string {
  return (
    DELIVERY_OPTIONS.find((option) => option.value === value)?.label ??
    DELIVERY_OPTIONS[0].label
  );
}

function getDeliveryPrice(value: DeliveryMethod): number {
  return (
    DELIVERY_OPTIONS.find((option) => option.value === value)?.price ??
    DELIVERY_OPTIONS[0].price
  );
}

function Checkout(): ReactElement {
  const [deliveryMethod, setDeliveryMethod] =
    useState<DeliveryMethod>('standard');
  const [paymentMethod, setPaymentMethod] =
    useState<PaymentMethod>('saved-card');
  const [isDeliveryOpen, setIsDeliveryOpen] = useState(false);
  const [isConfirmationVisible, setIsConfirmationVisible] = useState(false);

  const subtotal = useMemo(
    () => SUMMARY_ITEMS.reduce((sum, item) => sum + item.price, 0),
    [],
  );
  const deliveryPrice = getDeliveryPrice(deliveryMethod);
  const total = subtotal + deliveryPrice + TAX_ESTIMATE;

  useEffect(() => {
    function closeDeliverySelect(): void {
      setIsDeliveryOpen(false);
    }

    function closeDeliverySelectOnEscape(event: KeyboardEvent): void {
      if (event.key === 'Escape') {
        closeDeliverySelect();
      }
    }

    document.addEventListener('click', closeDeliverySelect);
    document.addEventListener('keydown', closeDeliverySelectOnEscape);

    return (): void => {
      document.removeEventListener('click', closeDeliverySelect);
      document.removeEventListener('keydown', closeDeliverySelectOnEscape);
    };
  }, []);

  function submitCheckout(event: SyntheticEvent<HTMLFormElement>): void {
    event.preventDefault();
    setIsConfirmationVisible(true);
  }

  return (
    <>
      <section>
        <p className="eyebrow">Checkout</p>
        <h1 className="title-lg">Secure checkout.</h1>
        <p className="lead">
          A clean form flow for contact details, shipping address, delivery
          method, and payment.
        </p>
      </section>

      <section className="checkout-layout section">
        <form className="card card-pad" id="checkoutForm" onSubmit={submitCheckout}>
          <div className="checkout-stepper">
            <div className="checkout-step is-active">1. Contact</div>
            <div className="checkout-step is-active">2. Delivery</div>
            <div className="checkout-step is-active">3. Payment</div>
          </div>

          <h2 className="title-sm">Contact details</h2>
          <div className="form-grid section">
            <div className="form-field">
              <label htmlFor="email">Email</label>
              <input
                className="input"
                id="email"
                type="email"
                defaultValue="dmytro@example.com"
                required
              />
            </div>
            <div className="form-field">
              <label htmlFor="phone">Phone</label>
              <input className="input" id="phone" type="tel" placeholder="+380..." />
            </div>
          </div>

          <div className="divider"></div>
          <h2 className="title-sm">Shipping address</h2>
          <div className="form-grid section">
            <div className="form-field">
              <label htmlFor="firstName">First name</label>
              <input
                className="input"
                id="firstName"
                defaultValue="Dmytro"
                required
              />
            </div>
            <div className="form-field">
              <label htmlFor="lastName">Last name</label>
              <input
                className="input"
                id="lastName"
                defaultValue="Orikhovskyi"
                required
              />
            </div>
            <div className="form-field">
              <label htmlFor="city">City</label>
              <input className="input" id="city" defaultValue="Kyiv" required />
            </div>
            <div className="form-field">
              <label htmlFor="postal">Postal code</label>
              <input className="input" id="postal" placeholder="01001" required />
            </div>
            <div className="form-field checkout-address-field">
              <label htmlFor="address">Address</label>
              <input
                className="input"
                id="address"
                placeholder="Street, building, apartment"
                required
              />
            </div>
          </div>

          <div className="divider"></div>
          <h2 className="title-sm">Delivery method</h2>
          <div className="form-field section">
            <label>Method</label>
            <div
              className={`custom-select ${isDeliveryOpen ? 'is-open' : ''}`}
              data-custom-select
              onClick={(event) => {
                event.stopPropagation();
              }}
            >
              <input
                type="hidden"
                id="deliveryMethod"
                value={deliveryMethod}
                readOnly
              />
              <button
                className="custom-select-button"
                type="button"
                aria-haspopup="listbox"
                aria-expanded={isDeliveryOpen}
                onClick={() => {
                  setIsDeliveryOpen((isOpen) => !isOpen);
                }}
              >
                <span data-selected-label>{getDeliveryLabel(deliveryMethod)}</span>
              </button>
              <div
                className="custom-select-menu"
                role="listbox"
                aria-label="Delivery method"
              >
                {DELIVERY_OPTIONS.map((option) => {
                  const isSelected = deliveryMethod === option.value;

                  return (
                    <button
                      className={`custom-select-option ${
                        isSelected ? 'is-selected' : ''
                      }`}
                      type="button"
                      data-value={option.value}
                      role="option"
                      aria-selected={isSelected}
                      key={option.value}
                      onClick={() => {
                        setDeliveryMethod(option.value);
                        setIsDeliveryOpen(false);
                        setIsConfirmationVisible(false);
                      }}
                    >
                      {option.label}
                    </button>
                  );
                })}
              </div>
            </div>
          </div>

          <div className="divider"></div>
          <h2 className="title-sm">Payment</h2>
          <div className="stack section">
            <label className="payment-option">
              <span>
                <strong>Saved card ending 4242</strong>
                <small>Default payment method</small>
              </span>
              <span className="choice choice-radio choice-inline">
                <input
                  type="radio"
                  name="payment"
                  checked={paymentMethod === 'saved-card'}
                  onChange={() => {
                    setPaymentMethod('saved-card');
                  }}
                />
                <span className="choice-box" aria-hidden="true"></span>
              </span>
            </label>
            <label className="payment-option">
              <span>
                <strong>New card</strong>
                <small>Add card details during payment</small>
              </span>
              <span className="choice choice-radio choice-inline">
                <input
                  type="radio"
                  name="payment"
                  checked={paymentMethod === 'new-card'}
                  onChange={() => {
                    setPaymentMethod('new-card');
                  }}
                />
                <span className="choice-box" aria-hidden="true"></span>
              </span>
            </label>
          </div>

          <button className="primary-button button-full section" type="submit">
            Place order
          </button>
          <div
            className={`confirmation-box ${
              isConfirmationVisible ? 'is-visible' : ''
            }`}
            id="confirmationBox"
          >
            Order placed. This is a prototype confirmation state.
          </div>
        </form>

        <aside className="card card-pad">
          <h2 className="title-sm">Summary</h2>
          <div className="divider"></div>
          <div className="stack">
            {SUMMARY_ITEMS.map((item) => (
              <div className="split" key={item.name}>
                <div>
                  <p className="product-title">{item.name}</p>
                  <p className="product-meta">{item.quantity}</p>
                </div>
                <strong>{formatPrice(item.price)}</strong>
              </div>
            ))}
          </div>
          <div className="divider"></div>
          <div className="summary-row">
            <span>Subtotal</span>
            <strong>{formatPrice(subtotal)}</strong>
          </div>
          <div className="summary-row">
            <span>Delivery</span>
            <strong>{formatPrice(deliveryPrice)}</strong>
          </div>
          <div className="summary-row">
            <span>Tax estimate</span>
            <strong>{formatPrice(TAX_ESTIMATE)}</strong>
          </div>
          <div className="divider"></div>
          <div className="summary-row">
            <span>Total</span>
            <strong className="summary-total">{formatPrice(total)}</strong>
          </div>
          <p className="muted text-small section">
            Payment data is not processed. This page is only a static prototype.
          </p>
        </aside>
      </section>
    </>
  );
}

export default Checkout;
