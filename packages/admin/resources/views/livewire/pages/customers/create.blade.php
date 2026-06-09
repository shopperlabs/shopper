<x-shopper::container class="space-y-8 py-5">
    <div class="space-y-2">
        <x-shopper::heading
            :title="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')])"
        />
        <p class="text-sh-fg-secondary max-w-2xl text-sm">
            {{ __('shopper::pages/customers.create.description') }}
        </p>
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\CustomerRenderHook::CREATE_FORM_BEFORE) }}

    <form wire:submit="store">
        {{ $this->form }}

        <div class="mt-10 border-t border-sh-border pt-10">
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
