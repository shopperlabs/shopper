<x-shopper::container>
    <div x-data="{ on: @entangle('is_enabled') }">
        <x-shopper::breadcrumb :back="route('shopper.brands.index')">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.brands.index')" :title="__('shopper::layout.sidebar.brands')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5">
            <x-slot name="title">
                {{ __('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::words.brand'))]) }}
            </x-slot>
        </x-shopper::heading>
    </div>
</x-shopper::container>
