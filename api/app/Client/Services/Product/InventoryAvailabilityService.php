<?php

declare(strict_types = 1);

namespace App\Client\Services\Product;

use App\Models\Catalog\Product;
use Illuminate\Validation\ValidationException;

class InventoryAvailabilityService
{
    /**
     * @throws ValidationException
     */
    public function ensureProductCanBePurchased(Product $product, int $quantity, string $field = 'product_id'): void
    {
        if (!$product->is_active) {
            throw ValidationException::withMessages([$field => ['This product is not available.']]);
        }

        if ($product->stock_quantity < $quantity) {
            throw ValidationException::withMessages(['quantity' => ['Requested quantity is not available in stock.']]);
        }
    }
}
