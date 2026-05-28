<?php

declare(strict_types = 1);

namespace Tests\Feature\Admin\Api\V1\Catalog;

use App\Models\Catalog\Brand;
use App\Models\Catalog\Product;
use App\Models\Catalog\Category;
use Tests\Feature\Admin\Api\V1\AdminApiTestCase;

class CatalogManagementTest extends AdminApiTestCase
{
    public function testAdminCanManageProductsImagesAttributesAndInventory(): void
    {
        $brand = Brand::query()->where('slug', 'mori')->firstOrFail();
        $category = Category::query()->where('slug', 'bags')->firstOrFail();

        $this->actingAsAdmin()
            ->getJson('/admin/api/v1/products?search=carry')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'everyday-carry-pack');

        $create = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/products', [
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'slug' => 'admin-created-pack',
                'sku' => 'ADMIN-PACK-001',
                'name' => 'Admin Created Pack',
                'short_description' => 'Created from admin API.',
                'description_html' => '<p>Created from admin API.</p>',
                'price_cents' => 9900,
                'stock_quantity' => 7,
                'is_active' => true,
                'images' => [
                    ['url' => 'https://example.com/admin-created-pack.jpg', 'alt' => 'Admin pack', 'is_primary' => true],
                ],
                'attributes' => [
                    ['name' => 'Volume', 'value' => '20L'],
                ],
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'admin-created-pack')
            ->assertJsonPath('data.images.0.is_primary', true)
            ->assertJsonPath('data.attributes.0.name', 'Volume');

        $productId = $create->json('data.id');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/products/' . $productId . '/inventory', ['stock_quantity' => 12])
            ->assertOk()
            ->assertJsonPath('data.stock_quantity', 12);

        $image = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/products/' . $productId . '/images', [
                'url' => 'https://example.com/admin-created-pack-side.jpg',
                'is_primary' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.is_primary', true);

        $attribute = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/products/' . $productId . '/attributes', [
                'name' => 'Material',
                'value' => 'Canvas',
            ])
            ->assertCreated()
            ->assertJsonPath('data.value', 'Canvas');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/products/' . $productId, ['name' => 'Renamed Admin Pack', 'is_active' => false])
            ->assertOk()
            ->assertJsonPath('data.name', 'Renamed Admin Pack')
            ->assertJsonPath('data.is_active', false);

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/attributes/' . $attribute->json('data.id'))
            ->assertNoContent();

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/product-images/' . $image->json('data.id'))
            ->assertNoContent();

        $this->actingAsAdmin()
            ->deleteJson('/admin/api/v1/products/' . $productId)
            ->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }

    public function testAdminCanManageCategoriesAndBrands(): void
    {
        $category = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/categories', [
                'slug' => 'admin-category',
                'name' => 'Admin Category',
                'description' => 'Temporary category.',
                'position' => 99,
                'is_active' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'admin-category');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/categories/' . $category->json('data.id'), ['position' => 100])
            ->assertOk()
            ->assertJsonPath('data.position', 100);

        $brand = $this->actingAsAdmin()
            ->postJson('/admin/api/v1/brands', [
                'slug' => 'admin-brand',
                'name' => 'Admin Brand',
                'description' => 'Temporary brand.',
                'logo_initial' => 'A',
                'is_active' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'admin-brand');

        $this->actingAsAdmin()
            ->patchJson('/admin/api/v1/brands/' . $brand->json('data.id'), ['is_active' => false])
            ->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->actingAsAdmin()->deleteJson('/admin/api/v1/categories/' . $category->json('data.id'))->assertNoContent();
        $this->actingAsAdmin()->deleteJson('/admin/api/v1/brands/' . $brand->json('data.id'))->assertNoContent();
    }

    public function testAdminProductValidationRejectsInvalidPayload(): void
    {
        $this->actingAsAdmin()
            ->postJson('/admin/api/v1/products', [
                'slug' => 'bad-product',
                'price_cents' => -1,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['brand_id', 'category_id', 'sku', 'name', 'price_cents']);
    }

    public function testRegularUserCannotAccessCatalogManagement(): void
    {
        $product = Product::query()->firstOrFail();

        $this->actingAs($this->user())
            ->patchJson('/admin/api/v1/products/' . $product->id, ['name' => 'Nope'])
            ->assertForbidden();
    }
}
