<x-shopper-modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Add new value for :attribute', ['attribute' => $attribute->name]) }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-shopper-input.group label="Value" for="value" class="sm:col-span-2" :error="$errors->first('value')">
                <x-shopper-input.text wire:model.defer="value" type="text" id="value" placeholder="My value" />
            </x-shopper-input.group>

            <div class="sm:col-span-2">
                @if($type === 'colorpicker')
                    <div wire:ignore>
                        <x-shopper-label :value="__('Key')" />
                        <x-shopper-input.color-picker name="key" wire:model.defer="key" placeholder="#9800BK" />
                        <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">{{ __('The key will be used for the values in storage for the forms (option, radio, etc.). Must be in slug format') }}</p>
                    </div>
                    @error('key')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <x-shopper-input.group label="Key" for="key" class="sm:col-span-2" :error="$errors->first('key')" helpText="The key will be used for the values in storage for the forms (option, radio, etc.). Must be in slug format">
                        <x-shopper-input.text wire:model.defer="key" type="text" id="key" placeholder="my_key" />
                    </x-shopper-input.group>
                @endif
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="save" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Cancel') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
