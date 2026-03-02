<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

interface Category
{
    public function getLabelOptionName(): string;

    public function descendantCategories(): HasManyOfDescendants;

    public function products(): MorphToMany;
}
