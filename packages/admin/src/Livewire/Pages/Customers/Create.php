<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Form\AddressField;
use Shopper\Components\Form\GenderField;
use Shopper\Components\Form\PhoneInput;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Events\Customers\CustomerRegistered;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Notifications\CustomerSendCredentials;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Create extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::forms.actions.create')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('customers.create');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/customers.overview'))
                    ->aside()
                    ->compact()
                    ->columns()
                    ->description(__('shopper::pages/customers.overview_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('shopper::forms.label.first_name'))
                            ->required(),
                        TextInput::make('last_name')
                            ->label(__('shopper::forms.label.last_name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('shopper::forms.label.email'))
                            ->prefixIcon(Untitledui::Mail)
                            ->autocomplete('email-address')
                            ->email()
                            ->unique()
                            ->required(),
                        PhoneInput::make('phone_number')
                            ->hint(__('shopper::forms.label.optional')),
                        GenderField::make(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/customers.address_title'))
                    ->aside()
                    ->compact()
                    ->columns()
                    ->description(__('shopper::pages/customers.address_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema(AddressField::make('address')),
                Separator::make(),
                Section::make(__('shopper::pages/customers.notification_title'))
                    ->aside()
                    ->compact()
                    ->columns()
                    ->description(__('shopper::pages/customers.notification_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        Checkbox::make('opt_in')
                            ->label(__('shopper::pages/customers.marketing_email'))
                            ->helperText(__('shopper::pages/customers.marketing_description')),
                        Checkbox::make('send_mail')
                            ->label(__('shopper::pages/customers.send_invitation'))
                            ->helperText(__('shopper::pages/customers.invitation_description'))
                            ->visible(fn (): bool => ResetPassword::$createUrlCallback !== null || Route::has('password.reset')),
                    ]),
            ])
            ->statePath('data')
            ->model(config('auth.providers.users.model'));
    }

    public function store(): void
    {
        $this->authorize('customers.create');

        /** @var array<string, mixed> $data */
        $data = $this->form->getState();
        $sendMail = data_get($data, 'send_mail');

        $customerData = Arr::only($data, [
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'gender',
            'opt_in',
        ]);
        $address = array_merge(Arr::only($data, ['address'])['address'], [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'type' => AddressType::Shipping,
        ]);

        $userModel = config('auth.providers.users.model');

        /** @var ShopperUser $customer */
        $customer = DB::transaction(function () use ($userModel, $customerData, $address): ShopperUser {
            /** @var ShopperUser $customer */
            $customer = $userModel::create(array_merge(
                $customerData,
                [
                    'password' => Hash::make(Str::password()),
                    'email_verified_at' => now()->toDateTimeString(),
                ],
            ));

            $customer->assignRole(config('shopper.admin.roles.user'));
            $customer->addresses()->create($address);

            return $customer;
        });

        event(new CustomerRegistered($customer));

        if ($sendMail) {
            $status = Password::sendResetLink(
                ['email' => $customer->email],
                fn (ShopperUser $customer, string $token) => $customer->notify(new CustomerSendCredentials($token)),
            );

            if ($status !== Password::RESET_LINK_SENT) {
                Notification::make()
                    ->title(__('shopper::pages/customers.invitation_not_sent'))
                    ->body(__($status))
                    ->warning()
                    ->send();
            }
        }

        Notification::make()
            ->title(__('shopper::notifications.create', ['item' => __('shopper::pages/customers.single')]))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.create')
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
