<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface OrderTaxLine
{
    public function taxable(): MorphTo;

    public function taxRate(): BelongsTo;
}
