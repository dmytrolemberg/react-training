<?php

declare(strict_types = 1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop\ShopSetting;
use App\Admin\Services\ShopSettingsService;

class ShopSettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ShopSettingsService::DEFAULTS as $key => $value) {
            ShopSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
