<x-shopper::container class="py-5">
    <x-shopper::heading class="mt-6" :title="$collection->name" />

    {{ shopper()->getRenderHook(\Shopper\View\CollectionRenderHook::EDIT_FORM_BEFORE) }}

    <form wire:submit="store" class="mt-8 border-t border-sh-border pt-10">
        <div class="space-y-10">
            {{ $this->form }}

            <div class="border-t border-sh-border py-8">
                <div class="flex justify-end">
                    <x-filament::button type="submit" wire.loading.attr="disabled">
                        <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                        {{ __('shopper::forms.actions.update') }}
                    </x-filament::button>
                </div>
            </div>
        </div>
    </form>

    {{ shopper()->getRenderHook(\Shopper\View\CollectionRenderHook::EDIT_FORM_AFTER) }}
</x-shopper::container>
