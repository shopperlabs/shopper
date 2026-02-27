<x-shopper::container class="py-5">
    <x-shopper::breadcrumb :back="route('shopper.customers.index')">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.customers.index')"
            :title="__('shopper::pages/customers.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading
        class="pt-6"
        :title="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')])"
    />

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::CREATE_FORM_BEFORE) }}

    <form wire:submit="store" class="mt-10">
        {{ $this->form }}

        <div class="mt-10 border-t border-gray-200 pt-10 dark:border-white/20">
            <div class="flex justify-end">
                <x-filament::button type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::forms.actions.save') }}
                </x-filament::button>
            </div>
        </div>
    </form>

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::CREATE_FORM_AFTER) }}
</x-shopper::container>
