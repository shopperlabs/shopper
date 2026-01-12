<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Discount
{
    public function hasReachedLimit(): bool;

    public function items(): HasMany;

    public function zone(): BelongsTo;
}
