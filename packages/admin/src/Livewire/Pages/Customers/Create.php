<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Shopper\Components\Form\AddressField;
use Shopper\Components\Form\GenderField;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Core\Models\Country;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Notifications\CustomerSendCredentials;

/**
 * @property-read Schema $form
 */
class Create extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_customers');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/customers.overview'))
                    ->description(__('shopper::pages/customers.overview_description'))
                    ->compact()
                    ->aside()
                    ->columns()
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
                            ->unique()
                            ->required(),
                        TextInput::make('phone_number')
                            ->label(__('shopper::forms.label.phone_number'))
                            ->hint(__('shopper::forms.label.optional'))
                            ->tel(),
                        GenderField::make(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/customers.security_title'))
                    ->description(__('shopper::pages/customers.security_description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        TextInput::make('password')
                            ->label(__('shopper::forms.label.password'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->hintAction(
                                Action::make(__('shopper::words.generate'))
                                    ->color('info')
                                    ->action(function (Set $set): void {
                                        $set('password', Str::password(12));
                                    }),
                            )
                            ->confirmed()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)),
                        TextInput::make('password_confirmation')
                            ->label(__('shopper::forms.label.confirm_password'))
                            ->password()
                            ->revealable()
                            ->required(),
                        Hidden::make('_password'),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/customers.address_title'))
                    ->description(__('shopper::pages/customers.address_description'))
                    ->compact()
                    ->aside()
                    ->columns()
                    ->schema(AddressField::make('address')),
                Separator::make(),
                Section::make(__('shopper::pages/customers.notification_title'))
                    ->description(__('shopper::pages/customers.notification_description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Checkbox::make('opt_in')
                            ->label(__('shopper::pages/customers.marketing_email'))
                            ->helperText(__('shopper::pages/customers.marketing_description')),
                        Checkbox::make('send_mail')
                            ->label(__('shopper::pages/customers.send_credentials'))
                            ->helperText(__('shopper::pages/customers.credential_description')),
                    ]),
            ])
            ->statePath('data')
            ->model(config('auth.providers.users.model'));
    }

    public function store(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->form->getState();
        $sendMail = data_get($data, 'send_mail');
        $password = data_get($data, 'password_confirmation');

        $customerData = Arr::except($data, ['address', 'send_mail', 'password_confirmation']);
        $address = array_merge(Arr::only($data, ['address'])['address'], [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'type' => AddressType::Shipping,
        ]);

        $userModel = config('auth.providers.users.model');

        /** @var ShopperUser $customer */
        $customer = $userModel::create(array_merge(
            $customerData,
            ['email_verified_at' => now()->toDateTimeString()],
        ));

        $customer->assignRole(config('shopper.core.roles.user'));
        $customer->addresses()->create($address);

        if ($sendMail) {
            $customer->notify(new CustomerSendCredentials($password));
        }

        Notification::make()
            ->title(__('shopper::notifications.create', ['item' => __('shopper::pages/customers.single')]))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.create', [
            'countries' => Cache::get(
                key: 'countries-settings',
                default: fn (): Collection => Country::query()->orderBy('name')->get()
            ),
        ])
            ->title(__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')]));
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }
}
