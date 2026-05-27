<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('logo_initial', 4)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('short_description');
            $table->longText('description_html');
            $table->unsignedInteger('price_cents');
            $table->char('currency', 3)->default('USD');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'stock_quantity']);
            $table->index(['brand_id', 'category_id']);
        });

        Schema::create('product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('alt')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'position']);
        });

        Schema::create('product_attributes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('value');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'position']);
        });

        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_item_id')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->string('author_name')->nullable();
            $table->string('status')->index();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'status', 'rating']);
        });

        Schema::create('carts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->index();
            $table->char('currency', 3)->default('USD');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('unit_price_cents');
            $table->char('currency', 3)->default('USD');
            $table->timestamps();

            $table->unique(['cart_id', 'product_id']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('status')->index();
            $table->string('payment_status')->index();
            $table->char('currency', 3)->default('USD');
            $table->unsignedInteger('subtotal_cents');
            $table->unsignedInteger('delivery_cents');
            $table->unsignedInteger('tax_cents');
            $table->unsignedInteger('total_cents');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('shipping_first_name');
            $table->string('shipping_last_name');
            $table->string('shipping_country')->default('Ukraine');
            $table->string('shipping_city');
            $table->string('shipping_postal_code');
            $table->string('shipping_address_line');
            $table->string('delivery_method');
            $table->string('payment_method_type');
            $table->string('payment_method_label');
            $table->string('transaction_id')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_slug');
            $table->string('sku');
            $table->string('name');
            $table->string('brand_name');
            $table->string('category_name');
            $table->unsignedInteger('unit_price_cents');
            $table->unsignedSmallInteger('quantity');
            $table->unsignedInteger('total_cents');
            $table->json('attributes');
            $table->timestamps();
        });

        Schema::table('reviews', function (Blueprint $table): void {
            $table->foreign('order_item_id')->references('id')->on('order_items')->nullOnDelete();
        });

        Schema::create('order_status_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['order_id', 'position']);
        });

        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('country')->default('Ukraine');
            $table->string('city');
            $table->string('postal_code');
            $table->string('address_line');
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        Schema::create('payment_methods', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('label');
            $table->string('brand')->nullable();
            $table->string('last_four', 4)->nullable();
            $table->unsignedTinyInteger('expires_month')->nullable();
            $table->unsignedSmallInteger('expires_year')->nullable();
            $table->string('mock_token')->nullable()->unique();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        Schema::create('wishlist_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('order_status_events');
        Schema::table('reviews', function (Blueprint $table): void {
            $table->dropForeign(['order_item_id']);
        });
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};
