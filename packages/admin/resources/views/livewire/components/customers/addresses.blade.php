<x-shopper::container class="space-y-8">
    <section class="space-y-3">
        <h4 class="text-sh-fg text-sm font-semibold">
            {{ __('shopper::pages/customers.addresses.shipping_section') }}
        </h4>

        @if ($this->shippingAddresses->isEmpty())
            <x-shopper::card>
                <x-shopper::empty-card
                    icon="untitledui-truck"
                    :heading="__('shopper::pages/customers.addresses.shipping_empty_title')"
                    :description="__('shopper::pages/customers.addresses.shipping_empty')"
                />
            </x-shopper::card>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->shippingAddresses as $address)
                    @include('shopper::livewire.components.customers._address-card', [
                        'address' => $address,
                        'isDefault' => $address->shipping_default,
                    ])
                @endforeach
            </div>
        @endif
    </section>

    <section class="space-y-3">
        <h4 class="text-sh-fg text-sm font-semibold">
            {{ __('shopper::pages/customers.addresses.billing_section') }}
        </h4>

        @if ($this->billingAddresses->isEmpty())
            <x-shopper::card>
                <x-shopper::empty-card
                    icon="untitledui-credit-card-02"
                    :heading="__('shopper::pages/customers.addresses.billing_empty_title')"
                    :description="__('shopper::pages/customers.addresses.billing_empty')"
                />
            </x-shopper::card>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($this->billingAddresses as $address)
                    @include('shopper::livewire.components.customers._address-card', [
                        'address' => $address,
                        'isDefault' => $address->billing_default,
                    ])
                @endforeach
            </div>
        @endif
    </section>
</x-shopper::container>
