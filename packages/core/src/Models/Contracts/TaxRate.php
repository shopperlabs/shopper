<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface TaxRate
{
    public function taxZone(): BelongsTo;

    public function rules(): HasMany;
}
