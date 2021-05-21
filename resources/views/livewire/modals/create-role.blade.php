<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-100"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        <span>{{ __('Add new role') }}</span>
        <p class="mt-1 sm:mt-0 sm:ml-3 font-normal text-sm leading-5 text-gray-500">{{ __("Add a new role and assign permissions for administrators.") }}</p>
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <div class="sm:col-span-1">
                <x-shopper-input.group label="Name (in lowercase)" for="name" isRequired :error="$errors->first('name')">
                    <x-shopper-input.text wire:model="name" id="name" placeholder="manager" autocomplete="off" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-1">
                <x-shopper-input.group label="Display name" for="display_name">
                    <x-shopper-input.text wire:model="display_name" id="display_name" placeholder="Manager" autocomplete="off" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Description" for="description">
                    <x-shopper-input.textarea wire:model="description" id="description" />
                </x-shopper-input.group>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="save" type="button">
                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                {{ __("Save") }}
            </x-shopper-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __("Cancel") }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
