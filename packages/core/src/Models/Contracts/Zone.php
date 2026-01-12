<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Zone
{
    public function isEnabled(): bool;

    public function currency(): BelongsTo;

    public function countries(): MorphToMany;

    public function paymentMethods(): MorphToMany;

    public function carriers(): MorphToMany;

    public function shippingOptions(): HasMany;
}
