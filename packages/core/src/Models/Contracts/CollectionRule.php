<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface CollectionRule
{
    public function getFormattedRule(): string;

    public function getFormattedOperator(): string;

    public function getFormattedValue(): string;

    public function collection(): BelongsTo;
}
