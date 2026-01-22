<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

final readonly class Package
{
    public function __construct(
        public float $length,
        public float $width,
        public float $height,
        public float $weight,
        public string $unit = 'metric', // 'metric' (cm, kg) or 'imperial' (in, lb)
    ) {}

    public function isMetric(): bool
    {
        return $this->unit === 'metric';
    }

    public function isImperial(): bool
    {
        return $this->unit === 'imperial';
    }

    /**
     * @return array<string, float|string>
     */
    public function toArray(): array
    {
        return [
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
            'unit' => $this->unit,
        ];
    }
}
