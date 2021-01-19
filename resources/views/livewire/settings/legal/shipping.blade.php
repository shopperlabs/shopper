<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Shipping policy") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-500">
                    {{ __("Define the shipping policy to which all users and consumers of the products in your store will be subject.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow bg-white rounded-md overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <div class="flex items-center justify-between">
                                <span class="flex-grow flex flex-col" id="toggleLabel">
                                    <span class="text-sm leading-5 font-medium text-gray-900">{{ __("Enabled") }}</span>
                                    <span class="text-sm leading-normal text-gray-500">{{ __("Setup page visibility for the customers.") }}</span>
                                </span>
                                <span role="checkbox" tabindex="0" @click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" aria-labelledby="toggleLabel" x-data="{ on: @entangle('isEnabled') }" x-bind:class="{ 'bg-gray-200': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue">
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <x-shopper-input.group label="Content" for="shipping-content">
                                <x-shopper-input.rich-text wire:model.lazy="content" id="shipping-content" :initial-value="$content" />
                            </x-shopper-input.group>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-md">
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper-button type="button" wire:click="store" wire:loading.attr="disabled">
                            <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            {{ __("Save") }}
                        </x-shopper-button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
