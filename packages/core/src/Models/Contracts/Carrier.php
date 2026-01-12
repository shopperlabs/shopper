<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Carrier
{
    public function options(): HasMany;
}
