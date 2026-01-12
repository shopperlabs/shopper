<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface AttributeValue
{
    public function attribute(): BelongsTo;

    public function attributeProduct(): BelongsTo;

    public function variants(): BelongsToMany;
}
