<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Collection
{
    public function isAutomatic(): bool;

    public function isManual(): bool;

    public function firstRule(): ?string;

    public function products(): MorphToMany;

    public function rules(): HasMany;
}
