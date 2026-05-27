<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\Catalog\Brand;
use App\Models\Commerce\Cart;
use App\Models\Catalog\Review;
use App\Models\Commerce\Order;
use App\Models\Account\Address;
use App\Models\Catalog\Product;
use Illuminate\Database\Seeder;
use App\Models\Catalog\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Commerce\CartStatus;
use App\Models\Account\WishlistItem;
use App\Models\Catalog\ReviewStatus;
use App\Models\Commerce\OrderStatus;
use App\Models\Account\PaymentMethod;
use App\Models\Commerce\PaymentStatus;
use App\Models\Commerce\DeliveryMethod;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $brands = $this->seedBrands();
            $categories = $this->seedCategories();
            $products = $this->seedProducts($brands, $categories);
            $user = User::query()->where('email', 'user@example.com')->firstOrFail();

            $this->seedAccount($user, $products);
            $this->seedCart($user, $products);
            $this->seedOrders($user, $products);
            $this->seedReviews($user, $products);
        });
    }

    /**
     * @return array<string, Brand>
     */
    private function seedBrands(): array
    {
        $items = [
            'northline' => ['name' => 'Northline', 'description' => 'Outerwear and accessories focused on calm utility.', 'logo_initial' => 'N'],
            'luma' => ['name' => 'Luma', 'description' => 'Minimal home objects, lamps, and warm lighting.', 'logo_initial' => 'L'],
            'mori' => ['name' => 'Mori', 'description' => 'Daily carry bags with recycled materials.', 'logo_initial' => 'M'],
            'studio-base' => ['name' => 'Studio Base', 'description' => 'Desk tools, organizers, and quiet workspace details.', 'logo_initial' => 'S'],
            'ever' => ['name' => 'Ever', 'description' => 'Timeless essentials for everyday use.', 'logo_initial' => 'E'],
            'plain-works' => ['name' => 'Plain Works', 'description' => 'Clear, simple objects with practical attributes.', 'logo_initial' => 'P'],
        ];

        $brands = [];
        foreach ($items as $slug => $item) {
            $brands[$slug] = Brand::query()->updateOrCreate(
                ['slug' => $slug],
                $item + ['is_active' => true],
            );
        }

        return $brands;
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $items = [
            'outerwear' => ['name' => 'Outerwear', 'description' => 'Jackets, layers, weather pieces', 'position' => 10],
            'home' => ['name' => 'Home', 'description' => 'Lighting, desk, storage', 'position' => 20],
            'bags' => ['name' => 'Bags', 'description' => 'Backpacks, pouches, carry', 'position' => 30],
            'accessories' => ['name' => 'Accessories', 'description' => 'Beanies, wallets, small goods', 'position' => 40],
            'electronics' => ['name' => 'Electronics', 'description' => 'Cables, docks, essentials', 'position' => 50],
        ];

        $categories = [];
        foreach ($items as $slug => $item) {
            $categories[$slug] = Category::query()->updateOrCreate(
                ['slug' => $slug],
                $item + ['is_active' => true],
            );
        }

        return $categories;
    }

    /**
     * @param array<string, Brand> $brands
     * @param array<string, Category> $categories
     * @return array<string, Product>
     */
    private function seedProducts(array $brands, array $categories): array
    {
        $items = [
            'aero-knit-jacket' => [
                'brand' => 'northline',
                'category' => 'outerwear',
                'sku' => 'NORTH-AERO-GR',
                'name' => 'Aero Knit Jacket',
                'short_description' => 'A lightweight graphite jacket with a quiet waterproof finish.',
                'description_html' => '<p>A clean outer layer for daily weather, light movement, and calm utility.</p>',
                'price_cents' => 14800,
                'stock_quantity' => 18,
                'rating_average' => 4.8,
                'reviews_count' => 126,
                'attributes' => [['Color', 'Graphite'], ['Finish', 'Waterproof'], ['Size range', 'XS-XL']],
            ],
            'modular-desk-lamp' => [
                'brand' => 'luma',
                'category' => 'home',
                'sku' => 'LUMA-LAMP-WARM',
                'name' => 'Modular Desk Lamp',
                'short_description' => 'Minimal aluminum desk lamp with USB-C power and warm light.',
                'description_html' => '<p>A compact lamp for focused workspaces, soft light, and simple placement.</p>',
                'price_cents' => 8900,
                'stock_quantity' => 12,
                'rating_average' => 4.7,
                'reviews_count' => 84,
                'attributes' => [['Material', 'Aluminum'], ['Power', 'USB-C'], ['Light', 'Warm light']],
            ],
            'everyday-carry-pack' => [
                'brand' => 'mori',
                'category' => 'bags',
                'sku' => 'MORI-18-GR',
                'name' => 'Everyday Carry Pack',
                'short_description' => 'A minimal 18L pack built from recycled textile for work and travel.',
                'description_html' => '<p><strong>Clean structure, padded laptop section, front organizer, soft back panel, and a balanced shape for everyday use.</strong></p><p>This content block supports long WYSIWYG product copy without breaking the buy panel.</p>',
                'price_cents' => 11200,
                'stock_quantity' => 24,
                'rating_average' => 4.9,
                'reviews_count' => 219,
                'attributes' => [
                    ['Volume', '18L'], ['Laptop size', 'Up to 15 in'], ['Material', 'Recycled textile'], ['Color', 'Graphite'],
                    ['Finish', 'Water resistant'], ['Weight', '720g'], ['Height', '44 cm'], ['Width', '29 cm'], ['Depth', '14 cm'],
                    ['Main pocket', 'Open storage'], ['Laptop pocket', 'Padded'], ['Front pocket', 'Organizer'], ['Back panel', 'Soft padded'],
                    ['Straps', 'Adjustable'], ['Closure', 'Two-way zipper'], ['Warranty', '2 years'], ['Return window', '30 days'],
                    ['Delivery', '2-4 days'], ['Recommended for', 'Commuting'], ['Style', 'Minimal'],
                ],
            ],
            'soft-wool-beanie' => [
                'brand' => 'northline',
                'category' => 'accessories',
                'sku' => 'NORTH-BEANIE-BLK',
                'name' => 'Soft Wool Beanie',
                'short_description' => 'Soft merino beanie in black with a one-size fit.',
                'description_html' => '<p>A small cold-weather essential with a soft merino feel.</p>',
                'price_cents' => 3600,
                'stock_quantity' => 40,
                'rating_average' => 4.5,
                'reviews_count' => 42,
                'attributes' => [['Material', 'Merino'], ['Color', 'Black'], ['Fit', 'One size']],
            ],
            'ceramic-cable-dock' => [
                'brand' => 'studio-base',
                'category' => 'home',
                'sku' => 'BASE-DOCK-MATTE',
                'name' => 'Ceramic Cable Dock',
                'short_description' => 'Matte ceramic cable dock for a quieter desk surface.',
                'description_html' => '<p>A small desk object for cable routing and clean daily setup.</p>',
                'price_cents' => 4200,
                'stock_quantity' => 0,
                'rating_average' => 4.4,
                'reviews_count' => 31,
                'attributes' => [['Material', 'Ceramic'], ['Finish', 'Matte'], ['Use', 'Desk']],
            ],
            'travel-tech-pouch' => [
                'brand' => 'mori',
                'category' => 'bags',
                'sku' => 'MORI-POUCH-2L',
                'name' => 'Travel Tech Pouch',
                'short_description' => 'A 2L organizer pouch for cables, adapters, and small travel tools.',
                'description_html' => '<p>Compact organization for daily carry and short trips.</p>',
                'price_cents' => 5800,
                'stock_quantity' => 28,
                'rating_average' => 4.6,
                'reviews_count' => 73,
                'attributes' => [['Volume', '2L'], ['Type', 'Organizer'], ['Material', 'Recycled']],
            ],
            'hidden-archive-jacket' => [
                'brand' => 'northline',
                'category' => 'outerwear',
                'sku' => 'NORTH-HIDDEN-001',
                'name' => 'Hidden Archive Jacket',
                'short_description' => 'Inactive fixture product.',
                'description_html' => '<p>This product should not be visible to clients.</p>',
                'price_cents' => 10000,
                'stock_quantity' => 10,
                'rating_average' => 5.0,
                'reviews_count' => 0,
                'is_active' => false,
                'attributes' => [['State', 'Inactive']],
            ],
        ];

        $products = [];
        foreach ($items as $slug => $item) {
            $product = Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'brand_id' => $brands[$item['brand']]->id,
                    'category_id' => $categories[$item['category']]->id,
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'short_description' => $item['short_description'],
                    'description_html' => $item['description_html'],
                    'price_cents' => $item['price_cents'],
                    'currency' => 'USD',
                    'stock_quantity' => $item['stock_quantity'],
                    'is_active' => $item['is_active'] ?? true,
                    'rating_average' => $item['rating_average'],
                    'reviews_count' => $item['reviews_count'],
                ],
            );

            $product->attributes()->delete();
            foreach ($item['attributes'] as $position => [$name, $value]) {
                $product->attributes()->create([
                    'name' => $name,
                    'value' => $value,
                    'position' => $position + 1,
                ]);
            }

            $product->images()->delete();
            foreach (range(1, 3) as $position) {
                $product->images()->create([
                    'url' => 'https://example.com/images/' . $slug . '-' . $position . '.jpg',
                    'alt' => $item['name'] . ' image ' . $position,
                    'position' => $position,
                    'is_primary' => $position === 1,
                ]);
            }

            $products[$slug] = $product->refresh();
        }

        return $products;
    }

    /**
     * @param array<string, Product> $products
     */
    private function seedAccount(User $user, array $products): void
    {
        Address::query()->where('user_id', $user->id)->delete();
        Address::query()->create([
            'user_id' => $user->id,
            'label' => 'Home',
            'first_name' => 'Dmytro',
            'last_name' => 'Orikhovskyi',
            'phone' => '+380000000000',
            'country' => 'Ukraine',
            'city' => 'Kyiv',
            'postal_code' => '01001',
            'address_line' => 'Street address placeholder',
            'is_default' => true,
        ]);
        Address::query()->create([
            'user_id' => $user->id,
            'label' => 'Office',
            'first_name' => 'Dmytro',
            'last_name' => 'Orikhovskyi',
            'phone' => '+380000000000',
            'country' => 'Ukraine',
            'city' => 'Kyiv',
            'postal_code' => '01001',
            'address_line' => 'Office address placeholder',
            'is_default' => false,
        ]);

        PaymentMethod::query()->where('user_id', $user->id)->delete();
        PaymentMethod::query()->create([
            'user_id' => $user->id,
            'type' => 'mock_card',
            'label' => 'Visa ending 4242',
            'brand' => 'Visa',
            'last_four' => '4242',
            'expires_month' => 12,
            'expires_year' => 2028,
            'mock_token' => 'pm_mock_visa_4242',
            'is_default' => true,
        ]);
        PaymentMethod::query()->create([
            'user_id' => $user->id,
            'type' => 'mock_card',
            'label' => 'Mastercard ending 1881',
            'brand' => 'Mastercard',
            'last_four' => '1881',
            'expires_month' => 9,
            'expires_year' => 2027,
            'mock_token' => 'pm_mock_mastercard_1881',
            'is_default' => false,
        ]);

        WishlistItem::query()->where('user_id', $user->id)->delete();
        foreach (['aero-knit-jacket', 'everyday-carry-pack', 'travel-tech-pouch'] as $slug) {
            WishlistItem::query()->create([
                'user_id' => $user->id,
                'product_id' => $products[$slug]->id,
            ]);
        }
    }

    /**
     * @param array<string, Product> $products
     */
    private function seedCart(User $user, array $products): void
    {
        Cart::query()->where('user_id', $user->id)->delete();

        $cart = Cart::query()->create([
            'user_id' => $user->id,
            'status' => CartStatus::Active->value,
            'currency' => 'USD',
        ]);

        foreach (['everyday-carry-pack', 'modular-desk-lamp'] as $slug) {
            $product = $products[$slug];
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price_cents' => $product->price_cents,
                'currency' => $product->currency,
            ]);
        }
    }

    /**
     * @param array<string, Product> $products
     */
    private function seedOrders(User $user, array $products): void
    {
        Order::query()->where('user_id', $user->id)->delete();

        $order = Order::query()->create([
            'user_id' => $user->id,
            'number' => '1048',
            'status' => OrderStatus::Processing->value,
            'payment_status' => PaymentStatus::Paid->value,
            'currency' => 'USD',
            'subtotal_cents' => 20100,
            'delivery_cents' => 0,
            'tax_cents' => 1608,
            'total_cents' => 21708,
            'contact_email' => 'dmytro@example.com',
            'contact_phone' => '+380000000000',
            'shipping_first_name' => 'Dmytro',
            'shipping_last_name' => 'Orikhovskyi',
            'shipping_country' => 'Ukraine',
            'shipping_city' => 'Kyiv',
            'shipping_postal_code' => '01001',
            'shipping_address_line' => 'Street address placeholder',
            'delivery_method' => DeliveryMethod::Standard->value,
            'payment_method_type' => 'mock_card',
            'payment_method_label' => 'Visa ending 4242',
            'transaction_id' => 'TX-5428-NS',
            'placed_at' => now()->subDay(),
        ]);

        foreach (['everyday-carry-pack' => 1, 'modular-desk-lamp' => 1] as $slug => $quantity) {
            $product = $products[$slug]->loadMissing(['brand', 'category', 'attributes']);
            $order->items()->create([
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'sku' => $product->sku,
                'name' => $product->name,
                'brand_name' => $product->brand->name,
                'category_name' => $product->category->name,
                'unit_price_cents' => $product->price_cents,
                'quantity' => $quantity,
                'total_cents' => $product->price_cents * $quantity,
                'attributes' => $product->attributes->take(3)->map(fn($attribute): array => [
                    'name' => $attribute->name,
                    'value' => $attribute->value,
                ])->values()->all(),
            ]);
        }

        foreach ([
            ['status' => OrderStatus::Processing, 'label' => 'Order placed', 'description' => 'May 26, 09:42', 'position' => 10],
            ['status' => OrderStatus::Processing, 'label' => 'Payment confirmed', 'description' => 'May 26, 09:43', 'position' => 20],
            ['status' => OrderStatus::Processing, 'label' => 'Preparing shipment', 'description' => 'Estimated dispatch today', 'position' => 30],
        ] as $event) {
            $order->statusEvents()->create([
                'status' => $event['status']->value,
                'label' => $event['label'],
                'description' => $event['description'],
                'occurred_at' => now()->subDay(),
                'position' => $event['position'],
            ]);
        }
    }

    /**
     * @param array<string, Product> $products
     */
    private function seedReviews(User $user, array $products): void
    {
        Review::query()->delete();

        foreach ([
            ['product' => 'everyday-carry-pack', 'rating' => 5, 'author_name' => 'Anna', 'body' => 'Very clean product details. Filters helped me find the right size and material fast.'],
            ['product' => 'aero-knit-jacket', 'rating' => 5, 'author_name' => 'Mark', 'body' => 'Minimal design, fast checkout, and product attributes were clear.'],
            ['product' => 'modular-desk-lamp', 'rating' => 4, 'author_name' => 'Sofia', 'body' => 'Good product page and very helpful rating overview.'],
        ] as $review) {
            Review::query()->create([
                'product_id' => $products[$review['product']]->id,
                'user_id' => $user->id,
                'order_item_id' => null,
                'rating' => $review['rating'],
                'body' => $review['body'],
                'author_name' => $review['author_name'],
                'status' => ReviewStatus::Approved->value,
                'is_verified_purchase' => false,
            ]);
        }

        Review::query()->create([
            'product_id' => $products['travel-tech-pouch']->id,
            'user_id' => $user->id,
            'order_item_id' => null,
            'rating' => 3,
            'body' => 'Pending moderation fixture.',
            'author_name' => 'Hidden',
            'status' => ReviewStatus::Pending->value,
            'is_verified_purchase' => false,
        ]);
    }
}
