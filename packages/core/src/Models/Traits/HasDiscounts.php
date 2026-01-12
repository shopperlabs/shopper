<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Discount;

/**
 * @property-read Collection<int, \Shopper\Core\Models\Contracts\Discount> $discounts
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
