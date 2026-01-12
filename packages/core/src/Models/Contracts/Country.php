<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Country
{
    public function countryFlag(): string;

    public function zones(): MorphToMany;
}
