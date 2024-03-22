<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Queue\SerializesModels;

final class Deleted
{
    use SerializesModels;

    public function __construct(public $product)
    {
    }
}
