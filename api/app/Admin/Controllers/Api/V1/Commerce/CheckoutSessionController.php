<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Commerce;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Commerce\CheckoutSession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Admin\Resources\Api\V1\Commerce\CheckoutSessionResource;
use App\Admin\Requests\Api\V1\Commerce\CheckoutSessionIndexRequest;

class CheckoutSessionController extends Controller
{
    public function index(CheckoutSessionIndexRequest $request): AnonymousResourceCollection
    {
        $query = CheckoutSession::query()->with(['user', 'order.items', 'order.statusEvents']);

        if ($request->filled('search')) {
            $search = mb_strtolower((string) $request->string('search'));
            $query->where(function (Builder $query) use ($search): void {
                $query->whereRaw('LOWER(contact_email) LIKE ?', ['%' . $search . '%'])
                    ->orWhereHas('order', fn(Builder $query): Builder => $query->whereRaw('LOWER(number) LIKE ?', ['%' . $search . '%']));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return CheckoutSessionResource::collection($query->latest()->paginate($request->perPage()));
    }

    public function show(CheckoutSession $checkoutSession): CheckoutSessionResource
    {
        return new CheckoutSessionResource($checkoutSession->load(['user', 'order.items', 'order.statusEvents']));
    }

    public function destroy(CheckoutSession $checkoutSession): Response
    {
        $checkoutSession->delete();

        return response()->noContent();
    }
}
