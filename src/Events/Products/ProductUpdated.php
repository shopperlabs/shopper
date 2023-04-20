<?php

declare(strict_types=1);

namespace Shopper\Framework\Events\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

final class ProductUpdated
{
    use SerializesModels;

    public function __construct(public Model $product)
    {
    }
}
