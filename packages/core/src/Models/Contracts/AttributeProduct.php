<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface AttributeProduct
{
    public function attribute(): BelongsTo;

    public function product(): BelongsTo;

    public function value(): BelongsTo;
}
