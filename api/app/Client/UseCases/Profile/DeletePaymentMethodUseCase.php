<?php

declare(strict_types = 1);

namespace App\Client\UseCases\Profile;

use App\Models\User\User;
use App\Models\Account\PaymentMethod;

class DeletePaymentMethodUseCase
{
    public function execute(User $user, PaymentMethod $paymentMethod): void
    {
        abort_unless($paymentMethod->user_id === $user->id, 404);

        $paymentMethod->delete();
    }
}
