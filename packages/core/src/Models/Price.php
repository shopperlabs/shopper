<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\PriceFactory;
use Shopper\Core\Helpers\Price as PriceHelper;

/**
 * @property-read int $id
 * @property int | null $amount
 * @property int | null $compare_amount
 * @property int | null $cost_amount
 * @property string $currency_code
 * @property int $currency_id
 * @property int $priceable_id
 * @property string $priceable_type
 * @property Currency $currency
 */
class Price extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('prices');
    }

    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }

    public function currencyCode(): Attribute
    {
        return Attribute::get(fn () => $this->loadMissing('currency')->currency->code);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function compareAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function costAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function amountPrice(): ?PriceHelper
    {
        if (! $this->amount) {
            return null;
        }

        return PriceHelper::from($this->amount);
    }

    public function compareAmountPrice(): ?PriceHelper
    {
        if (! $this->compare_amount) {
            return null;
        }

        return PriceHelper::from($this->compare_amount);
    }

    public function costAmountPrice(): ?PriceHelper
    {
        if (! $this->cost_amount) {
            return null;
        }

        return PriceHelper::from($this->cost_amount);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
