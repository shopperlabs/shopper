<div>

    <div>
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("API Keys") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Enable Github API to publish issue to the shopperlabs/framework package.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div class="sm:col-span-3">
                                <x-shopper-input.group label="Public key" for="public_key">
                                    <input wire:model="github_key" id="public_key" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div x-data="{ show: false }" class="sm:col-span-3">
                                <div class="flex items-center justify-between">
                                    <label for="secret_key" class="block text-sm font-medium leading-5 text-gray-700">
                                        {{ __("Secret Key") }}
                                    </label>
                                    <div class="flex items-center divide-x divide-gray-200">
                                        <button
                                            @click="show = !show"
                                            x-text="show ? '{{ __("Hide") }}' : '{{ __("Show") }}'"
                                            type="button"
                                            class="pl-2 text-sm text-leading-5 text-blue-600 hover:text-blue-500 focus:outline-none focus:text-blue-700 hover:underline">
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="github_secret" id="secret_key" :type="show ? 'text' : 'password'" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out" />
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <x-shopper-input.group label="Webhook URL" for="webhook_url">
                                    <input wire:model="github_webhook_url" id="webhook_url" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                                <p class="mt-2 text-gray-500 text-sm leading-5">
                                    {{ __("Learn more about webhooks") }} <a href="https://docs.github.com/webhooks" target="_blank" class="text-blue-600 hover:text-blue-500">https://docs.github.com/webhooks</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Update API Keys") }}
            </x-shopper-button>
        </div>
    </div>

</div>
