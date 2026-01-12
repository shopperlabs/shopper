<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Helpers\Price as PriceHelper;

interface Price
{
    public function amountPrice(): ?PriceHelper;

    public function compareAmountPrice(): ?PriceHelper;

    public function costAmountPrice(): ?PriceHelper;

    public function currency(): BelongsTo;

    public function priceable(): MorphTo;
}
