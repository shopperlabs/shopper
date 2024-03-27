<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-gray-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        <span class="flex flex-col">
            {{ __('shopper::modals.permissions.new') }}
            <span class="mt-0.5 font-normal text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('shopper::modals.permissions.new_description') }}
            </span>
        </span>
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
            <x-shopper::forms.group :label="__('shopper::layout.forms.label.group_name')" for="group" class="sm:col-span-2">
                <x-shopper::forms.select id="group" wire:model.defer="group">
                    <option>{{ __('shopper::words.no_group') }}</option>
                    @foreach($groups as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </x-shopper::forms.select>
            </x-shopper::forms.group>
            <x-shopper::forms.group
                for="permission_name"
                :label="__('shopper::modals.permissions.labels.name')"
                :error="$errors->first('name')"
                isRequired
            >
                <x-shopper::forms.input
                    wire:model="name"
                    type="text"
                    id="permission_name"
                    placeholder="create_post, manage_articles, etc"
                />
            </x-shopper::forms.group>
            <x-shopper::forms.group
                :label="__('shopper::layout.forms.label.display_name')"
                for="permission_display_name"
                :error="$errors->first('display_name')"
                isRequired
            >
                <x-shopper::forms.input
                    wire:model="display_name"
                    type="text"
                    id="permission_display_name"
                    placeholder="Create Blog posts"
                />
            </x-shopper::forms.group>
            <x-shopper::forms.group
                for="permission_description"
                class="sm:col-span-2"
                :label="__('shopper::layout.forms.label.description')"
            >
                <x-shopper::forms.textarea wire:model.defer="description" id="permission_description" />
            </x-shopper::forms.group>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <x-shopper::buttons.primary wire:click="save" type="button" class="sm:ml-3 sm:w-auto">
            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
            {{ __('shopper::layout.forms.actions.save') }}
        </x-shopper::buttons.primary>
        <x-shopper::buttons.default wire:click="$dispatch('closeModal')" type="button" class="mt-3 sm:mt-0 sm:w-auto">
            {{ __('shopper::layout.forms.actions.cancel') }}
        </x-shopper::buttons.default>
    </x-slot>
</x-shopper::modal>
