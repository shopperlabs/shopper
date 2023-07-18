<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories')" :current="__('shopper::pages/settings.location.add')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.inventories')" :title="__('shopper::words.locations')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ __('shopper::pages/settings.location.add') }}
        </x-slot>
    </x-shopper::heading>

    @include('shopper::livewire.components.settings.inventories._form')

    <div class="mt-8 pt-6 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
