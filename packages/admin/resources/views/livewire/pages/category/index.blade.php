<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/categories.menu')">
        <x-slot name="action">
            @can('add_categories')
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.category-form' })"
                    type="button"
                >
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/categories.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::CATEGORIES_TABLE_BEFORE) }}

    <div class="mt-10">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CatalogRenderHook::CATEGORIES_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/categories.menu')" link="categories" />
</x-shopper::container>
