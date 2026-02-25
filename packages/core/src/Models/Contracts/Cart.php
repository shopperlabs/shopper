<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface Cart
{
    public function lines(): HasMany;

    public function addresses(): HasMany;

    public function customer(): BelongsTo;

    public function channel(): BelongsTo;

    public function zone(): BelongsTo;

    public function isCompleted(): bool;
}
