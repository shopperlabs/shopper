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
use Shopper\Core\Models\Contracts\Price as PriceContract;

/**
 * @property-read int $id
 * @property-read ?int $amount
 * @property-read ?int $compare_amount
 * @property-read ?int $cost_amount
 * @property-read string $currency_code
 * @property-read int $currency_id
 * @property-read int $priceable_id
 * @property-read string $priceable_type
 * @property-read Currency $currency
 */
class Price extends Model implements PriceContract
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('prices');
    }

    public function currencyCode(): Attribute
    {
        return Attribute::get(fn (): string => $this->loadMissing('currency')->currency->code);
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

    /**
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): PriceFactory
    {
        return PriceFactory::new();
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value): float|int|null => $value ? $value / 100 : null,
            set: fn (?int $value): ?int => $value ? $value * 100 : null,
        );
    }

    protected function compareAmount(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value): float|int|null => $value ? $value / 100 : null,
            set: fn (?int $value): ?int => $value ? $value * 100 : null,
        );
    }

    protected function costAmount(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value): float|int|null => $value ? $value / 100 : null,
            set: fn (?int $value): ?int => $value ? $value * 100 : null,
        );
    }
}
