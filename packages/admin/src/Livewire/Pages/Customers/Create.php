<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Shopper\Components\Form\AddressField;
use Shopper\Components\Form\GenderField;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\User;
use Shopper\Core\Repositories\UserRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Notifications\CustomerSendCredentials;

class Create extends AbstractPageComponent implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_customers');
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::pages/customers.overview'))
                    ->description(__('shopper::pages/customers.overview_description'))
                    ->compact()
                    ->aside()
                    ->columns()
                    ->schema([
                        Components\TextInput::make('first_name')
                            ->label(__('shopper::layout.forms.label.first_name'))
                            ->required(),
                        Components\TextInput::make('last_name')
                            ->label(__('shopper::layout.forms.label.last_name'))
                            ->required(),
                        Components\TextInput::make('email')
                            ->label(__('shopper::layout.forms.label.email'))
                            ->prefixIcon('untitledui-mail')
                            ->autocomplete('email-address')
                            ->email()
                            ->unique()
                            ->required(),
                        Components\TextInput::make('phone_number')
                            ->label(__('shopper::layout.forms.label.phone_number'))
                            ->hint(__('shopper::layout.forms.label.optional'))
                            ->tel(),
                        GenderField::make(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/customers.security_title'))
                    ->description(__('shopper::pages/customers.security_description'))
                    ->compact()
                    ->aside()
                    ->schema([
                        Components\TextInput::make('password')
                            ->label(__('shopper::layout.forms.label.password'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->hintAction(
                                Components\Actions\Action::make(__('shopper::words.generate'))
                                    ->color('info')
                                    ->action(function (Set $set): void {
                                        $set('password', Str::password(12));
                                    }),
                            )
                            ->confirmed()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)),
                        Components\TextInput::make('password_confirmation')
                            ->label(__('shopper::layout.forms.label.confirm_password'))
                            ->password()
                            ->revealable()
                            ->required(),
                        Components\Hidden::make('_password'),
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
                        Components\Checkbox::make('opt_in')
                            ->label(__('shopper::pages/customers.marketing_email'))
                            ->helperText(__('shopper::pages/customers.marketing_description')),
                        Components\Checkbox::make('send_mail')
                            ->label(__('shopper::pages/customers.send_credentials'))
                            ->helperText(__('shopper::pages/customers.credential_description')),
                    ]),
            ])
            ->statePath('data')
            ->model(config('auth.providers.users.model', User::class));
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    public function store(): void
    {
        $data = $this->form->getState();
        $customerData = Arr::except($data, ['address', 'send_mail', 'password_confirmation']);
        $address = array_merge(Arr::only($data, ['address'])['address'], [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'is_default' => true,
            'type' => AddressType::SHIPPING,
        ]);

        /** @var User $customer */
        $customer = (new UserRepository())->create(array_merge(
            $customerData,
            ['email_verified_at' => now()->toDateTimeString()],
        ));

        $customer->assignRole(config('shopper.core.users.default_role'));

        $customer->addresses()->create($address);

        if ($data['send_mail']) {
            $customer->notify(new CustomerSendCredentials($data['password_confirmation']));
        }

        Notification::make()
            ->title(__('shopper::notifications.actions.create', ['item' => __('shopper::words.customer')]))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.create', [
            'countries' => Cache::get(
                key: 'countries-settings',
                default: fn () => Country::query()->orderBy('name')->get()
            ),
        ])
            ->title(__('shopper::words.actions_label.add_new', ['name' => __('shopper::words.customer')]));
    }
}
