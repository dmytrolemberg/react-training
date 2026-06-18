import { type BrandPreview } from './types.ts';

const HOME = {
  id: 1,
  name: 'Home',
  description: 'Comfortable and stylish living spaces',
} as const satisfies BrandPreview;

const ACCESSORIES = {
  id: 2,
  name: 'Accessories',
  description: 'Enhance your style with our curated selection of accessories',
} as const satisfies BrandPreview;

const CLOTHES = {
  id: 3,
  name: 'Clothes',
  description: 'A wide range of clothing options for every occasion',
} as const satisfies BrandPreview;

const SHOES = {
  id: 4,
  name: 'Shoes',
  description: 'A variety of stylish shoes for every need',
} as const satisfies BrandPreview;

export const BRAND_PREVIEWS = [
  HOME,
  ACCESSORIES,
  CLOTHES,
  SHOES
] as const;
