<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Carbon\Carbon;

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
    public $brand_id;

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
    public $requiresShipping = false;

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
     * Publish date for the collection.
     *
     * @var string
     */
    public $publishedAt;

    /**
     * Formatted publishedAt date.
     *
     * @var string
     */
    public $publishedAtFormatted;

    /**
     * Type of product that's be created.
     *
     * @var string
     */
    public $type = 'deliverable';

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

    /**
     * Live updated Formatted publishedAt attribute.
     *
     * @return void
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }
}
