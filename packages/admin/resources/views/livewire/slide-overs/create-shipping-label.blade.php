<form wire:submit="save" class="flex h-full flex-col">
    <header class="shrink-0 border-b border-gray-100 p-4 dark:border-white/10">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/orders.create_shipping_label') }}
            </h2>
            <x-shopper::close-icon />
        </div>
    </header>

    <div class="min-h-0 h-full flex-1 overflow-y-auto px-4">
        {{ $this->form }}
    </div>

    <footer class="flex shrink-0 justify-end gap-3 border-t border-gray-100 p-4 dark:border-white/10">
        <x-filament::button
            color="gray"
            wire:click="$dispatch('closePanel')"
            type="button"
        >
            {{ __('shopper::forms.actions.cancel') }}
        </x-filament::button>
        <x-filament::button type="submit" wire:loading.attr="disabled">
            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
            {{ __('shopper::pages/orders.create_shipping_label') }}
        </x-filament::button>
    </footer>
</form>
