import { type ReactElement } from 'react';
import '../Auth.css';
import './Register.css';
import { Link } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes.ts';

function Register(): ReactElement {

  return (
    <section className="auth-compact-wrap" aria-labelledby="registerTitle">
      <div className="auth-compact-panel">
        <p className="eyebrow">New account</p>
        <h1 className="title-lg" id="registerTitle">
          Join North Shop.
        </h1>
        <p className="lead">
          Create an account to save orders, addresses, payment methods, and
          wishlist items.
        </p>

        <form className="auth-form" id="registerForm">
          <div className="form-grid">
            <div className="form-field">
              <label htmlFor="firstName">First name</label>
              <input
                className="input"
                id="firstName"
                name="firstName"
                autoComplete="given-name"
                placeholder="Dmytro"
                required
              />
            </div>
            <div className="form-field">
              <label htmlFor="lastName">Last name</label>
              <input
                className="input"
                id="lastName"
                name="lastName"
                autoComplete="family-name"
                placeholder="Orikhovskyi"
                required
              />
            </div>
          </div>

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
          <div className="form-field">
            <label htmlFor="password">Password</label>
            <input
              className="input"
              id="password"
              name="password"
              type="password"
              autoComplete="new-password"
              placeholder="Create password"
              required
            />
          </div>

          <label className="choice">
            <input type="checkbox" />
            <span className="choice-box" aria-hidden="true"></span>
            Send product updates and order reminders
          </label>

          <button className="primary-button button-full" type="submit">
            Create account
          </button>
          <div className="auth-message" id="registerMessage">
            Account created prototype state is ready.
          </div>
        </form>

        <p className="auth-note">
          Already have an account?{' '}
          <Link className="auth-link" to={ROUTES.LOGIN} aria-label="Login">
            Sign in
          </Link>
        </p>
      </div>
    </section>
  );
}

export default Register;
