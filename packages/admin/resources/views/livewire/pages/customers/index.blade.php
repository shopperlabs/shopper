<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/customers.menu')">
        <x-slot name="action">
            @can('add_customers')
                <x-filament::button tag="a" :href="route('shopper.customers.create')">
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/customers.menu')" link="customers" />
</x-shopper::container>
