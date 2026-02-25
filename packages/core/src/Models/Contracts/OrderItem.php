<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface OrderItem
{
    public function product(): MorphTo;

    public function order(): BelongsTo;

    public function taxLines(): MorphMany;
}
