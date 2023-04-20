<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Framework\Models\Shop\DiscountDetail;

trait CanHaveDiscount
{
    public function discounts(): MorphToMany
    {
        return $this->morphedByMany(DiscountDetail::class, 'discountable')->orderBy('created_at', 'desc');
    }
}
