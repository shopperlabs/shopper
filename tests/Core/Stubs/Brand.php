<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Models\Brand as BaseBrand;

final class Brand extends BaseBrand
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
