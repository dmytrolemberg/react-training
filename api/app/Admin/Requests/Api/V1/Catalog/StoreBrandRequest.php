<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Catalog;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', Rule::unique('brands', 'slug')],
            'name' => ['required', 'string', 'max:255', Rule::unique('brands', 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo_initial' => ['nullable', 'string', 'max:4'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
