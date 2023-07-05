<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

interface Badge
{
    public function getValue(): string;

    public function setValue(string $value): self;

    public function getClass(): string;

    public function setClass(string $class): self;
}
