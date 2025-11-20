<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Discount;

/**
 * @property-read \Illuminate\Support\Collection<int, Discount> $discounts
 */
trait HasDiscounts
{
    /**
     * @return MorphToMany<Discount, $this>
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(
            Discount::class,
            'discountable',
            shopper_table('discountables'),
            'discountable_id',
            'discount_id'
        );
    }
}
