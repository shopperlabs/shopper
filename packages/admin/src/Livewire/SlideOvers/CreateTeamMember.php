<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Shopper\Components\Form\GenderField;
use Shopper\Components\Form\PhoneInput;
use Shopper\Components\Section;
use Shopper\Contracts\SlideOverForm;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Models\Role;
use Shopper\Notifications\AdminSendCredentials;
use Shopper\Traits\AuthorizesTeamManagement;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class CreateTeamMember extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use AuthorizesTeamManagement;
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeTeamAccess();

        $this->title = __('shopper::pages/settings/staff.add_admin');

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
                            ->inlineSuffix()
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
                        PhoneInput::make('phone_number'),
                    ]),
                Section::make(__('shopper::pages/settings/staff.role_information'))
                    ->description(__('shopper::pages/settings/staff.role_information_summary'))
                    ->schema([
                        CheckboxList::make('roles')
                            ->label(__('shopper::pages/settings/staff.choose_role'))
                            ->options(
                                Role::query()
                                    ->where('name', '<>', config('shopper.admin.roles.user'))
                                    ->unless(
                                        $this->actingUserIsAdmin(),
                                        fn (Builder $query): Builder => $query->where('name', '<>', config('shopper.admin.roles.admin'))
                                    )
                                    ->pluck('display_name', 'id')
                            )
                            ->columns()
                            ->required(),
                    ]),
                Callout::make(__('shopper::words.attention_needed'))
                    ->description(__('shopper::words.attention_description', ['role' => config('shopper.admin.roles.admin')]))
                    ->warning(),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $this->authorizeTeamAccess();

        $data = $this->form->getState();
        $userModel = config('auth.providers.users.model');

        /** @var Collection<int, Role> $roles */
        $roles = Role::query()->findMany($data['roles']);

        $roles->each(fn (Role $role) => $this->authorizeTeamAccess($role));

        /** @var ShopperUser $user */
        $user = $userModel::query()->create([
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'password' => Hash::make(
                value: $data['password']
            ),
            'phone_number' => $data['phone_number'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $user->assignRole($roles->pluck('name')->all());

        $this->dispatch('teamUpdate');

        if ($data['send_mail']) {
            $user->notify(new AdminSendCredentials($data['password']));
        }

        /** @var Model $user */
        Notification::make()
            ->title(__('shopper::notifications.create', ['item' => $user->full_name]))
            ->success()
            ->send();

        $this->dispatch('admin.created');

        $this->closePanel();
    }
}
