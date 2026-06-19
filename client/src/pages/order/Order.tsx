import { type ReactElement } from 'react';
import { useParams } from 'react-router-dom';
import { ROUTES } from '@/shared/config/routes/routes.ts';
import Button from '@/shared/ui/Button.tsx';
import './Order.css';

type OrderStatus = 'processing' | 'shipped' | 'delivered';
type TimelineState = 'done' | 'active' | 'pending';

interface OrderLine {
  name: string;
  meta: string;
  attrs: string[];
  price: string;
}

interface TimelineStep {
  title: string;
  meta: string;
  state: TimelineState;
}

interface OrderDetail {
  number: string;
  date: string;
  email: string;
  status: OrderStatus;
  label: string;
  itemCountLabel: string;
  subtotal: string;
  delivery: string;
  tax: string;
  total: string;
  paidWith: string;
  address: {
    name: string;
    city: string;
    street: string;
    phone: string;
  };
  lines: OrderLine[];
  timeline: TimelineStep[];
}

const DEFAULT_ORDER_NUMBER = '1048';

const ORDERS_BY_NUMBER: Record<string, OrderDetail> = {
  '1048': {
    number: '1048',
    date: 'May 26, 2026',
    email: 'dmytro@example.com',
    status: 'processing',
    label: 'Processing',
    itemCountLabel: '2 products in this order',
    subtotal: '$201',
    delivery: 'Free',
    tax: '$16',
    total: '$217',
    paidWith: 'Paid with Visa ending 4242. Transaction ID: TX-5428-NS.',
    address: {
      name: 'Dmytro Orikhovskyi',
      city: 'Kyiv, Ukraine',
      street: 'Street address placeholder',
      phone: '+380 XX XXX XX XX',
    },
    lines: [
      {
        name: 'Everyday Carry Pack',
        meta: 'Mori · Bags · Graphite · Qty 1',
        attrs: ['18L', 'Laptop 15”', 'Recycled textile'],
        price: '$112',
      },
      {
        name: 'Modular Desk Lamp',
        meta: 'Luma · Home · Warm light · Qty 1',
        attrs: ['Aluminum', 'USB-C', 'Warm light'],
        price: '$89',
      },
    ],
    timeline: [
      { title: 'Order placed', meta: 'May 26, 09:42', state: 'done' },
      { title: 'Payment confirmed', meta: 'May 26, 09:43', state: 'done' },
      {
        title: 'Preparing shipment',
        meta: 'Estimated dispatch today',
        state: 'active',
      },
      {
        title: 'Out for delivery',
        meta: 'Waiting for carrier update',
        state: 'pending',
      },
      { title: 'Delivered', meta: 'Estimated May 29', state: 'pending' },
    ],
  },
  '1042': {
    number: '1042',
    date: 'May 12, 2026',
    email: 'dmytro@example.com',
    status: 'shipped',
    label: 'Shipped',
    itemCountLabel: '1 product in this order',
    subtotal: '$137',
    delivery: 'Free',
    tax: '$11',
    total: '$148',
    paidWith: 'Paid with Visa ending 4242. Transaction ID: TX-5416-NS.',
    address: {
      name: 'Dmytro Orikhovskyi',
      city: 'Kyiv, Ukraine',
      street: 'Street address placeholder',
      phone: '+380 XX XXX XX XX',
    },
    lines: [
      {
        name: 'Aero Knit Jacket',
        meta: 'Northline · Outerwear · Graphite · Qty 1',
        attrs: ['Waterproof', 'XS–XL', 'Graphite'],
        price: '$148',
      },
    ],
    timeline: [
      { title: 'Order placed', meta: 'May 12, 10:18', state: 'done' },
      { title: 'Payment confirmed', meta: 'May 12, 10:19', state: 'done' },
      { title: 'Preparing shipment', meta: 'May 12, 15:30', state: 'done' },
      { title: 'Out for delivery', meta: 'Carrier update received', state: 'active' },
      { title: 'Delivered', meta: 'Estimated May 15', state: 'pending' },
    ],
  },
  '1038': {
    number: '1038',
    date: 'Apr 28, 2026',
    email: 'dmytro@example.com',
    status: 'delivered',
    label: 'Delivered',
    itemCountLabel: '2 products in this order',
    subtotal: '$87',
    delivery: 'Free',
    tax: '$7',
    total: '$94',
    paidWith: 'Paid with Visa ending 4242. Transaction ID: TX-5399-NS.',
    address: {
      name: 'Dmytro Orikhovskyi',
      city: 'Kyiv, Ukraine',
      street: 'Street address placeholder',
      phone: '+380 XX XXX XX XX',
    },
    lines: [
      {
        name: 'Travel Tech Pouch',
        meta: 'Mori · Bags · Slate · Qty 1',
        attrs: ['2L', 'Organizer', 'Travel'],
        price: '$58',
      },
      {
        name: 'Soft Wool Beanie',
        meta: 'Northline · Accessories · Charcoal · Qty 1',
        attrs: ['Merino', 'One size', 'Warm'],
        price: '$36',
      },
    ],
    timeline: [
      { title: 'Order placed', meta: 'Apr 28, 13:04', state: 'done' },
      { title: 'Payment confirmed', meta: 'Apr 28, 13:05', state: 'done' },
      { title: 'Preparing shipment', meta: 'Apr 28, 16:20', state: 'done' },
      { title: 'Out for delivery', meta: 'Apr 29, 11:12', state: 'done' },
      { title: 'Delivered', meta: 'Apr 29, 17:48', state: 'done' },
    ],
  },
};

function getStatusClassName(status: OrderStatus): string {
  if (status === 'delivered') {
    return 'badge status-success';
  }

  return 'badge status-warning';
}

function getTimelineClassName(state: TimelineState): string {
  if (state === 'done') {
    return 'timeline-step is-done';
  }

  if (state === 'active') {
    return 'timeline-step is-active';
  }

  return 'timeline-step';
}

function Order(): ReactElement {
  const { number } = useParams();
  const order =
    ORDERS_BY_NUMBER[number ?? DEFAULT_ORDER_NUMBER] ??
    ORDERS_BY_NUMBER[DEFAULT_ORDER_NUMBER];

  return (
    <>
      <Button className="ghost-button" to={ROUTES.ORDERS}>
        ← Back to orders
      </Button>

      <section className="order-detail-hero section">
        <div>
          <p className="eyebrow">Order details</p>
          <h1 className="title-lg">Order #{order.number}</h1>
          <p className="lead">
            Placed on {order.date} · Confirmation sent to {order.email}
          </p>
        </div>
        <div className="order-detail-actions">
          <span className={getStatusClassName(order.status)}>
            {order.label}
          </span>
          <Button className="secondary-button">
            Download invoice
          </Button>
          <Button>
            Track delivery
          </Button>
        </div>
      </section>

      <section className="order-detail-layout section">
        <div className="stack">
          <article className="card card-pad">
            <div className="split">
              <div>
                <p className="eyebrow">Items</p>
                <h2 className="title-sm">{order.itemCountLabel}</h2>
              </div>
              <strong className="summary-total">{order.total}</strong>
            </div>

            <div className="order-line-list section">
              {order.lines.map((line) => (
                <article className="order-line-item" key={line.name}>
                  <div className="product-media small"></div>
                  <div>
                    <h3 className="product-title">{line.name}</h3>
                    <p className="product-meta">{line.meta}</p>
                    <div className="attribute-list compact-list">
                      {line.attrs.map((attr) => (
                        <span className="badge" key={attr}>
                          {attr}
                        </span>
                      ))}
                    </div>
                  </div>
                  <strong>{line.price}</strong>
                </article>
              ))}
            </div>
          </article>

          <article className="card card-pad">
            <p className="eyebrow">Delivery progress</p>
            <h2 className="title-sm">Package timeline</h2>
            <div className="timeline section">
              {order.timeline.map((step) => (
                <div
                  className={getTimelineClassName(step.state)}
                  key={step.title}
                >
                  <span></span>
                  <div>
                    <strong>{step.title}</strong>
                    <p className="product-meta">{step.meta}</p>
                  </div>
                </div>
              ))}
            </div>
          </article>
        </div>

        <aside className="stack">
          <article className="card card-pad">
            <h2 className="title-sm">Payment summary</h2>
            <div className="divider"></div>
            <div className="stack">
              <div className="summary-row">
                <span>Subtotal</span>
                <strong>{order.subtotal}</strong>
              </div>
              <div className="summary-row">
                <span>Delivery</span>
                <strong>{order.delivery}</strong>
              </div>
              <div className="summary-row">
                <span>Tax estimate</span>
                <strong>{order.tax}</strong>
              </div>
            </div>
            <div className="divider"></div>
            <div className="summary-row">
              <span>Total</span>
              <strong className="summary-total">{order.total}</strong>
            </div>
            <p className="muted text-small section">{order.paidWith}</p>
          </article>

          <article className="card card-pad">
            <h2 className="title-sm">Shipping address</h2>
            <p className="muted text-small section">
              {order.address.name}
              <br />
              {order.address.city}
              <br />
              {order.address.street}
              <br />
              Phone: {order.address.phone}
            </p>
          </article>

          <article className="card card-pad">
            <h2 className="title-sm">Need help?</h2>
            <p className="muted text-small section">
              Use this area for order support, returns, invoices, shipment
              tracking, and product review links.
            </p>
            <div className="cluster section">
              <Button className="secondary-button" to={ROUTES.REVIEWS}>
                Write review
              </Button>
              <Button className="ghost-button">
                Contact support
              </Button>
            </div>
          </article>
        </aside>
      </section>
    </>
  );
}

export default Order;
