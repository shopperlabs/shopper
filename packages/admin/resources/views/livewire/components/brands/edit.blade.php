<x-shopper::container>
    <div x-data="{ on: @entangle('is_enabled') }">
        <x-shopper::breadcrumb :back="route('shopper.brands.index')">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.brands.index')" :title="__('shopper::layout.sidebar.brands')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5">
            <x-slot name="title">
                {{ $name }}
            </x-slot>
        </x-shopper::heading>

        @include('shopper::livewire.components.brands._form')

        <div class="mt-8 border-t border-gray-200 pt-6 pb-10 dark:border-gray-700">
            <div class="flex justify-end">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </div>
</x-shopper::container>
