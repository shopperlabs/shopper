<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Address;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read array{ltv: int, orders_count: int, orders_count_in_currency: int, aov: int, last_order_at: ?CarbonInterface} $stats
 * @property-read ?Address $defaultAddress
 * @property-read ?object $prevCustomer
 * @property-read ?object $nextCustomer
 */
class Show extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithBreadcrumbs;

    #[Locked]
    public ShopperUser $customer;

    #[Url(as: 'tab')]
    public string $activeTab = 'profile';

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: $this->customer->full_name),
        ];
    }

    public function mount(int $user): void
    {
        $this->authorize('customers.read');

        $userModel = config('auth.providers.users.model');

        $activeScope = fn (Builder $query): Builder => $query
            ->whereIn('status', [
                OrderStatus::Processing,
                OrderStatus::Completed,
            ])
            ->where('payment_status', PaymentStatus::Paid);

        $currencyScope = fn (Builder $query): Builder => $activeScope($query)
            ->where('currency_code', shopper_currency());

        /** @var ShopperUser $customer */
        $customer = $userModel::query()
            ->customers()
            ->with(['addresses', 'addresses.country'])
            ->withCount([
                'orders as paid_orders_count' => $activeScope,
                'orders as paid_orders_in_currency_count' => $currencyScope,
            ])
            ->withSum(['orders as paid_orders_total' => $currencyScope], 'price_amount')
            ->withMax(['orders as paid_last_order_at' => $activeScope], 'created_at')
            ->findOrFail($user);

        $this->customer = $customer;
    }

    public function goToCustomer(int $id): void
    {
        $this->authorize('customers.read');

        $userModel = config('auth.providers.users.model');

        abort_unless(
            $userModel::query()->customers()->whereKey($id)->exists(),
            404
        );

        $this->redirectRoute(name: 'shopper.customers.show', parameters: ['user' => $id], navigate: true);
    }

    /**
     * @return array{ltv: int, orders_count: int, orders_count_in_currency: int, aov: int, last_order_at: ?CarbonInterface}
     */
    #[Computed]
    public function stats(): array
    {
        $count = (int) ($this->customer->paid_orders_count ?? 0);
        $countInCurrency = (int) ($this->customer->paid_orders_in_currency_count ?? 0);
        $total = (int) ($this->customer->paid_orders_total ?? 0);
        $lastOrderAt = $this->customer->paid_last_order_at ?? null;

        return [
            'ltv' => $total,
            'orders_count' => $count,
            'orders_count_in_currency' => $countInCurrency,
            'aov' => $countInCurrency > 0 ? (int) round($total / $countInCurrency) : 0,
            'last_order_at' => $lastOrderAt === null ? null : CarbonImmutable::parse((string) $lastOrderAt),
        ];
    }

    #[Computed]
    public function defaultAddress(): ?Address
    {
        $shipping = $this->customer->addresses
            ->where('type', AddressType::Shipping)
            ->sortByDesc('shipping_default')
            ->first();

        if ($shipping) {
            return $shipping;
        }

        /** @var ?Address $billing */
        $billing = $this->customer->addresses
            ->where('type', AddressType::Billing)
            ->sortByDesc('billing_default')
            ->first();

        return $billing;
    }

    #[Computed]
    public function prevCustomer(): ?object
    {
        $userModel = config('auth.providers.users.model');

        return $userModel::query()
            ->customers()
            ->where('id', '<', $this->customer->id)
            ->latest('id')
            ->first(['id']);
    }

    #[Computed]
    public function nextCustomer(): ?object
    {
        $userModel = config('auth.providers.users.model');

        return $userModel::query()
            ->customers()
            ->where('id', '>', $this->customer->id)
            ->oldest('id')
            ->first(['id']);
    }

    public function anonymizeAction(): Action
    {
        return Action::make('anonymize')
            ->label(__('shopper::pages/customers.anonymize.action'))
            ->authorize('customers.delete', $this->customer) // @phpstan-ignore-line
            ->icon(Untitledui::UserX02)
            ->modalIcon(Untitledui::UserX02)
            ->modalHeading(__('shopper::pages/customers.anonymize.title'))
            ->modalDescription(__('shopper::pages/customers.anonymize.description'))
            ->modalSubmitActionLabel(__('shopper::pages/customers.anonymize.confirm'))
            ->visible(shopper()->auth()->user()->can('customers.delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (): void {
                /** @var Model&ShopperUser $customer */
                $customer = $this->customer;

                DB::transaction(function () use ($customer): void {
                    $customer->update([
                        'first_name' => __('shopper::pages/customers.anonymize.first_name'),
                        'last_name' => __('shopper::pages/customers.anonymize.last_name'),
                        'email' => 'anonymized_'.$customer->id.'_'.Str::random(8).'@anonymized.local',
                        'phone_number' => null,
                        'gender' => null,
                        'avatar_type' => 'gravatar',
                        'avatar_location' => null,
                        'birth_date' => null,
                        'email_verified_at' => null,
                        'last_login_at' => null,
                        'last_login_ip' => null,
                        'store_two_factor_secret' => null,
                        'store_two_factor_recovery_codes' => null,
                        'opt_in' => false,
                    ]);

                    $customer->addresses()->delete();
                });

                Notification::make()
                    ->title(__('shopper::pages/customers.anonymize.success'))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
            });
    }

    public function render(): View
    {
        /** @var Model&ShopperUser $customer */
        $customer = $this->customer;

        return view('shopper::livewire.pages.customers.show')
            ->title(__('shopper::forms.actions.show_label', ['label' => $customer->full_name]));
    }
}
