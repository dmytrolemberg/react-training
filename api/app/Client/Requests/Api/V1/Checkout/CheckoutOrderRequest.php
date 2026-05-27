<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Checkout;

use Illuminate\Validation\Rule;
use App\Models\Commerce\DeliveryMethod;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutOrderRequest extends FormRequest
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
            'contact' => ['required', 'array'],
            'contact.email' => ['required', 'email', 'max:255'],
            'contact.phone' => ['nullable', 'string', 'max:40'],
            'shipping_address' => ['required', 'array'],
            'shipping_address.first_name' => ['required', 'string', 'max:120'],
            'shipping_address.last_name' => ['required', 'string', 'max:120'],
            'shipping_address.country' => ['nullable', 'string', 'max:120'],
            'shipping_address.city' => ['required', 'string', 'max:120'],
            'shipping_address.postal_code' => ['required', 'string', 'max:32'],
            'shipping_address.address_line' => ['required', 'string', 'max:255'],
            'delivery_method' => ['required', Rule::enum(DeliveryMethod::class)],
            'payment_method_type' => ['required', Rule::in(['mock_card', 'cash_on_delivery'])],
            'payment_method_id' => ['nullable', 'integer', Rule::exists('payment_methods', 'id')],
            'card_number' => ['prohibited'],
            'card_cvc' => ['prohibited'],
            'card_expiry' => ['prohibited'],
        ];
    }
}
