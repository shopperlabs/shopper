<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Relations\morphMany;
use Shopper\Framework\Models\Shop\DiscountDetail;

trait CanHaveDiscount
{
    public function discounts(): morphMany
    {
        return $this->morphMany(DiscountDetail::class, 'discountable')->orderBy('created_at', 'desc');
    }
}
