<?php

declare(strict_types=1);

namespace Shopper\Core\Pricing;

use InvalidArgumentException;

final readonly class QuantityRule
{
    public function __construct(
        public int $minimum = 1,
        public ?int $maximum = null,
        public int $increment = 1,
    ) {
        if ($minimum < 1 || $increment < 1 || $minimum % $increment !== 0 || ($maximum !== null && $maximum < $minimum)) {
            throw new InvalidArgumentException('A quantity rule needs a minimum and an increment of at least 1, a minimum that is a multiple of the increment, and a maximum not below the minimum.');
        }
    }

    public function allows(int $quantity): bool
    {
        return $quantity >= $this->minimum
            && ($this->maximum === null || $quantity <= $this->maximum)
            && $quantity % $this->increment === 0;
    }

    /**
     * @return array{minimum: int, maximum: int|null, increment: int}
     */
    public function toArray(): array
    {
        return [
            'minimum' => $this->minimum,
            'maximum' => $this->maximum,
            'increment' => $this->increment,
        ];
    }
}
