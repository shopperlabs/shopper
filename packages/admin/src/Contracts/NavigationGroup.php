<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface NavigationGroup
{
    public function order(): int;

    public function getLabel(): string;
}
