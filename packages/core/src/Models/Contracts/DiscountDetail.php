<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface DiscountDetail
{
    public function discount(): BelongsTo;

    public function discountable(): MorphTo;
}
