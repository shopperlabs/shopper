<?php

declare(strict_types=1);

namespace Shopper\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string | null $slug
 * @property string | null $code
 * @property bool $is_enabled
 * @property int | null $currency_id
 * @property array $metadata
 */
class Zone extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'is_enabled',
        'currency_id',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('zones');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function countries(): MorphToMany
    {
        return $this->morphedByMany(Country::class, 'zonable', 'zone_has_relations');
    }

    public function paymentMethods(): MorphToMany
    {
        return $this->morphedByMany(PaymentMethod::class, 'zonable', 'zone_has_relations');
    }

    public function carriers(): MorphToMany
    {
        return $this->morphedByMany(Carrier::class, 'zonable', 'zone_has_relations');
    }
}
