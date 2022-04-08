<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('New Mailable') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <x-shopper::forms.group label="Name" for="name" class="sm:col-span-2" :error="$errors->first('name')" helpText="Enter mailable name e.g Welcome User, WelcomeUser" isRequired>
                <x-shopper::forms.input wire:model.defer="name" type="text" id="name" placeholder="Mailable name" />
            </x-shopper::forms.group>
            <div class="sm:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <x-shopper::forms.checkbox wire:model.defer="isMarkdown" id="is_markdown" />
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="is_markdown" class="font-medium text-secondary-700 dark:text-secondary-300">{{ __('Markdown Template') }}</label>
                        <p class="text-secondary-500 dark:text-secondary-400">{{ __('Use markdown template.') }}</p>
                    </div>
                </div>
            </div>

            @if($isMarkdown)
                <x-shopper::forms.group label="Markdown" for="markdown" class="sm:col-span-2" :error="$errors->first('markdownView')" isRequired>
                    <x-shopper::forms.input wire:model.defer="markdownView" type="text" id="markdown" placeholder="Eg. markdown.view" />
                </x-shopper::forms.group>
            @endif

            <div class="sm:col-span-2">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <x-shopper::forms.checkbox wire:model.defer="isForce" id="is_force" />
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="is_force" class="font-medium text-secondary-700 dark:text-secondary-300">{{ __('Force') }}</label>
                        <p class="text-secondary-500 dark:text-secondary-400">{{ __('Force mailable creation even if already exists.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::button wire:click="generateMailable" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="generateMailable" class="text-white" />
                {{ __('Save') }}
            </x-shopper::button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Cancel') }}
            </x-shopper::default-button>
        </span>
    </x-slot>

</x-shopper::modal>
