<?php

declare(strict_types = 1);

namespace App\Client\Services\Checkout;

use App\Models\Account\PaymentMethod;
use App\Models\Commerce\PaymentStatus;

class FakePaymentService
{
    /**
     * @return array{status: PaymentStatus, transaction_id: string|null, label: string, type: string}
     */
    public function charge(string $paymentMethodType, ?PaymentMethod $paymentMethod): array
    {
        if ($paymentMethodType === 'cash_on_delivery') {
            return [
                'status' => PaymentStatus::Pending,
                'transaction_id' => null,
                'label' => 'Pay on delivery',
                'type' => 'cash_on_delivery',
            ];
        }

        return [
            'status' => PaymentStatus::Paid,
            'transaction_id' => 'TX-' . now()->format('His') . '-' . random_int(1000, 9999),
            'label' => $paymentMethod instanceof PaymentMethod ? $paymentMethod->label : 'Mock card',
            'type' => 'mock_card',
        ];
    }
}
