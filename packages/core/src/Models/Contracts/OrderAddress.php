<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface OrderAddress
{
    public function orders(): HasMany;

    public function customer(): BelongsTo;
}
