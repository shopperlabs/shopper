<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Auth\Access\AuthorizationException;

trait HandlesAuthorizationExceptions
{
    public function exception(Exception $e, callable $stopPropagation): void
    {
        if ($e instanceof AuthorizationException) {
            Notification::make()
                ->title(__('shopper::notifications.unauthorized.title'))
                ->body($e->getMessage() ?: __('shopper::notifications.unauthorized.body'))
                ->warning()
                ->send();

            $stopPropagation();
        }
    }
}
