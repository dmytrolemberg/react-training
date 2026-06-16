import { type ReactElement, useMemo, useState } from 'react';
import { generatePath } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';
import Button from '@/shared/ui/Button.tsx';
import './Orders.css';

type OrderStatus = 'processing' | 'shipped' | 'delivered';
type OrderStatusFilter = OrderStatus | 'all';

interface OrderSummary {
  number: string;
  date: string;
  status: OrderStatus;
  label: string;
  total: string;
  items: string;
  previewItems: string[];
}

interface StatusTab {
  status: OrderStatusFilter;
  label: string;
}

const ORDERS: OrderSummary[] = [
  {
    number: '1048',
    date: 'May 26, 2026',
    status: 'processing',
    label: 'Processing',
    total: '$217',
    items: 'Everyday Carry Pack, Modular Desk Lamp',
    previewItems: ['Everyday Carry Pack', 'Modular Desk Lamp', 'Invoice'],
  },
  {
    number: '1042',
    date: 'May 12, 2026',
    status: 'shipped',
    label: 'Shipped',
    total: '$148',
    items: 'Aero Knit Jacket',
    previewItems: ['Aero Knit Jacket', 'Shipment', 'Receipt'],
  },
  {
    number: '1038',
    date: 'Apr 28, 2026',
    status: 'delivered',
    label: 'Delivered',
    total: '$94',
    items: 'Travel Tech Pouch, Soft Wool Beanie',
    previewItems: ['Travel Tech Pouch', 'Soft Wool Beanie', 'Review'],
  },
];

const STATUS_TABS: StatusTab[] = [
  { status: 'all', label: 'All' },
  { status: 'processing', label: 'Processing' },
  { status: 'shipped', label: 'Shipped' },
  { status: 'delivered', label: 'Delivered' },
];

function getOrderPath(number: string): string {
  return generatePath(ROUTES.ORDER, { number });
}

function getStatusClassName(status: OrderStatus): string {
  if (status === 'delivered') {
    return 'badge status-success';
  }

  if (status === 'shipped') {
    return 'badge status-warning';
  }

  return 'badge';
}

function Orders(): ReactElement {
  const [selectedStatus, setSelectedStatus] =
    useState<OrderStatusFilter>('all');

  const visibleOrders = useMemo((): OrderSummary[] => {
    if (selectedStatus === 'all') {
      return ORDERS;
    }

    return ORDERS.filter((order) => order.status === selectedStatus);
  }, [selectedStatus]);

  return (
    <>
      <section className="split">
        <div>
          <p className="eyebrow">Orders</p>
          <h1 className="title-lg">Track every order.</h1>
          <p className="lead">
            Order list with status, items, total amount, delivery progress, and
            links to review products.
          </p>
        </div>
        <Button className="secondary-button" to={ROUTES.PRODUCTS}>
          Shop again
        </Button>
      </section>

      <section className="section">
        <div className="order-tabs" id="orderTabs">
          {STATUS_TABS.map((tab) => (
            <button
              className={`filter-pill ${
                selectedStatus === tab.status ? 'is-active' : ''
              }`}
              type="button"
              data-status={tab.status}
              key={tab.status}
              onClick={() => {
                setSelectedStatus(tab.status);
              }}
            >
              {tab.label}
            </button>
          ))}
        </div>
        <div className="stack" id="orderList">
          {visibleOrders.map((order) => (
            <article className="order-card" key={order.number}>
              <div className="split">
                <div>
                  <h2 className="product-title">Order #{order.number}</h2>
                  <p className="product-meta">
                    {order.date} · {order.items}
                  </p>
                </div>
                <div className="cluster">
                  <span className={getStatusClassName(order.status)}>
                    {order.label}
                  </span>
                  <strong>{order.total}</strong>
                </div>
              </div>
              <div className="order-products">
                {order.previewItems.map((item) => (
                  <span
                    className="order-mini-product"
                    aria-label={item}
                    title={item}
                    key={item}
                  ></span>
                ))}
              </div>
              <div className="cluster section">
                <Button
                  className="secondary-button"
                  to={getOrderPath(order.number)}
                >
                  View details
                </Button>
                <Button className="ghost-button" to={ROUTES.REVIEWS}>
                  Write review →
                </Button>
              </div>
            </article>
          ))}
        </div>
      </section>
    </>
  );
}

export default Orders;
