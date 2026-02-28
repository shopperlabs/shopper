<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Contracts\LoginResponse;
use Shopper\Contracts\TwoFactorAuthenticationProvider;
use Shopper\Facades\Shopper;
use Shopper\Traits\TwoFactorAuthenticatable;

/**
 * @property-read Schema $form
 * @property-read Schema $twoFactorForm
 */
#[Layout('shopper::components.layouts.base')]
final class Login extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var array<string, mixed>|null */
    public ?array $twoFactorData = [];

    #[Locked]
    public ?string $challengedUserId = null;

    public bool $useRecoveryCode = false;

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
                TextInput::make('password')
                    ->label(__('shopper::forms.label.password'))
                    ->password()
                    ->revealable()
                    ->inlineSuffix()
                    ->hintAction(
                        Action::make('resetPassword')
                            ->label(__('shopper::pages/auth.login.forgot_password'))
                            ->url(route('shopper.password.request'))
                            ->extraAttributes(['wire:navigate' => true])
                            ->visible(fn (): bool => config('shopper.auth.password_reset', true))
                    )
                    ->required()
                    ->autocomplete('current-password'),
                Checkbox::make('remember')
                    ->label(__('shopper::forms.label.remember')),
            ])
            ->statePath('data');
    }

    public function twoFactorForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('shopper::forms.label.code'))
                    ->autocomplete('one-time-code')
                    ->autofocus()
                    ->required()
                    ->visible(fn (): bool => ! $this->useRecoveryCode),
                TextInput::make('recovery_code')
                    ->label(__('shopper::forms.label.recovery_code'))
                    ->autocomplete('one-time-code')
                    ->autofocus()
                    ->required()
                    ->visible(fn (): bool => $this->useRecoveryCode),
            ])
            ->statePath('twoFactorData');
    }

    public function authenticate(): mixed
    {
        $data = $this->form->getState();

        [$throwable] = useTryCatch(fn () => $this->rateLimit(5));

        if ($throwable instanceof TooManyRequestsException) {
            throw ValidationException::withMessages([
                'data.email' => __('shopper::pages/auth.login.throttled', [
                    'seconds' => $throwable->secondsUntilAvailable,
                    'minutes' => ceil($throwable->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $user = $this->validateCredentials($data);

        if ($this->challengedUserId && decrypt($this->challengedUserId) === (string) $user->getKey()) {
            return $this->verifyTwoFactorCode($user);
        }

        if ($this->shouldChallenge($user)) {
            $this->challengedUserId = encrypt((string) $user->getKey());

            return null;
        }

        return $this->loginUser($user, $data['remember'] ?? false);
    }

    public function resetChallenge(): void
    {
        $this->challengedUserId = null;
        $this->twoFactorForm->fill();
        $this->useRecoveryCode = false;
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.auth.login')
            ->title(__('shopper::pages/auth.login.title'));
    }

    private function validateCredentials(array $data): mixed
    {
        $model = Shopper::auth()->getProvider()->getModel(); // @phpstan-ignore-line

        $user = $model::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'data.email' => __('shopper::pages/auth.login.failed'),
            ]);
        }

        return $user;
    }

    private function shouldChallenge(mixed $user): bool
    {
        return config('shopper.auth.2fa_enabled')
            && $user->two_factor_secret
            && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user));
    }

    private function verifyTwoFactorCode(mixed $user): mixed
    {
        $data = $this->twoFactorForm->getState();

        if ($this->useRecoveryCode) {
            $validCode = collect($user->recoveryCodes())
                ->first(fn ($code): bool => hash_equals($data['recovery_code'], $code));

            if (! $validCode) {
                throw ValidationException::withMessages([
                    'twoFactorData.recovery_code' => __('The provided two factor recovery code was invalid.'),
                ]);
            }

            $user->replaceRecoveryCode($validCode);
        } else {
            $isValid = app(TwoFactorAuthenticationProvider::class)->verify(
                decrypt($user->two_factor_secret),
                $data['code'],
            );

            if (! $isValid) {
                throw ValidationException::withMessages([
                    'twoFactorData.code' => __('The provided two factor authentication code was invalid.'),
                ]);
            }
        }

        return $this->loginUser($user, $this->data['remember'] ?? false);
    }

    private function loginUser(mixed $user, bool $remember = false): mixed
    {
        Shopper::auth()->login($user, $remember);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
