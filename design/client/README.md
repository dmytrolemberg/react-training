# Minimal Shop Static Prototype

This is a static HTML/CSS/JS prototype for an online shop pet project.

## Structure

- `index.html` — home page
- `products.html` — catalog with search, filters, sorting, custom checkboxes/selects, and add-to-cart counter
- `product.html` — product detail page with long WYSIWYG-ready description, 1–50 attributes support, quantity, and tabs
- `brands.html` — brands and categories directory
- `cart.html` — cart with quantity controls and totals
- `checkout.html` — checkout form with custom delivery select, custom radio controls, and confirmation state
- `login.html` — headerless sign-in page with home link and centered auth form
- `register.html` — headerless account creation page with home link and centered auth form
- `reset-password.html` — headerless password reset page with home link and reset steps
- `profile.html` — user profile page
- `orders.html` — orders list with status filters
- `order.html` — single order details page with items, payment summary, address, and delivery timeline
- `reviews.html` — reviews and ratings page with custom rating select and review form
- `styles.css` — one shared CSS file for all pages

## CSS organization

The CSS file keeps common styles first and page-specific sections later:

1. Common design tokens
2. Common reset and layout
3. Common header, nav, buttons, forms
4. Common cards, product cards, badges, ratings
5. Common responsive rules
6. Page-specific styles
7. Common responsive refinements for desktop, tablet, and mobile
8. Custom form controls
9. Large content / WYSIWYG / long attributes handling
10. Single order details page

## Responsive behavior

The layout is optimized for:

- Desktop: 1180px and wider
- Tablet: around 768px–1180px
- Mobile phones: 320px–760px

Responsive improvements include:

- Sticky header with horizontal scroll navigation on small screens
- One-column layouts for mobile
- Two-column tablet grids where useful
- Smaller cards, product media, typography, and spacing on phones
- Mobile-friendly catalog filters, product details, cart, checkout, profile, orders, reviews, brands, and categories pages

## Long content and attribute handling

- Product descriptions can come from a WYSIWYG editor and include headings, paragraphs, and lists.
- Long descriptions are collapsible in the buy panel.
- Attribute lists support short lists and long lists up to about 50 items.
- Long attribute lists use internal scrolling to avoid breaking the layout.

## Notes

- No build step required.
- Open `index.html` in a browser.
- JavaScript is included inline only in the HTML pages that need small interactions.
