<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('General')">
        <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-6">
        <x-slot name="title">
            {{ __('shopper::pages/settings.settings.title') }}
        </x-slot>
    </x-shopper::heading>

    <form wire:submit="store" class="mt-10">
        {{ $this->form }}

        <div class="pt-10 mt-10 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-end">
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.save') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>

    <x-filament-actions::modals />

</x-shopper::container>
