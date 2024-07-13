<?php

declare(strict_types=1);

namespace Shopper\Contracts;

interface SlideOverForm
{
    public function getAction(): ?string;

    public function getTitle(): ?string;

    public function getDescription(): ?string;
}
