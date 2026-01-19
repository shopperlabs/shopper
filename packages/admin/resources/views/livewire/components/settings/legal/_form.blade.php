<form class="mt-5 max-w-3xl lg:col-span-2" wire:submit="store">
    {{ $this->form }}

    <div class="mt-6 flex justify-end">
        <x-filament::button type="submit" wire:loading.attr="disabled">
            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
            {{ __('shopper::forms.actions.save') }}
        </x-filament::button>
    </div>
</form>
