<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/discounts.menu')">
        <x-slot name="action">
            @can('add_discounts')
                <x-filament::button
                    type="button"
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.discount-form' })"
                >
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/discounts.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::DISCOUNTS_TABLE_BEFORE) }}

    <div class="mt-10">
        {{ $this->table }}
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::DISCOUNTS_TABLE_AFTER) }}

    <x-shopper::learn-more :name="__('shopper::pages/discounts.menu')" link="discounts" />
</x-shopper::container>
