<?php

declare(strict_types=1);

namespace Shopper\Framework\Events\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

final class ProductRemoved
{
    use SerializesModels;

    public function __construct(public Model $product)
    {
    }
}
