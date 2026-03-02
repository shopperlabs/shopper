<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

interface HasColor
{
    /**
     * @return string | array<string> | null
     */
    public function getColor(): string|array|null;
}
