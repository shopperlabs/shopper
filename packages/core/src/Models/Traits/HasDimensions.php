<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasDimensions
{
    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function width(): Attribute
    {
        return Attribute::get(
            fn (): string => "{$this->width_value} {$this->width_unit->value}",
        );
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function weight(): Attribute
    {
        return Attribute::get(
            fn (): string => "{$this->weight_value} {$this->weight_unit->value}",
        );
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function height(): Attribute
    {
        return Attribute::get(
            fn (): string => "{$this->height_value} {$this->height_unit->value}",
        );
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function depth(): Attribute
    {
        return Attribute::get(
            fn (): string => "{$this->depth_value} {$this->depth_unit->value}",
        );
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function volume(): Attribute
    {
        return Attribute::get(
            fn (): string => "{$this->volume_value} {$this->volume_unit->value}",
        );
    }
}
