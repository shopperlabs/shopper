<form wire:submit="store" class="mt-10">
    {{ $this->form }}

    <div class="mt-10 border-t border-gray-200 pt-10 dark:border-white/10">
        <div class="flex items-center justify-end">
            <x-shopper::buttons.primary wire:click="store" type="submit" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</form>
