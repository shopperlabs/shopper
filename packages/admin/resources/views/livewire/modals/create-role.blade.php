<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        <span>{{ __('shopper::modals.roles.new') }}</span>
        <p class="mt-1 sm:mt-0 sm:ml-3 text-sm leading-5 font-normal text-secondary-500 dark:text-secondary-400">
            {{ __('shopper::modals.roles.new_description') }}
        </p>
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <x-shopper::forms.group :label="__('shopper::modals.roles.labels.name')" for="name" class="sm:col-span-1" :error="$errors->first('name')" isRequired>
                <x-shopper::forms.input wire:model.defer="name" type="text" id="name" placeholder="manager" autocomplete="off" />
            </x-shopper::forms.group>
            <x-shopper::forms.group :label="__('shopper::layout.forms.label.display_name')" for="display_name" class="sm:col-span-1">
                <x-shopper::forms.input wire:model.defer="display_name" type="text" id="display_name" placeholder="Manager" autocomplete="off" />
            </x-shopper::forms.group>
            <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description" class="sm:col-span-2">
                <x-shopper::forms.textarea wire:model.defer="description" id="description" />
            </x-shopper::forms.group>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper::buttons.primary wire:click="save" type="button">
                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.cancel') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>

</x-shopper::modal>
