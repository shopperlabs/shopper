<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Filament\Notifications\Notification;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

trait HandlesAuthorizationExceptions
{
    public bool $mountedHandlesAuthorizationExceptions = false;

    public function mountHandlesAuthorizationExceptions(): void
    {
        $this->mountedHandlesAuthorizationExceptions = true;
    }

    public function exception(Throwable $e, callable $stopPropagation): void
    {
        if ($e instanceof AuthorizationException && $this->mountedHandlesAuthorizationExceptions) {
            Notification::make()
                ->title(__('shopper::notifications.unauthorized.title'))
                ->body($e->getMessage() ?: __('shopper::notifications.unauthorized.body'))
                ->warning()
                ->send();

            $stopPropagation();
        }
    }
}
