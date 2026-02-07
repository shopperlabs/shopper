<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
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
 * @property-read string $title
 * @property-read string $slug
 * @property-read ?string $logo
 * @property-read ?string $logo_url
 * @property-read bool $is_enabled
 * @property-read ?string $description
 * @property-read ?string $link_url
 * @property-read ?string $instructions
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
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
