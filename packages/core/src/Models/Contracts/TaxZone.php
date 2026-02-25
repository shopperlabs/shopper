<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface TaxZone
{
    public function country(): BelongsTo;

    public function parent(): BelongsTo;

    public function children(): HasMany;

    public function rates(): HasMany;

    public function provider(): BelongsTo;
}
