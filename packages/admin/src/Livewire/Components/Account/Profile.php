<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Account;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Components\Section;
use Shopper\Traits\HasAuthenticated;

/**
 * @property-read Schema $form
 */
class Profile extends Component implements HasActions, HasForms
{
    use HasAuthenticated;
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getUser()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/auth.account.profile_title'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/auth.account.profile_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        FileUpload::make('avatar_location')
                            ->label(__('shopper::forms.label.photo'))
                            ->avatar()
                            ->image()
                            ->maxSize(1024)
                            ->disk(config('shopper.media.storage.disk_name')),
                        Grid::make()
                            ->schema([
                                TextInput::make('first_name')
                                    ->label(__('shopper::forms.label.first_name'))
                                    ->required(),
                                TextInput::make('last_name')
                                    ->label(__('shopper::forms.label.last_name'))
                                    ->required(),
                                TextInput::make('email')
                                    ->label(__('shopper::forms.label.email'))
                                    ->prefixIcon('untitledui-mail')
                                    ->autocomplete('email-address')
                                    ->email()
                                    ->required()
                                    ->unique(
                                        table: config('auth.providers.users.model'),
                                        ignorable: $this->getUser()
                                    ),
                                TextInput::make('phone_number')
                                    ->label(__('shopper::forms.label.phone_number'))
                                    ->tel(),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model($this->getUser());
    }

    public function save(): void
    {
        $this->getUser()
            ->update(
                array_merge(
                    $this->form->getState(),
                    filled($this->form->getState()['avatar_location'])
                        ? ['avatar_type' => 'storage']
                        : ['avatar_type' => 'avatar_ui']
                )
            );

        $this->dispatch('profile.updated');

        Notification::make()
            ->title(__('shopper::notifications.users_roles.profile_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.account.profile');
    }
}
