<?php

declare(strict_types = 1);

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\Shop\ShopSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $key
 * @property mixed $value
 */
#[Fillable(['key', 'value'])]
class ShopSetting extends Model
{
    /** @use HasFactory<ShopSettingFactory> */
    use HasFactory;

    protected static function newFactory(): ShopSettingFactory
    {
        return ShopSettingFactory::new();
    }

    /**
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }
}
