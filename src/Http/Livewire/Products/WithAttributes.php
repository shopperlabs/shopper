<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products;

use Carbon\Carbon;

trait WithAttributes
{
    public ?string $name = null;

    public ?string $sku = null;

    public ?string $barcode = null;

    public ?int $brand_id = null;

    public ?string $description = null;

    public ?int $price_amount = null;

    public ?int $old_price_amount = null;

    public ?int $cost_amount = null;

    public bool $backorder = false;

    public bool $requiresShipping = false;

    public bool $isVisible = true;

    public int $securityStock = 0;

    public ?string $publishedAt = null;

    public ?string $publishedAtFormatted = null;

    public string $type = 'deliverable';

    public ?float $weightValue = null;

    public string $weightUnit = 'kg';

    public ?float $heightValue = null;

    public string $heightUnit = 'cm';

    public ?float $widthValue = null;

    public string $widthUnit = 'cm';

    public ?float $volumeValue;

    public string $volumeUnit = 'l';

    public function updatedPublishedAt($value): void
    {
        if ($value) {
            $this->publishedAtFormatted = Carbon::createFromFormat('Y-m-d H:i', $value)->toRfc7231String();
        }
    }
}
