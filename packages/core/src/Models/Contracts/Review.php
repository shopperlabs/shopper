<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface Review
{
    public function reviewrateable(): MorphTo;

    public function author(): MorphTo;
}
