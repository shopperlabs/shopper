<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Shopper\Components\Section;
use Shopper\Core\Models\Role;
use Shopper\Core\Models\User;
use Shopper\Core\Repositories\UserRepository;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Notifications\AdminSendCredentials;

class CreateTeamMember extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::pages/settings.roles_permissions.login_information'))
                    ->description(__('shopper::pages/settings.roles_permissions.login_information_summary'))
                    ->schema([
                        Components\TextInput::make('email')
                            ->label(__('shopper::layout.forms.label.email'))
                            ->email()
                            ->required(),
                        Components\TextInput::make('password')
                            ->label(__('shopper::layout.forms.label.password'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->hintAction(
                                Components\Actions\Action::make(__('shopper::words.generate'))
                                    ->color('info')
                                    ->action(function (Set $set): void {
                                        $set('password', Str::password(16));
                                    }),
                            ),
                        Components\Toggle::make('send_mail')
                            ->label(__('shopper::pages/settings.roles_permissions.send_invite'))
                            ->helperText(__('shopper::pages/settings.roles_permissions.send_invite_summary')),
                    ]),
                Section::make(__('shopper::pages/settings.roles_permissions.personal_information'))
                    ->description(__('shopper::pages/settings.roles_permissions.personal_information_summary'))
                    ->schema([
                        Components\TextInput::make('first_name')
                            ->label(__('shopper::layout.forms.label.first_name'))
                            ->required(),
                        Components\TextInput::make('last_name')
                            ->label(__('shopper::layout.forms.label.last_name'))
                            ->required(),
                        Components\Select::make('gender')
                            ->label(__('shopper::layout.forms.label.gender'))
                            ->options([
                                'male' => __('shopper::words.male'),
                                'female' => __('shopper::words.female'),
                            ])
                            ->default('male')
                            ->native(false),
                        Components\TextInput::make('phone_number')
                            ->label(__('shopper::layout.forms.label.phone_number'))
                            ->tel(),
                    ]),
                Section::make(__('shopper::pages/settings.roles_permissions.role_information'))
                    ->description(__('shopper::pages/settings.roles_permissions.role_information_summary'))
                    ->schema([
                        Components\Radio::make('role_id')
                            ->label(__('shopper::pages/settings.roles_permissions.choose_role'))
                            ->options(
                                Role::query()
                                    ->where('name', '<>', config('shopper.core.users.default_role'))
                                    ->pluck('display_name', 'id')
                            )
                            ->required()
                            ->live(onBlur: true),
                    ]),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $data = $this->form->getState();

        /** @var User $user */
        $user = (new UserRepository())->create([
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

        Notification::make()
            ->body(__('shopper::notifications.actions.create', ['item' => $user->full_name]))
            ->success()
            ->send();

        $this->dispatch('closePanel');
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.create-team-member');
    }
}
