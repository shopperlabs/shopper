<x-shopper::container>
    <div x-data="{ on: @entangle('is_enabled') }">
        <x-shopper::breadcrumb :back="route('shopper.categories.index')">
            <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.categories.index')" :title="__('shopper::layout.sidebar.categories')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5">
            <x-slot name="title">
                {{ $name }}
            </x-slot>
        </x-shopper::heading>

        @include('shopper::livewire.components.categories._form')

        <div class="mt-8 border-t border-secondary-200 pt-6 pb-10 dark:border-secondary-700">
            <div class="flex justify-end">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </div>
</x-shopper::container>
