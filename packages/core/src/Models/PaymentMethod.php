<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Traits\HasSlug;
use Shopper\Core\Traits\HasZones;

/**
 * @property-read int $id
 * @property string $title
 * @property string $slug
 * @property string|null $logo
 * @property string|null $description
 * @property string|null $link_url
 * @property string|null $instructions
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use HasSlug;
    use HasZones;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function getTable(): string
    {
        return shopper_table('payment_methods');
    }

    public function LogoUrl(): ?Attribute
    {
        return Attribute::make(
            get: fn () => $this->logo ? shopper_asset($this->logo) : null,
        );
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }
}
