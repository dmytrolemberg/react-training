import { type ReactElement } from 'react';
import './App.css';
import Layout from '@/layouts/Layout';
import Home from '@/pages/home';

function App(): ReactElement {
  return (
    <Layout>
      <Home />
    </Layout>
  );
}

export default App;
