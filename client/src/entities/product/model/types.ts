export interface ProductPreview {
  readonly slug: string;
  readonly name: string;
  readonly category: string;
  readonly brand: string;
  readonly price: number;
  readonly rating: number;
  readonly reviewsCount: number;
  readonly isStock: boolean;
  readonly attributeValues: readonly string[];
  readonly image: string;
}

export interface Product extends ProductPreview {
  readonly description: string;
  readonly attributes: readonly Record<string, string>[];
  readonly images: readonly string[];
}
