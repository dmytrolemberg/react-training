import { type ReactElement, type ReactNode } from 'react';
import './Layout.css';
import Header from './header/Header'

function Layout({ children }: { children: ReactNode }): ReactElement {

  return (
    <div className="app-shell">
      <div className="page">
        <Header />
        <main className="page-main">{children}</main>
      </div>
    </div>
  );
}

export default Layout;
