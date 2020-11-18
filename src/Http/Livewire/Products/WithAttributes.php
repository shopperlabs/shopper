<?php

namespace Shopper\Framework\Http\Livewire\Products;

trait WithAttributes
{
    /**
     * Product name.
     *
     * @var string
     */
    public $name;

    /**
     * Product unique SKU.
     *
     * @var string
     */
    public $sku;

    /**
     * Product Barcode.
     *
     * @var string
     */
    public $barcode;

    /**
     * Brand id.
     *
     * @var string
     */
    public $brandId;

    /**
     * Product sample description.
     *
     * @var string
     */
    public $description;

    /**
     * Price Amount.
     *
     * @var string
     */
    public $price_amount;

    /**
     * Old Price Amount (to compare and add promotion).
     *
     * @var string
     */
    public $old_price_amount;

    /**
     * Product cost amount.
     *
     * @var string
     */
    public $cost_amount;

    /**
     * Define if th product can be return after ordering.
     *
     * @var bool
     */
    public $backorder = false;

    /**
     * Define if the product can be shipping.
     *
     * @var bool
     */
    public $requiredShipping = false;

    /**
     * Define if the product is visible for the customer.
     *
     * @var bool
     */
    public $isVisible = false;

    /**
     * Product Safety stock.
     *
     * @var int
     */
    public $securityStock;

    /**
     * Product dimension: weight Value.
     *
     * @var string
     */
    public $weightValue;

    /**
     * Product dimension: weight Unit.
     *
     * @var string
     */
    public $weightUnit = 'kg';

    /**
     * Product dimension: height Value.
     *
     * @var string
     */
    public $heightValue;

    /**
     * Product dimension: height Unit.
     *
     * @var string
     */
    public $heightUnit = 'cm';

    /**
     * Product dimension: width Value.
     *
     * @var string
     */
    public $widthValue;

    /**
     * Product dimension: width Unit.
     *
     * @var string
     */
    public $widthUnit = 'cm';

    /**
     * Product dimension: volume Value.
     *
     * @var string
     */
    public $volumeValue;

    /**
     * Product dimension: volume Unit.
     *
     * @var string
     */
    public $volumeUnit = 'l';
}
