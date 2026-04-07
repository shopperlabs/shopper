<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Notifications\AdminResetPassword;

/**
 * @property-read Schema $form
 */
#[Layout('shopper::components.layouts.base')]
final class ForgotPassword extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    use WithRateLimiting;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->email()
                    ->required()
                    ->autocomplete('email')
                    ->autofocus(),
            ])
            ->statePath('data');
    }

    public function sendResetPasswordLink(): void
    {
        [$throwable] = useTryCatch(fn () => $this->rateLimit(3, 300));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'data.email' => __('shopper::pages/auth.login.throttled', [
                    'seconds' => $throwable->secondsUntilAvailable,
                    'minutes' => ceil($throwable->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();

        $response = $this->broker()->sendResetLink(
            ['email' => $data['email']],
            fn ($user, string $token): string => tap(Password::RESET_LINK_SENT, function () use ($user, $token): void {
                $user->notify(new AdminResetPassword($token));
            })
        );

        if ($response === Password::RESET_LINK_SENT) {
            session()->flash('success', trans($response));
            $this->reset('data');

            return;
        }

        $this->addError('data.email', trans($response));
    }

    public function broker(): PasswordBroker
    {
        return Password::broker();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.forgot-password')
            ->title(__('shopper::pages/auth.email.title'));
    }
}
