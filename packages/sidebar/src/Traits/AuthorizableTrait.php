<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

trait AuthorizableTrait
{
    protected bool $authorized = true;

    public function isAuthorized(): bool
    {
        return $this->authorized;
    }

    public function setAuthorized(bool $authorized = true): self
    {
        $this->authorized = $authorized;

        return $this;
    }
}
