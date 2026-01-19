<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/brands.menu')">
        <x-slot name="action">
            @can('add_brands')
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.brand-form' })"
                    type="button"
                >
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/brands.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/brands.menu')" link="brands" />
</x-shopper::container>
