<x-shopper::container>
    <form wire:submit="store">
        {{ $this->form }}

        <div class="mt-10 border-t border-gray-200 pt-8 dark:border-white/10">
            <div class="flex justify-end">
                <x-filament::button type="submit" wire.loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::forms.actions.update') }}
                </x-filament::button>
            </div>
        </div>
    </form>
</x-shopper::container>
