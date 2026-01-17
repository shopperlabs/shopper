<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Illuminate\Validation\ValidationException;
use Shopper\Actions\Auth\ConfirmPassword;
use Shopper\Facades\Shopper;

trait ConfirmsPasswords
{
    public string $pendingConfirmableAction = '';

    public function confirmPasswordAction(): Action
    {
        return Action::make('confirmPassword')
            ->modalWidth(Width::Large)
            ->modalHeading(__('shopper::forms.label.confirm_password'))
            ->modalDescription(__('shopper::pages/settings/global.confirm_password_content'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.confirm'))
            ->modalCancelActionLabel(__('shopper::forms.actions.nevermind'))
            ->schema([
                TextInput::make('password')
                    ->label(__('shopper::forms.label.password'))
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->action(function (array $data): void {
                if (! $this->confirmPassword($data['password'])) {
                    throw ValidationException::withMessages([
                        'mountedActions.0.data.password' => __('shopper::notifications.auth.password'),
                    ]);
                }

                $this->dispatch($this->pendingConfirmableAction);
            });
    }

    public function startConfirmingPassword(string $action): void
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            $this->dispatch($action);

            return;
        }

        $this->pendingConfirmableAction = $action;

        $this->mountAction('confirmPassword');
    }

    public function confirmPassword(string $password): bool
    {
        $confirmed = app(ConfirmPassword::class)(
            Shopper::auth(),
            Shopper::auth()->user(),
            $password
        );

        if ($confirmed) {
            session(['auth.password_confirmed_at' => time()]);
        }

        return $confirmed;
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
