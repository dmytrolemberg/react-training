import { type ReactElement } from 'react';
import './App.css';
import Layout from '@/layouts/Layout';
import { Outlet } from 'react-router-dom';
function App(): ReactElement {
  return (
    <Layout>
      <Outlet />
    </Layout>
  );
}

export default App;
