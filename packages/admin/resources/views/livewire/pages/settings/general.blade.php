<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('shopper::pages/settings/menu.general')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.index')"
            :title="__('shopper::pages/settings/global.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-6">
        <x-slot name="title">
            {{ __('shopper::pages/settings/global.general.title') }}
        </x-slot>
    </x-shopper::heading>

    <form wire:submit="store" class="mt-10">
        {{ $this->form }}

        <div class="mt-10 border-t border-gray-200 pt-10 dark:border-gray-700">
            <div class="flex justify-end">
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::forms.actions.save') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>

    <x-filament-actions::modals />
</x-shopper::container>
