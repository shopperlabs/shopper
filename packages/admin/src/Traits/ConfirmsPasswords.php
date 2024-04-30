<?php

declare(strict_types=1);

namespace Shopper\Traits;

trait ConfirmsPasswords
{
    public function startConfirmingPassword(string $action): void
    {
        $this->resetErrorBag();

        if (! $this->passwordIsConfirmed()) {
            $this->dispatch('openModal', 'shopper-modals.confirm-password', [
                'action' => $action,
            ]);

            return;
        }

        $this->dispatch($action);
    }

    protected function ensurePasswordIsConfirmed(?int $maximumSecondsSinceConfirmation = null): mixed
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return $this->passwordIsConfirmed($maximumSecondsSinceConfirmation) ? null : abort(403);
    }

    protected function passwordIsConfirmed(?int $maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?? config('auth.password_timeout', 900);

        return (time() - session('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}
