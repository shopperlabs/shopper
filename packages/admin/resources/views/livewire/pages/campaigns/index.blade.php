<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/campaigns.menu')">
        <x-slot name="action">
            @can('campaigns.create')
                <x-filament::button tag="a" :href="route('shopper.campaigns.create')" wire:navigate>
                    {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/campaigns.single')]) }}
                </x-filament::button>
            @endcan
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10">
        {{ $this->table }}
    </div>
</x-shopper::container>
