<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

interface Category
{
    /**
     * Use to display a custom label into filament relationship select form component
     */
    public function getLabelOptionName(): string;

    public function descendantCategories(): HasManyOfDescendants;

    public function products(): MorphToMany;
}
