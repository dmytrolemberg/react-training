import { type ReactElement, type ReactNode } from 'react';
import './Layout.css';
import Header from './Header.tsx';
import { useLocation } from 'react-router-dom';
import { ROUTES } from '@/shared/model/routes';

function Layout({ children }: { children: ReactNode }): ReactElement {
  const location = useLocation();
  const isAuthPage = location.pathname === ROUTES.LOGIN || location.pathname === ROUTES.REGISTER || location.pathname === ROUTES.RESET_PASSWORD;

  return (
    <div className="app-shell">
      <div className="page">
        {!isAuthPage && <Header />}
        <main className="page-main">{children}</main>
      </div>
    </div>
  );
}

export default Layout;
