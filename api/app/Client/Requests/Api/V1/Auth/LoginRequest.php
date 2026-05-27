<?php

declare(strict_types = 1);

namespace App\Client\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{email: list<string>, password: list<string>}
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function email(): string
    {
        return (string) $this->string('email');
    }

    public function password(): string
    {
        return (string) $this->string('password');
    }
}
