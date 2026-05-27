<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Profile;

use App\Models\User\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ];
    }

    /**
     * @return array{name: string, email: string}
     */
    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'name' => is_string($validated['name'] ?? null) ? $validated['name'] : '',
            'email' => is_string($validated['email'] ?? null) ? $validated['email'] : '',
        ];
    }
}
