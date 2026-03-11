<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Shopper\Components\Form\GenderField;
use Shopper\Components\Section;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Models\Role;
use Shopper\Notifications\AdminSendCredentials;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class CreateTeamMember extends SlideOverComponent implements HasActions, HasForms
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('view_users');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/settings/staff.login_information'))
                    ->description(__('shopper::pages/settings/staff.login_information_summary'))
                    ->schema([
                        TextInput::make('email')
                            ->label(__('shopper::forms.label.email'))
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->label(__('shopper::forms.label.password'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->hintAction(
                                Action::make(__('shopper::words.generate'))
                                    ->color('info')
                                    ->action(function (Set $set): void {
                                        $set('password', Str::password(16));
                                    }),
                            ),
                        Toggle::make('send_mail')
                            ->label(__('shopper::pages/settings/staff.send_invite'))
                            ->helperText(__('shopper::pages/settings/staff.send_invite_summary')),
                    ]),
                Section::make(__('shopper::pages/settings/staff.personal_information'))
                    ->description(__('shopper::pages/settings/staff.personal_information_summary'))
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('shopper::forms.label.first_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->label(__('shopper::forms.label.last_name'))
                            ->required(),
                        GenderField::make(),
                        TextInput::make('phone_number')
                            ->label(__('shopper::forms.label.phone_number'))
                            ->tel(),
                    ]),
                Section::make(__('shopper::pages/settings/staff.role_information'))
                    ->description(__('shopper::pages/settings/staff.role_information_summary'))
                    ->schema([
                        Radio::make('role_id')
                            ->label(__('shopper::pages/settings/staff.choose_role'))
                            ->options(
                                Role::query()
                                    ->where('name', '<>', config('shopper.admin.roles.user'))
                                    ->pluck('display_name', 'id')
                            )
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $this->authorize('view_users');

        $data = $this->form->getState();
        $userModel = config('auth.providers.users.model');

        /** @var ShopperUser $user */
        $user = $userModel::create([
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => Hash::make(
                value: $data['password']
            ),
            'phone_number' => $data['first_name'],
            'gender' => $data['gender'],
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        /** @var Role $role */
        $role = Role::findById((int) $data['role_id']);

        $user->assignRole([$role->name]);

        $this->dispatch('teamUpdate');

        if ($data['send_mail']) {
            $user->notify(new AdminSendCredentials($data['password']));
        }

        /** @var Model $user */
        Notification::make()
            ->body(__('shopper::notifications.create', ['item' => $user->full_name]))
            ->success()
            ->send();

        $this->dispatch('closePanel');
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.create-team-member');
    }
}
