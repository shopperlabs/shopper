<form wire:submit="store">
    {{ $this->form }}

    <div class="mt-10 flex justify-end">
        <x-filament::button type="submit" wire.loading.attr="disabled">
            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
            {{ __('shopper::forms.actions.update') }}
        </x-filament::button>
    </div>
</form>
