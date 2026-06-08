import { type ReactElement } from 'react';
import Logo from './Logo.tsx';
import Navbar from './Navbar.tsx';
import Actions from './Actions.tsx';

function Header(): ReactElement {
  return (
    <header className="site-header">
      <Logo />
      <Navbar />
      <Actions />
    </header>
  );
}

export default Header;
