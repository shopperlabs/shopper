<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface Currency
{
    public function zone(): HasOne;
}
