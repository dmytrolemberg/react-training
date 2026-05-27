<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use App\Client\Controllers\Api\V1\AuthController;
use App\Client\Controllers\Api\V1\PingController;
use App\Client\Controllers\Api\V1\Cart\CartController;
use App\Client\Controllers\Api\V1\Order\OrderController;
use App\Client\Controllers\Api\V1\Review\ReviewController;
use App\Client\Controllers\Api\V1\Catalog\CatalogController;
use App\Client\Controllers\Api\V1\Profile\AddressController;
use App\Client\Controllers\Api\V1\Profile\ProfileController;
use App\Client\Controllers\Api\V1\Profile\WishlistController;
use App\Client\Controllers\Api\V1\Checkout\CheckoutController;
use App\Client\Controllers\Api\V1\Profile\PaymentMethodController;

Route::get('/ping', PingController::class);

Route::prefix('catalog')->group(function (): void {
    Route::get('/home', [CatalogController::class, 'home']);
    Route::get('/products', [CatalogController::class, 'products']);
    Route::get('/products/{slug}', [CatalogController::class, 'product']);
    Route::get('/brands', [CatalogController::class, 'brands']);
    Route::get('/categories', [CatalogController::class, 'categories']);
});

Route::get('/reviews', [ReviewController::class, 'index']);

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::patch('/cart/items/{item}', [CartController::class, 'update']);
    Route::delete('/cart/items/{item}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);

    Route::get('/checkout/options', [CheckoutController::class, 'options']);
    Route::post('/checkout/orders', [CheckoutController::class, 'store']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{number}', [OrderController::class, 'show']);
    Route::get('/orders/{number}/tracking', [OrderController::class, 'tracking']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/addresses', [AddressController::class, 'index']);
    Route::post('/profile/addresses', [AddressController::class, 'store']);
    Route::patch('/profile/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy']);
    Route::get('/profile/payment-methods', [PaymentMethodController::class, 'index']);
    Route::post('/profile/payment-methods', [PaymentMethodController::class, 'store']);
    Route::delete('/profile/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy']);
    Route::get('/profile/wishlist', [WishlistController::class, 'index']);
    Route::post('/profile/wishlist', [WishlistController::class, 'store']);
    Route::delete('/profile/wishlist/{wishlistItem}', [WishlistController::class, 'destroy']);

    Route::post('/reviews', [ReviewController::class, 'store']);
});
