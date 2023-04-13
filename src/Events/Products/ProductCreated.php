<?php

declare(strict_types=1);

namespace Shopper\Framework\Events\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

final class ProductCreated
{
    use SerializesModels;

    public function __construct(public Model $product, public array $quantity)
    {
    }
}
