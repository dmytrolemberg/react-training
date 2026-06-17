export interface ProductPreview {
  readonly slug: string;
  readonly name: string;
  readonly category: string;
  readonly brand: string;
  readonly price: number;
  readonly rating: number;
  readonly reviews: number;
  readonly stock: boolean;
  readonly attributes: readonly string[];
}
