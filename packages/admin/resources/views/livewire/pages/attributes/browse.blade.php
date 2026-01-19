<x-shopper::container class="py-5">
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.index')"
            :title="__('shopper::pages/products.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5" :title="__('shopper::pages/attributes.menu')">
        <x-slot name="action">
            @can('add_attributes')
                <x-filament::button
                    wire:click="$dispatch('openPanel', { component: 'shopper-slide-overs.attribute-form' })"
                    type="button"
                >
                    {{ __('shopper::forms.actions.create') }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-8">
        {{ $this->table }}
    </div>

    <x-shopper::learn-more :name="__('shopper::pages/attributes.menu')" link="attributes" />
</x-shopper::container>
