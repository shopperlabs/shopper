<?php

namespace Shopper\Framework\Events;

use Illuminate\Queue\SerializesModels;
use Shopper\Framework\Models\Shop\Product\Product;

class ProductCreated
{
    use SerializesModels;

    /**
     * @var Product
     */
    public Product $product;

    /**
     * @var int
     */
    public int $quantity;

    /**
     * Create a new event instance.
     *
     * @param  Product  $product
     * @param  int  $quantity
     */
    public function __construct(Product $product, int $quantity = 0)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
