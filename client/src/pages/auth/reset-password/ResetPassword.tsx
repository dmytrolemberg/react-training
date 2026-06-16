import { type ReactElement } from 'react';
import '../Auth.css';
import './ResetPassword.css';
import { Link } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';
import Button from '@/shared/ui/Button.tsx';

function ResetPassword(): ReactElement {

  return (
    <section className="auth-compact-wrap" aria-labelledby="resetTitle">
      <div className="auth-compact-panel">
        <p className="eyebrow">Password reset</p>
        <h1 className="title-lg" id="resetTitle">
          Get back into your account.
        </h1>
        <p className="lead">
          Enter your email and we will send a reset link for your North Shop
          profile.
        </p>

        <form className="auth-form" id="resetForm">
          <div className="form-field">
            <label htmlFor="email">Email</label>
            <input
              className="input"
              id="email"
              name="email"
              type="email"
              autoComplete="email"
              placeholder="dmytro@example.com"
              required
            />
          </div>

          <Button className="primary-button button-full" type="submit">
            Send reset link
          </Button>
          <div className="auth-message" id="resetMessage">
            Reset link prototype state is ready.
          </div>
        </form>

        <div className="auth-step-list" aria-label="Reset steps">
          <div className="auth-step">
            <span className="auth-step-number">1</span>
            <div>
              <p className="product-title">Check your email</p>
              <p className="product-meta">
                Use the newest reset link for this account.
              </p>
            </div>
          </div>
          <div className="auth-step">
            <span className="auth-step-number">2</span>
            <div>
              <p className="product-title">Choose a new password</p>
              <p className="product-meta">
                Keep saved addresses, orders, and wishlist unchanged.
              </p>
            </div>
          </div>
        </div>

        <p className="auth-note">
          Remembered it?{' '}
          <Link className="auth-link" to={ROUTES.LOGIN} aria-label="Login">
            Return to sign in
          </Link>
        </p>
      </div>
    </section>
  );
}

export default ResetPassword;
