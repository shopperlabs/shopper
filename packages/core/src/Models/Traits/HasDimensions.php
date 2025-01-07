<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasDimensions
{
    protected function width(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->width_value} {$this->width_unit->value}",
        );
    }

    protected function weight(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->weight_value} {$this->weight_unit->value}",
        );
    }

    protected function height(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->height_value} {$this->height_unit->value}",
        );
    }

    protected function depth(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->depth_value} {$this->depth_unit->value}",
        );
    }

    protected function volume(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->volume_value} {$this->volume_unit->value}",
        );
    }
}
