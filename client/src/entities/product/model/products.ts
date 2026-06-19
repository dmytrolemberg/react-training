import { type ProductPreview, type Product } from './types.ts';

const AERO_KNIT_JACKET_PREVIEW = {
  slug: 'aero-knit-jacket',
  name: 'Aero Knit Jacket',
  category: 'Outerwear',
  brand: 'Northline',
  price: 148,
  rating: 4.2,
  reviewsCount: 126,
  isStock: true,
  attributeValues: ['Graphite', 'Waterproof', 'XS-XL'],
  image: 'path/to/aero-knit-jacket.jpg'
} as const satisfies ProductPreview;

const MODULAR_DESK_LAMP_PREVIEW = {
  slug: 'modular-desk-lamp',
  name: 'Modular Desk Lamp',
  category: 'Home',
  brand: 'Luma',
  price: 89,
  rating: 4.5,
  reviewsCount: 84,
  isStock: true,
  attributeValues: ['Aluminum', 'USB-C', 'Warm light'],
  image: 'path/to/modular-desk-lamp.jpg'
} as const satisfies ProductPreview;

const EVERYDAY_CARRY_PACK_PREVIEW = {
  slug: 'everyday-carry-pack',
  name: 'Everyday Carry Pack',
  category: 'Bags',
  brand: 'Mori',
  price: 112,
  rating: 3.3,
  reviewsCount: 219,
  isStock: true,
  attributeValues: ['18L', 'Recycled', 'Laptop 15"'],
  image: 'path/to/everyday-carry-pack.jpg'
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
    reviewsCount: 72,
    isStock: true,
    attributeValues: ['2L', 'Organizer'],
    image: 'path/to/travel-tech-pouch'
  },
  PRODUCT_PREVIEWS[0],
  {
    slug: 'soft-wool-beanie',
    name: 'Soft Wool Beanie',
    category: 'Accessories',
    brand: 'Northline',
    price: 36,
    rating: 4.5,
    reviewsCount: 48,
    isStock: true,
    attributeValues: ['Merino', 'One size'],
    image: 'path/to/soft-wool-beanie'
  },
] as const;

export const PRODUCT = {
  slug: 'aero-knit-jacket',
  name: 'Aero Knit Jacket',
  category: 'Outerwear',
  brand: 'Northline',
  price: 148,
  rating: 4.2,
  reviewsCount: 126,
  isStock: true,
  attributeValues: ['Graphite', 'Waterproof', 'XS-XL'],
  image: 'path/to/aero-knit-jacket.jpg',
  description: "The Aero Knit Jacket is a versatile and stylish outerwear piece designed for comfort and performance. Crafted with high-quality materials, it offers excellent insulation while remaining lightweight. The jacket features a sleek design with a modern fit, making it suitable for various occasions, from casual outings to outdoor adventures. Its waterproof properties ensure you stay dry in wet conditions, while the breathable fabric allows for optimal airflow. With its range of sizes from XS to XL, the Aero Knit Jacket caters to different body types, providing a perfect fit for everyone. Whether you're exploring the city or hitting the trails, this jacket is your go-to choice for staying warm and fashionable.",
  attributes: [
    { name: 'Color', value: 'Graphite' },
    { name: 'Material', value: 'Waterproof' },
    { name: 'Size', value: 'XS-XL' },
  ],
  images: ['path/to/aero-knit-jacket.jpg', 'path/to/aero-knit-jacket-back.jpg', 'path/to/aero-knit-jacket-side.jpg'],
} as const satisfies Product;
