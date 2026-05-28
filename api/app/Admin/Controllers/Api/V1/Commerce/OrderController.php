<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Commerce;

use Illuminate\Http\Response;
use App\Models\Commerce\Order;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Admin\Resources\Api\V1\Commerce\OrderResource;
use App\Admin\Requests\Api\V1\Commerce\OrderIndexRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Admin\Requests\Api\V1\Commerce\UpdateOrderStatusRequest;
use App\Admin\Requests\Api\V1\Commerce\UpdatePaymentStatusRequest;

class OrderController extends Controller
{
    public function index(OrderIndexRequest $request): AnonymousResourceCollection
    {
        $query = Order::query()->with(['user', 'items', 'statusEvents']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(function (Builder $query) use ($search): void {
                $query->whereRaw('LOWER(number) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(contact_email) LIKE ?', ['%' . $search . '%'])
                    ->orWhereHas('user', fn(Builder $query): Builder => $query->whereRaw('LOWER(email) LIKE ?', ['%' . $search . '%']));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        return OrderResource::collection($query->latest('placed_at')->paginate($request->perPage()));
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load(['user', 'items', 'statusEvents']));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): OrderResource
    {
        /** @var array{status: string, label?: string, description?: string|null} $data */
        $data = $request->validated();
        $maxPosition = $order->statusEvents()->max('position');
        $position = is_numeric($maxPosition) ? (int) $maxPosition + 10 : 10;

        $order->forceFill(['status' => $data['status']])->save();
        $order->statusEvents()->create([
            'status' => $data['status'],
            'label' => $data['label'] ?? 'Status updated',
            'description' => $data['description'] ?? null,
            'occurred_at' => now(),
            'position' => $position,
        ]);

        return new OrderResource($order->refresh()->load(['user', 'items', 'statusEvents']));
    }

    public function updatePaymentStatus(UpdatePaymentStatusRequest $request, Order $order): OrderResource
    {
        $data = $request->validated();
        $paymentStatus = $data['payment_status'] ?? null;
        abort_unless(is_string($paymentStatus), 422);

        $order->forceFill(['payment_status' => $paymentStatus])->save();

        return new OrderResource($order->refresh()->load(['user', 'items', 'statusEvents']));
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }
}
