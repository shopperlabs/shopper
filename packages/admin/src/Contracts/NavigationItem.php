<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use BackedEnum;

interface NavigationItem
{
    public function name(): string;

    public function icon(): string|BackedEnum;

    public function order(): int;

    public function group(): ?string;

    public function permission(): ?string;
}
