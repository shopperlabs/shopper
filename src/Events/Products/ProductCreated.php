<?php

namespace Shopper\Framework\Events;

use Illuminate\Queue\SerializesModels;
use Shopper\Framework\Models\Shop\Product\Product;

class ProductCreated
{
    use SerializesModels;

    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * Quantity to set to the product.
     *
     * @var int
     */
    public int $quantity;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     * @param  int  $quantity
     */
    public function __construct($product, int $quantity = 0)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
