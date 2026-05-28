<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Settings;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'general' => ['sometimes', 'array'],
            'general.shop_name' => ['sometimes', 'string', 'max:120'],
            'general.currency' => ['sometimes', 'string', Rule::in(['EUR', 'USD', 'UAH'])],
            'checkout' => ['sometimes', 'array'],
            'checkout.guest_checkout' => ['sometimes', 'boolean'],
            'checkout.require_phone' => ['sometimes', 'boolean'],
            'checkout.send_order_confirmation' => ['sometimes', 'boolean'],
            'shipping' => ['sometimes', 'array'],
            'shipping.standard_delivery_cents' => ['sometimes', 'integer', 'min:0'],
            'shipping.express_delivery_cents' => ['sometimes', 'integer', 'min:0'],
            'shipping.pickup_delivery_cents' => ['sometimes', 'integer', 'min:0'],
            'shipping.free_standard_delivery_threshold_cents' => ['sometimes', 'integer', 'min:0'],
            'tax' => ['sometimes', 'array'],
            'tax.rate' => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'search' => ['sometimes', 'array'],
            'search.products_per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'reviews' => ['sometimes', 'array'],
            'reviews.require_moderation' => ['sometimes', 'boolean'],
            'notifications' => ['sometimes', 'array'],
            'notifications.order_confirmation' => ['sometimes', 'boolean'],
        ];
    }
}
