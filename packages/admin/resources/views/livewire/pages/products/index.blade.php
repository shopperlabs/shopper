<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/products.menu')">
        <x-slot name="action">
            @can('products.create')
                <div class="flex items-center gap-2">
                    {{ $this->importAction }}
                    <x-filament::button
                        wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.add-product' })"
                        type="button"
                    >
                        {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/products.single')]) }}
                    </x-filament::button>
                </div>
            @endcan
        </x-slot>
    </x-shopper::heading>

    {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::INDEX_TABLE_BEFORE) }}

    <div class="mt-10">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\ProductRenderHook::INDEX_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/products.menu')" link="products" />

    <x-filament-actions::modals />
</x-shopper::container>
