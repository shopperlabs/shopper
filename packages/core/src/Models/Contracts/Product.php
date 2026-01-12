<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Product
{
    public function canUseShipping(): bool;

    public function canUseAttributes(): bool;

    public function canUseVariants(): bool;

    public function isExternal(): bool;

    public function isVariant(): bool;

    public function isVirtual(): bool;

    public function isStandard(): bool;

    public function isPublished(): bool;

    public function variants(): HasMany;

    public function channels(): MorphToMany;

    public function relatedProducts(): MorphToMany;

    public function categories(): MorphToMany;

    public function collections(): MorphToMany;

    public function brand(): BelongsTo;

    public function options(): BelongsToMany;
}
