<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface CarrierOption
{
    public function carrier(): BelongsTo;

    public function zone(): BelongsTo;
}
