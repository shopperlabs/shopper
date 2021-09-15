<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\Shop\DiscountDetail;
use Illuminate\Database\Eloquent\Relations\morphMany;

trait CanHaveDiscount
{
    public function discounts(): morphMany
    {
        return $this->morphMany(DiscountDetail::class, 'discountable')->orderBy('created_at', 'desc');
    }
}
