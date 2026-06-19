import { type ReactElement } from 'react';
import '../Auth.css';
import './Login.css';
import { Link } from 'react-router-dom';
import { ROUTES } from '@/shared/config/routes/routes.ts';
import Button from '@/shared/ui/Button.tsx';

function Login(): ReactElement {

  return (
    <section className="auth-compact-wrap" aria-labelledby="loginTitle">
      <div className="auth-compact-panel">
        <p className="eyebrow">Account access</p>
        <h1 className="title-lg" id="loginTitle">
          Welcome back.
        </h1>
        <p className="lead">
          Sign in to continue checkout, review orders, and manage saved
          products.
        </p>

        <form className="auth-form" id="loginForm">
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
              autoComplete="current-password"
              placeholder="Enter password"
              required
            />
          </div>

          <div className="auth-form-row">
            <label className="choice">
              <input type="checkbox" />
              <span className="choice-box" aria-hidden="true"></span>
              Keep me signed in
            </label>
            <Link
              className="auth-link"
              to={ROUTES.RESET_PASSWORD}
              aria-label="Reset password"
            >
              Forgot password?
            </Link>
          </div>

          <Button className="primary-button button-full" type="submit">
            Sign in
          </Button>
          <div className="auth-message" id="loginMessage">
            Signed in prototype state is ready.
          </div>
        </form>

        <p className="auth-note">
          New to North Shop?{' '}
          <Link className="auth-link" to={ROUTES.REGISTER} aria-label="Register">
            Create an account
          </Link>
        </p>
      </div>
    </section>
  );
}

export default Login;
