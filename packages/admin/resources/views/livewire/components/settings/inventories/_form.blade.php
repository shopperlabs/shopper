<form wire:submit="store" class="mt-10">
    {{ $this->form }}

    <div class="mt-10 border-t border-gray-200 pt-10 dark:border-gray-700">
        <div class="flex items-center justify-end space-x-4">
            @can('delete_inventories')
                @isset($inventory->id)
                    <span class="w-full sm:w-auto">
                        <x-shopper::buttons.danger
                            wire:click="$emit('openModal', 'shopper-modals.delete-inventory', {{ json_encode([$inventory->id, $inventory->name]) }})"
                            type="button"
                        >
                            <x-untitledui-trash-03 class="mr-2 h-5 w-5" aria-hidden="true" />
                            {{ __('shopper::layout.status.delete') }}
                        </x-shopper::buttons.danger>
                    </span>
                @endisset
            @endcan

            <x-shopper::buttons.primary wire:click="store" type="submit" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</form>
