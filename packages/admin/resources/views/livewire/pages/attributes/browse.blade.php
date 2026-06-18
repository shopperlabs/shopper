<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/attributes.menu')">
        <x-slot name="action">
            @can('attributes.create')
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.attribute-form' })"
                    type="button"
                >
                    {{ __('shopper::forms.actions.create') }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::ATTRIBUTES_TABLE_BEFORE) }}

    <div class="mt-8">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::ATTRIBUTES_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/attributes.menu')" link="attributes" />
</x-shopper::container>
