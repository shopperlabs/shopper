<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use Shopper\ShopperPanel;

interface ShopperAddon
{
    public function getId(): string;

    public function getName(): string;

    public function register(ShopperPanel $panel): void;

    public function boot(ShopperPanel $panel): void;

    public function isEnabled(): bool;
}
