<?php

declare(strict_types=1);

namespace Shopper\Api\Support;

use Illuminate\Support\Collection;
use Shopper\Cart\Models\CartLine;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Shipping\DataTransferObjects\Package;

/**
 * Collapses cart lines into a single package: summed weight, envelope
 * dimensions, normalized to the configured shipping units. Multi-parcel
 * packing is a carrier concern; rating only needs a faithful input.
 */
final class CartPackagesBuilder
{
    private const float KG_TO_LBS = 2.20462262;

    private const float CM_TO_IN = 0.39370079;

    /**
     * @param  Collection<int, CartLine>  $lines
     * @return array<int, Package>
     */
    public function build(Collection $lines): array
    {
        $weight = 0.0;
        $length = 0.0;
        $width = 0.0;
        $height = 0.0;

        foreach ($lines as $line) {
            $purchasable = $line->purchasable;

            if (! $purchasable instanceof Product && ! $purchasable instanceof ProductVariant) {
                continue;
            }

            $weight += $this->toKilograms($purchasable->weight_value, $purchasable->weight_unit) * $line->quantity;
            $length = max($length, $this->toCentimeters($purchasable->depth_value, $purchasable->depth_unit));
            $width = max($width, $this->toCentimeters($purchasable->width_value, $purchasable->width_unit));
            $height = max($height, $this->toCentimeters($purchasable->height_value, $purchasable->height_unit));
        }

        if (config('shopper.shipping.units', 'metric') === 'imperial') {
            return [new Package(
                length: round($length * self::CM_TO_IN, 2),
                width: round($width * self::CM_TO_IN, 2),
                height: round($height * self::CM_TO_IN, 2),
                weight: round($weight * self::KG_TO_LBS, 3),
                unit: 'imperial',
            )];
        }

        return [new Package(
            length: round($length, 2),
            width: round($width, 2),
            height: round($height, 2),
            weight: round($weight, 3),
            unit: 'metric',
        )];
    }

    private function toKilograms(mixed $value, ?Weight $unit): float
    {
        $factor = match ($unit) {
            Weight::G => 0.001,
            Weight::LBS => 0.45359237,
            default => 1.0,
        };

        return (float) ($value ?? 0) * $factor;
    }

    private function toCentimeters(mixed $value, ?Length $unit): float
    {
        $factor = match ($unit) {
            Length::M => 100.0,
            Length::MM => 0.1,
            Length::FT => 30.48,
            Length::IN => 2.54,
            default => 1.0,
        };

        return (float) ($value ?? 0) * $factor;
    }
}
