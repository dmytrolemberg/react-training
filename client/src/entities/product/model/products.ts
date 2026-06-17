import { type ProductPreview } from './types.ts';

const AERO_KNIT_JACKET_PREVIEW = {
  slug: 'aero-knit-jacket',
  name: 'Aero Knit Jacket',
  category: 'Outerwear',
  brand: 'Northline',
  price: 148,
  rating: 4.2,
  reviews: 126,
  stock: true,
  attributes: ['Graphite', 'Waterproof', 'XS-XL'],
} as const satisfies ProductPreview;

const MODULAR_DESK_LAMP_PREVIEW = {
  slug: 'modular-desk-lamp',
  name: 'Modular Desk Lamp',
  category: 'Home',
  brand: 'Luma',
  price: 89,
  rating: 4.5,
  reviews: 84,
  stock: true,
  attributes: ['Aluminum', 'USB-C', 'Warm light'],
} as const satisfies ProductPreview;

const EVERYDAY_CARRY_PACK_PREVIEW = {
  slug: 'everyday-carry-pack',
  name: 'Everyday Carry Pack',
  category: 'Bags',
  brand: 'Mori',
  price: 112,
  rating: 3.3,
  reviews: 219,
  stock: true,
  attributes: ['18L', 'Recycled', 'Laptop 15"'],
} as const satisfies ProductPreview;

export const PRODUCT_PREVIEWS = [
  AERO_KNIT_JACKET_PREVIEW,
  MODULAR_DESK_LAMP_PREVIEW,
  EVERYDAY_CARRY_PACK_PREVIEW,
] as const;

export const RELATED_PRODUCT_PREVIEWS: readonly ProductPreview[] = [
  {
    slug: 'travel-tech-pouch',
    name: 'Travel Tech Pouch',
    category: 'Bags',
    brand: 'Mori',
    price: 58,
    rating: 4.6,
    reviews: 72,
    stock: true,
    attributes: ['2L', 'Organizer'],
  },
  PRODUCT_PREVIEWS[0],
  {
    slug: 'soft-wool-beanie',
    name: 'Soft Wool Beanie',
    category: 'Accessories',
    brand: 'Northline',
    price: 36,
    rating: 4.5,
    reviews: 48,
    stock: true,
    attributes: ['Merino', 'One size'],
  },
] as const;
