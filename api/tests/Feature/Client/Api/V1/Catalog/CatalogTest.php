<?php

declare(strict_types = 1);

namespace Tests\Feature\Client\Api\V1\Catalog;

use Tests\TestCase;

class CatalogTest extends TestCase
{
    public function testHomeEndpointReturnsDiscoveryData(): void
    {
        $this->getJson('/api/v1/catalog/home')
            ->assertOk()
            ->assertJsonPath('data.featured_products.0.name', 'Everyday Carry Pack')
            ->assertJsonCount(4, 'data.categories');
    }

    public function testProductsEndpointReturnsOnlyActiveProducts(): void
    {
        $this->getJson('/api/v1/catalog/products')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'everyday-carry-pack')
            ->assertJsonMissing(['slug' => 'hidden-archive-jacket']);
    }

    public function testProductsCanBeFilteredAndSorted(): void
    {
        $this->getJson('/api/v1/catalog/products?categories[]=bags&brands[]=mori&min_rating=4.5&in_stock=1&sort=price_asc')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'travel-tech-pouch')
            ->assertJsonPath('data.1.slug', 'everyday-carry-pack');
    }

    public function testProductsSupportSearchPaginationAndSortAliases(): void
    {
        $this->getJson('/api/v1/catalog/products?search=desk&per_page=1&sort=priceDesc')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'modular-desk-lamp')
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonPath('meta.total', 2);
    }

    public function testInvalidProductFilterFailsValidation(): void
    {
        $this->getJson('/api/v1/catalog/products?min_rating=9')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['min_rating']);
    }

    public function testInvalidProductFilterCollectionsFailValidation(): void
    {
        $this->getJson('/api/v1/catalog/products?search=' . str_repeat('a', 101) . '&categories[]=missing&brands[]=missing&sort=unknown&per_page=100')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['search', 'categories.0', 'brands.0', 'sort', 'per_page']);
    }

    public function testProductDetailIncludesAttributesAndRelatedProducts(): void
    {
        $this->getJson('/api/v1/catalog/products/everyday-carry-pack')
            ->assertOk()
            ->assertJsonPath('data.slug', 'everyday-carry-pack')
            ->assertJsonPath('data.attributes.0.name', 'Volume')
            ->assertJsonPath('related.0.slug', 'travel-tech-pouch');
    }

    public function testInactiveProductDetailIsHidden(): void
    {
        $this->getJson('/api/v1/catalog/products/hidden-archive-jacket')
            ->assertNotFound();
    }

    public function testUnknownProductDetailIsNotFound(): void
    {
        $this->getJson('/api/v1/catalog/products/not-a-real-product')
            ->assertNotFound();
    }

    public function testBrandsAndCategoriesReturnProductCounts(): void
    {
        $this->getJson('/api/v1/catalog/brands')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'mori'])
            ->assertJsonPath('data.2.products_count', 2);

        $this->getJson('/api/v1/catalog/categories')
            ->assertOk()
            ->assertJsonFragment(['slug' => 'bags'])
            ->assertJsonFragment(['slug' => 'outerwear']);
    }
}
