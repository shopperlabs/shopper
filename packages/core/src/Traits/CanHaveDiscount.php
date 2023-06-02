<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\DiscountDetail;

trait CanHaveDiscount
{
    public function discounts(): MorphToMany
    {
        return $this->morphedByMany(DiscountDetail::class, 'discountable')
            ->orderBy('created_at', 'desc');
    }
}
