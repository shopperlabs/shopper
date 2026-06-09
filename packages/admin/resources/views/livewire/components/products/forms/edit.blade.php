<x-shopper::container>
    <form wire:submit="store">
        {{ $this->form }}

        <div class="mt-10 border-t border-sh-border pt-8">
            <div class="flex justify-end">
                <x-filament::button type="submit" wire.loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" />
                    {{ __('shopper::forms.actions.update') }}
                </x-filament::button>
            </div>
        </div>
    </form>

    <x-filament-actions::modals />
</x-shopper::container>
