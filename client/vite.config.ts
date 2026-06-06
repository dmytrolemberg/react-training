import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tsconfigPaths from 'vite-tsconfig-paths';
import { fileURLToPath, URL } from 'node:url';

// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tsconfigPaths()],
  root: 'src/app',
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  // publicDir: '../public',
  server: {
    port: 8002,
    strictPort: true,
  },
  build: {
    outDir: '../../dist',
    emptyOutDir: true,
  },
})
