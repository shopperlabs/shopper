<?php

declare(strict_types=1);

namespace Shopper\Core\Channel\Contracts;

interface ChannelDriver
{
    public function code(): string;

    public function name(): string;

    public function logo(): ?string;

    public function isConfigured(): bool;
}
