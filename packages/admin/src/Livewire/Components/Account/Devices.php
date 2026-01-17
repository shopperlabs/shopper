<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Stevebauman\Location\Facades\Location;

class Devices extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function logoutOtherBrowsersAction(): Action
    {
        return Action::make('logoutOtherBrowsers')
            ->label(__('shopper::words.log_out'))
            ->color('danger')
            ->size(Size::Small)
            ->modalWidth(Width::Large)
            ->modalIcon(Untitledui::LogOut)
            ->modalHeading(__('shopper::words.logout_session'))
            ->modalDescription(__('shopper::words.logout_session_confirm'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.logout_session'))
            ->schema([
                TextInput::make('password')
                    ->label(__('shopper::forms.label.password'))
                    ->password()
                    ->required()
                    ->placeholder(__('Enter your password')),
            ])
            ->action(function (array $data): void {
                // @phpstan-ignore-next-line
                if (! Hash::check($data['password'], shopper()->auth()->user()->password)) {
                    throw ValidationException::withMessages([
                        'mountedActions.0.data.password' => __('shopper::notifications.auth.password'),
                    ]);
                }

                Auth::guard(config('shopper.auth.guard'))->logoutOtherDevices($data['password']); // @phpstan-ignore-line

                $this->deleteOtherSessionRecords();
            });
    }

    /**
     * @return Collection<int, object>
     */
    #[Computed]
    public function sessions(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return DB::table('sessions')
            ->where('user_id', shopper()->auth()->user()->getKey()) // @phpstan-ignore-line
            ->orderBy('last_activity', 'desc')
            ->limit(3)
            ->get()->map(fn ($session) => (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'location' => Location::get($session->ip_address),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.devices');
    }

    protected function deleteOtherSessionRecords(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table('sessions')
            ->where('user_id', auth()->user()->getKey())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    protected function createAgent(object $session): Agent
    {
        return tap(new Agent, function ($agent) use ($session): void {
            $agent->setUserAgent($session->user_agent); // @phpstan-ignore-line
        });
    }
}
