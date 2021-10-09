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
     */
    public bool $backorder = false;

    /**
     * Define if the product can be shipping.
     */
    public bool $requiresShipping = false;

    /**
     * Define if the product is visible for the customer.
     */
    public bool $isVisible = true;

    /**
     * Product Safety stock.
     */
    public int|string $securityStock = 0;

    public ?string $publishedAt = null;

    /**
     * Formatted publishedAt date.
     */
    public ?string $publishedAtFormatted = null;

    /**
     * Type of product that's be created.
     */
    public ?string $type = 'deliverable';

    /**
     * Product dimension: weight Value.
     */
    public ?float $weightValue = null;

    /**
     * Product dimension: weight Unit.
     */
    public string $weightUnit = 'kg';

    /**
     * Product dimension: height Value.
     */
    public ?float $heightValue = null;

    /**
     * Product dimension: height Unit.
     */
    public string $heightUnit = 'cm';

    /**
     * Product dimension: width Value.
     */
    public ?float $widthValue = null;

    /**
     * Product dimension: width Unit.
     */
    public string $widthUnit = 'cm';

    /**
     * Product dimension: volume Value.
     */
    public ?float $volumeValue;

    /**
     * Product dimension: volume Unit.
     */
    public string $volumeUnit = 'l';

    /**
     * Live updated Formatted publishedAt attribute.
     */
    public function updatedPublishedAt()
    {
        $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d', $this->publishedAt)->toRfc7231String();
    }
}
