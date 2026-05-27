<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1\Order;

use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Commerce\Order;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Client\Requests\Api\V1\Order\OrderIndexRequest;
use App\Client\Resources\Api\V1\Order\OrderDetailResource;
use App\Client\Resources\Api\V1\Order\OrderSummaryResource;
use App\Client\Resources\Api\V1\Order\OrderStatusEventResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request): AnonymousResourceCollection
    {
        $query = Order::query()
            ->where('user_id', $this->user($request)->id)
            ->with('items')
            ->latest('placed_at')
            ->orderByDesc('id');

        $status = $request->input('status');
        if (is_string($status) && $status !== '') {
            $query->where('status', $status);
        }

        return OrderSummaryResource::collection($query->paginate($request->perPage()));
    }

    public function show(Request $request, string $number): OrderDetailResource
    {
        $order = $this->orderForUser($this->user($request), $number);

        return new OrderDetailResource($order);
    }

    public function tracking(Request $request, string $number): JsonResponse
    {
        $order = $this->orderForUser($this->user($request), $number);

        return response()->json([
            'data' => [
                'order_number' => $order->number,
                'status' => $order->status->value,
                'timeline' => OrderStatusEventResource::collection($order->statusEvents),
            ],
        ]);
    }

    private function orderForUser(User $user, string $number): Order
    {
        return Order::query()
            ->where('user_id', $user->id)
            ->where('number', $number)
            ->with(['items', 'statusEvents'])
            ->firstOrFail();
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
