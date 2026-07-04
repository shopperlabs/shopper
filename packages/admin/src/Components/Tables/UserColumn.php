<?php

declare(strict_types=1);

namespace Shopper\Components\Tables;

use Closure;
use Filament\Tables\Columns\Column;

class UserColumn extends Column
{
    protected string $view = 'shopper::filament.tables.user-column';

    protected ?Closure $user = null;

    protected bool|Closure $showsCurrentUserBadge = false;

    public function user(Closure $callback): static
    {
        $this->user = $callback;

        return $this;
    }

    public function getUser(): mixed
    {
        if (! $this->user) {
            return $this->getRecord();
        }

        return $this->evaluate($this->user);
    }

    public function currentUserBadge(bool|Closure $condition = true): static
    {
        $this->showsCurrentUserBadge = $condition;

        return $this;
    }

    public function showsCurrentUserBadge(): bool
    {
        return (bool) $this->evaluate($this->showsCurrentUserBadge);
    }
}
