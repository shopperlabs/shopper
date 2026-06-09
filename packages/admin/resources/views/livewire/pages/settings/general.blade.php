<x-shopper::container>
    <x-shopper::heading :title="__('shopper::pages/settings/global.general.title')" />

    <form wire:submit="store" class="mt-10">
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
</x-shopper::container>
