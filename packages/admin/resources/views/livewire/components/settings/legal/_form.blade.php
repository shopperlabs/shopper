<form class="mt-5 lg:col-span-2 max-w-3xl" wire:submit="store">
    {{ $this->form }}

    <div class="mt-6 flex justify-end">
        <x-shopper::buttons.primary wire:click="store" type="submit" wire:loading.attr="disabled">
            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
            {{ __('shopper::layout.forms.actions.save') }}
        </x-shopper::buttons.primary>
    </div>
</form>
