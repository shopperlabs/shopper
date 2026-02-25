<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface CartLine
{
    public function cart(): BelongsTo;

    public function purchasable(): MorphTo;

    public function adjustments(): HasMany;

    public function taxLines(): HasMany;
}
