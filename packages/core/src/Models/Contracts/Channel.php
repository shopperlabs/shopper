<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Channel
{
    public function products(): MorphToMany;
}
