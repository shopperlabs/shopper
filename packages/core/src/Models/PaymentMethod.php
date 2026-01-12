<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Database\Factories\PaymentMethodFactory;
use Shopper\Core\Models\Contracts\PaymentMethod as PaymentMethodContract;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Models\Traits\HasZones;

/**
 * @property-read int $id
 * @property string $title
 * @property string $slug
 * @property ?string $logo
 * @property ?string $logo_url
 * @property ?string $description
 * @property ?string $link_url
 * @property ?string $instructions
 */
class PaymentMethod extends Model implements PaymentMethodContract
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory;

    use HasSlug;
    use HasZones;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('payment_methods');
    }

    /**
     * @param  Builder<PaymentMethod>  $query
     * @return Builder<PaymentMethod>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    protected static function newFactory(): PaymentMethodFactory
    {
        return PaymentMethodFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    protected function LogoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->logo ? shopper_asset($this->logo) : null,
        );
    }
}
