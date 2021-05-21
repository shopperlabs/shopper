<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-100"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __("New Mailable") }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Name" for="name" :error="$errors->first('name')" helpText="Enter mailable name e.g Welcome User, WelcomeUser" isRequired>
                    <x-shopper-input.text wire:model.lazy="name" id="name" placeholder="Mailable name" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model.lazy="isMarkdown" id="is_markdown" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="is_markdown" class="font-medium text-gray-700">{{ __('Markdown Template') }}</label>
                        <p class="text-gray-500">{{ __('Use markdown template.') }}</p>
                    </div>
                </div>
            </div>

            @if($isMarkdown)
                <div class="sm:col-span-2">
                    <x-shopper-input.group label="Markdown" for="markdown" :error="$errors->first('markdownView')" isRequired>
                        <x-shopper-input.text wire:model.lazy="markdownView" id="markdown" placeholder="Eg. markdown.view" />
                    </x-shopper-input.group>
                </div>
            @endif

            <div class="sm:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input wire:model.lazy="isForce" id="is_force" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="is_force" class="font-medium text-gray-700">{{ __('Force') }}</label>
                        <p class="text-gray-500">{{ __('Force mailable creation even if already exists.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="generateMailable" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="generateMailable" class="text-white" />
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
