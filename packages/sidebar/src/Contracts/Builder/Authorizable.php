<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

interface Authorizable
{
    public function isAuthorized(): bool;

    public function setAuthorized(bool $authorized = true): self;
}
