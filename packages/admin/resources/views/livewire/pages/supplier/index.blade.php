<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/suppliers.menu')">
        <x-slot name="action">
            @can('add_suppliers')
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.supplier-form' })"
                    type="button"
                >
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/suppliers.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/suppliers.menu')" link="suppliers" />
</x-shopper::container>
