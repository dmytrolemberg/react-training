<?php

declare(strict_types = 1);

namespace App\Admin\Controllers\Api\V1\Settings;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Admin\Services\ShopSettingsService;
use App\Admin\Requests\Api\V1\Settings\UpdateSettingsRequest;

class SettingsController extends Controller
{
    public function show(ShopSettingsService $settings): JsonResponse
    {
        return response()->json(['data' => $settings->all()]);
    }

    public function update(UpdateSettingsRequest $request, ShopSettingsService $settings): JsonResponse
    {
        return response()->json(['data' => $settings->update($request->validated())]);
    }
}
