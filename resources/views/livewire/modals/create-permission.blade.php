<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-100"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        <span class="flex flex-col">
            {{ __('New permission') }}
            <span class="mt-0.5 font-normal text-sm leading-5 text-gray-500">
                {{ __('Add a new permission and directly assign to this role.') }}
            </span>
        </span>
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Group name" for="group">
                    <x-shopper-input.select id="group" wire:model.lazy="group">
                        <option>{{ __('No group') }}</option>
                        @foreach($groups as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-shopper-input.select>
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Permission name (in lowercase)" for="permission_name" isRequired :error="$errors->first('name')">
                    <x-shopper-input.text wire:model.lazy="name" id="name" placeholder="create_post, manage_articles, etc" autocomplete="off" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Display name" for="display_name" isRequired :error="$errors->first('display_name')">
                    <x-shopper-input.text wire:model.lazy="display_name" id="display_name" placeholder="Create Blog posts" autocomplete="off" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Description" for="description">
                    <x-shopper-input.textarea wire:model.lazy="description" id="description" />
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
