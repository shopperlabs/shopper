<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/collections.menu')">
        <x-slot name="action">
            @can('add_collections')
                <x-filament::button
                    type="button"
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.add-collection-form' })"
                >
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/collections.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/collections.menu')" link="collections" />
</x-shopper::container>
