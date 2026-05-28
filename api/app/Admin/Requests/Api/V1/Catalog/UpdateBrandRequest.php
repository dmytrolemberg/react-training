<?php

declare(strict_types = 1);

namespace App\Admin\Requests\Api\V1\Catalog;

use App\Models\Catalog\Brand;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
        $brand = $this->route('brand');
        $brandId = $brand instanceof Brand ? $brand->id : null;

        return [
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brandId)],
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('brands', 'name')->ignore($brandId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo_initial' => ['nullable', 'string', 'max:4'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
