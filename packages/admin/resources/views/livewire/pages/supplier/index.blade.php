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

    {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::SUPPLIERS_TABLE_BEFORE) }}

    <div class="mt-10">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::SUPPLIERS_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/suppliers.menu')" link="suppliers" />
</x-shopper::container>
