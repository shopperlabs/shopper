<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ProductVariant
{
    public function product(): BelongsTo;

    public function prices(): MorphMany;

    public function values(): BelongsToMany;
}
