<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\Shop\DiscountDetail;

trait CanHaveDiscount
{
    /**
     * Relation with Discount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function discounts()
    {
        return $this->morphMany(DiscountDetail::class, 'discountable')->orderBy('created_at', 'desc');
    }
}
