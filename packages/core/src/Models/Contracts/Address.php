<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface Address
{
    public function user(): BelongsTo;

    public function country(): BelongsTo;
}
