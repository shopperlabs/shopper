<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * @property-read Schema $form
 */
final class SessionExpired extends Component implements HasSchemas
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
                TextInput::make('password')
                    ->hiddenLabel()
                    ->password()
                    ->revealable()
                    ->inlineSuffix()
                    ->required()
                    ->autocomplete('current-password')
                    ->autofocus(),
            ])
            ->statePath('data');
    }

    public function attempt(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'data.password' => __('shopper::pages/auth.login.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        $data = $this->form->getState();

        $user = shopper()->auth()->user();

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'data.password' => __('shopper::pages/auth.login.failed'),
            ]);
        }

        session()->regenerate();

        $this->form->fill();
        $this->dispatch('close-modal', id: 'session-expired');
        $this->redirect(request()->header('Referer') ?? route('shopper.dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.session-expired');
    }
}
