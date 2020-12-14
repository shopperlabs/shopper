<?php

namespace Shopper\Framework\Events\Products;

use Illuminate\Queue\SerializesModels;

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
     * @var array
     */
    public $quantity;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $product
     * @param  array  $quantity
     */
    public function __construct($product, array $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
