<form wire:submit="save">
    {{ $this->form }}

    <div class="mt-8 grid grid-cols-1 gap-x-6 md:grid-cols-3 md:gap-x-12">
        <div class="flex justify-end md:col-span-2 md:col-start-2 lg:max-w-3xl">
            <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                {{ __('shopper::forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</form>
