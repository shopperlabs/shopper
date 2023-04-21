<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Add new payment method') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 h-96 overflow-y-scroll hide-scroll">
            <div class="sm:col-span-2">
                <div>
                    <x-shopper::label value="{{ __('Provider Logo') }}" />
                    <div class="mt-2">
                        <x-shopper::forms.avatar-upload id="logo" wire:model.lazy='logo'>
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                                @if($logo)
                                    <img class="h-full w-full bg-center" src="{{ $logo->temporaryUrl() }}" alt="">
                                @else
                                    <span class="h-12 w-12 flex items-center justify-center">
                                        <x-heroicon-o-photograph class="w-8 h-8 text-secondary-400 dark:text-secondary-500" />
                                    </span>
                                @endif
                            </span>
                        </x-shopper::forms.avatar-upload>
                    </div>
                </div>
            </div>
            <div class="sm:col-span-2">
                <x-shopper::forms.group
                    for="title"
                    :label="__('Custom payment method name')"
                    :error="$errors->first('title')"
                    isRequired
                >
                    <x-shopper::forms.input wire:model.defer="title" id="title" type="text" />
                </x-shopper::forms.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper::forms.group :label="__('Payment Website Documentation')" for="link_url">
                    <x-shopper::forms.input wire:model.defer="linkUrl" type="url" id="link_url" placeholder="https://doc.myprovider.com" />
                </x-shopper::forms.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper::forms.group
                    for="description"
                    :label="__('Additional details')"
                    :helpText="__('Displays to customers when they’re choosing a payment method.')"
                >
                    <x-shopper::forms.textarea wire:model.defer="description" id="description" />
                </x-shopper::forms.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper::forms.group
                    for="instructions"
                    :label="__('Payment instructions')"
                    :helpText="__('Displays to customers after they place an order with this payment method.')"
                >
                    <x-shopper::forms.textarea wire:model.defer="instructions" id="instructions" />
                </x-shopper::forms.group>
            </div>
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
