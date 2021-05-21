<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Carbon\Carbon;

trait WithAttributes
{
    public ?string $name = null;

    public ?string $sku = null;

    public ?string $barcode = null;

    public ?int $brand_id = null;

    public ?string $description = null;

    public ?string $price_amount = null;

    public ?string $old_price_amount = null;

    public ?string $cost_amount = null;

    /**
     * Define if the product can be return after ordering.
     *
     * @var bool
     */
    public bool $backorder = false;

    /**
     * Define if the product can be shipping.
     *
     * @var bool
     */
    public bool $requiresShipping = false;

    /**
     * Define if the product is visible for the customer.
     *
     * @var bool
     */
    public bool $isVisible = true;

    /**
     * Product Safety stock.
     *
     * @var int
     */
    public int $securityStock = 0;

    public ?string $publishedAt = null;

    /**
     * Formatted publishedAt date.
     *
     * @var string|null
     */
    public ?string $publishedAtFormatted = null;

    /**
     * Type of product that's be created.
     *
     * @var string
     */
    public string $type = 'deliverable';

    /**
     * Product dimension: weight Value.
     *
     * @var float|null
     */
    public ?float $weightValue = null;

    /**
     * Product dimension: weight Unit.
     *
     * @var string
     */
    public string $weightUnit = 'kg';

    /**
     * Product dimension: height Value.
     *
     * @var float|null
     */
    public ?float $heightValue = null;

    /**
     * Product dimension: height Unit.
     *
     * @var string
     */
    public string $heightUnit = 'cm';

    /**
     * Product dimension: width Value.
     *
     * @var float|null
     */
    public ?float $widthValue = null;

    /**
     * Product dimension: width Unit.
     *
     * @var string
     */
    public string $widthUnit = 'cm';

    /**
     * Product dimension: volume Value.
     *
     * @var float|null
     */
    public ?float $volumeValue;

    /**
     * Product dimension: volume Unit.
     *
     * @var string
     */
    public string $volumeUnit = 'l';

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
