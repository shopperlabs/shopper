<form wire:submit="store" class="mt-10">
    {{ $this->form }}

    <div class="border-sh-border mt-10 border-t pt-10">
        <div class="flex items-center justify-end">
            <x-filament::button wire:click="store" type="submit" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::forms.actions.save') }}
            </x-filament::button>
        </div>
    </div>
</form>
