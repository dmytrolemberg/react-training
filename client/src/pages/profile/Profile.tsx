import { type ReactElement } from 'react';
import './Profile.css';
import { ROUTES } from '@/shared/model/routes.ts';
import { Link } from 'react-router-dom';

function Profile(): ReactElement {

  return (
    <>
      <section>
        <p className="eyebrow">Profile</p>
        <h1 className="title-lg">Account overview.</h1>
        <p className="lead">
          User profile page with order history, saved addresses, payment
          methods, wishlist, and reviews.
        </p>
      </section>

      <section className="profile-layout section">
        <aside className="profile-card">
          <div className="avatar">D</div>
          <h2 className="title-sm section">Dmytro Orikhovskyi</h2>
          <p className="muted text-small">dmytro@example.com</p>
          <div className="profile-list">
            <a href="orders.html">
              <span>Orders</span>
              <strong>12</strong>
            </a>
            <a href="reviews.html">
              <span>Reviews</span>
              <strong>8</strong>
            </a>
            <Link to={ROUTES.CART}>
              <span>Cart</span>
              <strong>2</strong>
            </Link>
            <button type="button">
              <span>Wishlist</span>
              <strong>5</strong>
            </button>
          </div>
        </aside>

        <div className="stack">
          <div className="grid grid-3">
            <div className="kpi-card">
              <span className="muted text-small">Total orders</span>
              <span className="kpi-value">12</span>
            </div>
            <div className="kpi-card">
              <span className="muted text-small">Average rating</span>
              <span className="kpi-value">4.8</span>
            </div>
            <div className="kpi-card">
              <span className="muted text-small">Saved items</span>
              <span className="kpi-value">5</span>
            </div>
          </div>

          <section className="card card-pad">
            <div className="split">
              <h2 className="title-sm">Saved addresses</h2>
              <button className="secondary-button" type="button">
                Add address
              </button>
            </div>
            <div className="grid grid-2 section">
              <div className="soft-card card-pad">
                <span className="badge">Default</span>
                <h3 className="product-title section">Home</h3>
                <p className="muted text-small">
                  Kyiv, Ukraine · Street address placeholder
                </p>
              </div>
              <div className="soft-card card-pad">
                <span className="badge">Work</span>
                <h3 className="product-title section">Office</h3>
                <p className="muted text-small">
                  Kyiv, Ukraine · Office address placeholder
                </p>
              </div>
            </div>
          </section>

          <section className="card card-pad">
            <div className="split">
              <h2 className="title-sm">Payment methods</h2>
              <button className="secondary-button" type="button">
                Add card
              </button>
            </div>
            <div className="grid grid-2 section">
              <div className="soft-card card-pad">
                <span className="badge">Visa</span>
                <h3 className="product-title section">•••• 4242</h3>
                <p className="muted text-small">Expires 12/28</p>
              </div>
              <div className="soft-card card-pad">
                <span className="badge">Mastercard</span>
                <h3 className="product-title section">•••• 1881</h3>
                <p className="muted text-small">Expires 09/27</p>
              </div>
            </div>
          </section>
        </div>
      </section>
    </>
  );
}

export default Profile;
