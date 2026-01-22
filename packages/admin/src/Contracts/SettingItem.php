<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use BackedEnum;

interface SettingItem
{
    public function name(): string;

    public function description(): string;

    public function icon(): string|BackedEnum;

    public function url(): ?string;

    public function permission(): ?string;

    public function order(): int;
}
