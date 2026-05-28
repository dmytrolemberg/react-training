<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Commerce;

use Illuminate\Validation\Rule;
use App\Models\Commerce\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentStatusRequest extends FormRequest
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
            'payment_status' => ['required', Rule::enum(PaymentStatus::class)],
        ];
    }
}
