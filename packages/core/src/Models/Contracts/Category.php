<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection as SupportCollection;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

interface Category
{
    public function getLabelOptionName(): string;

    public function descendantCategories(): HasManyOfDescendants;

    /**
     * @return SupportCollection<int, int>
     */
    public function enabledSubtreeIds(): SupportCollection;

    public function products(): MorphToMany;
}
