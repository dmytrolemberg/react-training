<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\Api\V1\AuthController;
use App\Admin\Controllers\Api\V1\PingController;
use App\Admin\Controllers\Api\V1\DashboardController;
use App\Admin\Controllers\Api\V1\Catalog\BrandController;
use App\Admin\Controllers\Api\V1\Commerce\CartController;
use App\Admin\Controllers\Api\V1\Review\ReviewController;
use App\Admin\Controllers\Api\V1\Commerce\OrderController;
use App\Admin\Controllers\Api\V1\User\AdminUserController;
use App\Admin\Controllers\Api\V1\Catalog\ProductController;
use App\Admin\Controllers\Api\V1\Catalog\CategoryController;
use App\Admin\Controllers\Api\V1\Catalog\InventoryController;
use App\Admin\Controllers\Api\V1\Customer\CustomerController;
use App\Admin\Controllers\Api\V1\Settings\SettingsController;
use App\Admin\Controllers\Api\V1\Catalog\ProductImageController;
use App\Admin\Controllers\Api\V1\Catalog\ProductAttributeController;
use App\Admin\Controllers\Api\V1\Commerce\CheckoutSessionController;

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    Route::get('/ping', PingController::class);
    Route::get('/dashboard', DashboardController::class);

    Route::apiResource('products', ProductController::class);
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/inventory/{product}', [InventoryController::class, 'show']);
    Route::patch('/products/{product}/inventory', [InventoryController::class, 'update']);
    Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
    Route::patch('/product-images/{image}', [ProductImageController::class, 'update']);
    Route::delete('/product-images/{image}', [ProductImageController::class, 'destroy']);
    Route::post('/products/{product}/attributes', [ProductAttributeController::class, 'store']);
    Route::patch('/attributes/{attribute}', [ProductAttributeController::class, 'update']);
    Route::delete('/attributes/{attribute}', [ProductAttributeController::class, 'destroy']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);

    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::patch('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus']);

    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('reviews', ReviewController::class);
    Route::apiResource('carts', CartController::class);
    Route::apiResource('checkout-sessions', CheckoutSessionController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('admin-users', AdminUserController::class);

    Route::get('/settings', [SettingsController::class, 'show']);
    Route::patch('/settings', [SettingsController::class, 'update']);
});
