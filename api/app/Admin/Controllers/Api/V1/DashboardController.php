<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1;

use App\Models\User\User;
use App\Models\Commerce\Cart;
use App\Models\Catalog\Review;
use App\Models\Commerce\Order;
use App\Models\Catalog\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Commerce\CheckoutSession;

class DashboardController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $recentOrders = Order::query()
            ->with('user')
            ->latest('placed_at')
            ->limit(5)
            ->get()
            ->map(fn(Order $order): array => [
                'id' => $order->id,
                'number' => $order->number,
                'customer' => $order->user->full_name,
                'status' => $order->status->value,
                'payment_status' => $order->payment_status->value,
                'total_cents' => $order->total_cents,
                'placed_at' => $order->placed_at?->toISOString(),
            ]);

        $lowStock = Product::query()
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get(['id', 'sku', 'name', 'stock_quantity']);

        return response()->json([
            'data' => [
                'totals' => [
                    'products' => Product::query()->count(),
                    'orders' => Order::query()->count(),
                    'customers' => User::query()->where('role', 'user')->count(),
                    'reviews' => Review::query()->count(),
                    'active_carts' => Cart::query()->count(),
                    'checkout_sessions' => CheckoutSession::query()->count(),
                ],
                'revenue_cents' => (int) Order::query()->sum('total_cents'),
                'recent_orders' => $recentOrders,
                'low_stock_products' => $lowStock,
            ],
        ]);
    }
}
