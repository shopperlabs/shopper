<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface InventoryHistory
{
    public function inventory(): BelongsTo;

    public function user(): BelongsTo;

    public function stockable(): MorphTo;

    public function reference(): MorphTo;
}
