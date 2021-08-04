<?php

namespace Shopper\Framework\Events\Products;

use Illuminate\Queue\SerializesModels;

class ProductCreated
{
    use SerializesModels;

    public $product;

    public array $quantity;

    public function __construct($product, array $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
