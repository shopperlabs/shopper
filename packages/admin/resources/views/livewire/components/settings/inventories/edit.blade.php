<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories')" :current="$inventory->name">
        <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.inventories')" :title="__('shopper::words.locations')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ $inventory->name }}
        </x-slot>
    </x-shopper::heading>

    @include('shopper::livewire.components.settings.inventories._form')

    <div class="mt-8 pt-6 pb-10 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex items-center justify-end space-x-4">
            @can('delete_inventories')
                <span class="w-full sm:w-auto">
                    <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-inventory', {{ json_encode([$inventoryId, $name]) }})" type="button">
                        <x-untitledui-trash-03 class="w-5 h-5 mr-2" />
                        {{ __('shopper::layout.status.delete') }}
                    </x-shopper::buttons.danger>
                </span>
            @endcan
            <span class="ml-auto flex justify-end">
                <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </span>
        </div>
    </div>
</x-shopper::container>
