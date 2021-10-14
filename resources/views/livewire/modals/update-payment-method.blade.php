<x-shopper-modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-100 dark:border-gray-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Update payment method') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 h-96 overflow-y-scroll hide-scroll">
            <div class="sm:col-span-2">
                <div>
                    <x-shopper-label value="{{ __('Provider Logo') }}" />
                    <div class="mt-2">
                        <x-shopper-input.avatar-upload id="logo" wire:model.lazy='logo'>
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($logo)
                                    <img class="h-full w-full bg-center" src="{{ $logo->temporaryUrl() }}" alt="">
                                @elseif($logoUrl)
                                    <img class="h-full w-full bg-center" src="{{ $logoUrl }}" alt="">
                                @else
                                    <span class="h-12 w-12 flex items-center justify-center">
                                        <x-heroicon-o-photograph class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                                    </span>
                                @endif
                            </span>
                        </x-shopper-input.avatar-upload>
                    </div>
                </div>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Custom payment method name" for="title" :error="$errors->first('title')" isRequired>
                    <x-shopper-input.text wire:model.lazy="title" id="title" type="text" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Payment Website Documentation" for="link_url">
                    <x-shopper-input.text wire:model.lazy="linkUrl" type="url" id="link_url" placeholder="https://doc.myprovider.com" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Additional details" for="description" helpText="Displays to customers when they’re choosing a payment method.">
                    <x-shopper-input.textarea wire:model.lazy="description" id="description" />
                </x-shopper-input.group>
            </div>
            <div class="sm:col-span-2">
                <x-shopper-input.group label="Payment instructions" for="instructions" helpText="Displays to customers after they place an order with this payment method.">
                    <x-shopper-input.textarea wire:model.lazy="instructions" id="instructions" />
                </x-shopper-input.group>
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="save" type="button">
                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                {{ __('Update') }}
            </x-shopper-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Cancel') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
