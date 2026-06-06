import js from '@eslint/js';
import globals from 'globals';
import reactHooks from 'eslint-plugin-react-hooks';
import reactRefresh from 'eslint-plugin-react-refresh';
import tseslint from 'typescript-eslint';
import prettierConfig from 'eslint-config-prettier';

export default tseslint.config(
  { ignores: ['dist', 'node_modules', 'coverage'] },
  {
    files: ['**/*.{ts,tsx}'],
    extends: [
      js.configs.recommended,
      ...tseslint.configs.recommended,
      ...tseslint.configs.strictTypeChecked,
      ...tseslint.configs.stylisticTypeChecked,
    ],
    plugins: {
      'react-hooks': reactHooks,
      'react-refresh': reactRefresh,
    },
    languageOptions: {
      ecmaVersion: 2023,
      parserOptions: {
        project: ['./tsconfig.app.json', './tsconfig.node.json'],
        tsconfigRootDir: import.meta.dirname,
      },
      globals: globals.browser,
    },
    rules: {
      // --- REACT RULES ---
      ...reactHooks.configs.recommended.rules,
      'react-refresh/only-export-components': ['error', { allowConstantExport: true }],

      // --- 1. PRODUCTION HYGIENE ---
      'no-console': ['error', { allow: ['warn', 'error'] }], // Ban console.log, allow warn/error
      'no-debugger': 'error', // Ensure no debugger statements slip into prod
      'no-alert': 'error', // Ban alert(), confirm(), prompt()

      // --- 2. ASYNC / PROMISE SAFETY (CRITICAL) ---
      // Forces you to 'await' or '.catch()' every single promise. No unhandled background tasks.
      '@typescript-eslint/no-floating-promises': 'error',
      // Prevents passing async functions to places expecting synchronous ones (e.g., Array.map)
      '@typescript-eslint/no-misused-promises': [
        'error',
        {
          checksVoidReturn: {
            attributes: false, // Allows async functions in React onClick handlers
          },
        },
      ],

      // --- 3. BULLETPROOF LOGIC & TYPES ---
      '@typescript-eslint/no-explicit-any': 'error',
      '@typescript-eslint/no-unsafe-assignment': 'error',
      '@typescript-eslint/no-unsafe-member-access': 'error',
      '@typescript-eslint/no-unsafe-return': 'error',
      '@typescript-eslint/no-unsafe-argument': 'error',
      'eqeqeq': ['error', 'always'],
      '@typescript-eslint/no-non-null-assertion': 'error',
      '@typescript-eslint/switch-exhaustiveness-check': 'error',
      '@typescript-eslint/prefer-nullish-coalescing': 'error',

      // Prevents "if (variable)" when the variable could be an empty string or 0.
      // Forces explicit checks: "if (variable !== '')"
      '@typescript-eslint/strict-boolean-expressions': [
        'error',
        {
          allowString: false,
          allowNumber: false,
          allowNullableObject: true,
        },
      ],

      // --- 4. IMMUTABILITY ---
      // Prevents reassigning function parameters (e.g., mutating an object passed to a function)
      'no-param-reassign': 'error',
      // Encourages using readonly arrays and properties where possible
      '@typescript-eslint/prefer-readonly': 'error',

      // --- 5. COMPLEXITY CAPS ---
      // Forces developers to break down massive functions into smaller, testable ones
      'complexity': ['error', 15], // Max cyclomatic complexity
      'max-depth': ['error', 3], // Max nested if/for loops (e.g., if inside an if inside a for)

      // --- 6. EXPLICIT ABSTRACTIONS ---
      '@typescript-eslint/no-magic-numbers': [
        'error',
        {
          ignoreEnums: true,
          ignoreNumericLiteralTypes: true,
          ignoreReadonlyClassProperties: true,
          ignore: [-1, 0, 1],
        },
      ],
      '@typescript-eslint/explicit-function-return-type': 'error',
      '@typescript-eslint/explicit-module-boundary-types': 'error',
    },
  },
  // --- PRETTIER OVERRIDE (Must be last) ---
  prettierConfig
);
