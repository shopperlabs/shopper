<div>

    <div>
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900">{{ __("API Keys") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-600">
                        {{ __("Enable Github API to publish issue to the shopperlabs/framework package.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div class="sm:col-span-3">
                                <x-shopper::forms.group label="Public key" for="public_key">
                                    <input wire:model="github_key" id="public_key" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper::forms.group>
                            </div>

                            <div x-data="{ show: false }" class="sm:col-span-3">
                                <div class="flex items-center justify-between">
                                    <label for="secret_key" class="block text-sm font-medium leading-5 text-secondary-700">
                                        {{ __("Secret Key") }}
                                    </label>
                                    <div class="flex items-center divide-x divide-secondary-200">
                                        <button
                                            @click="show = !show"
                                            x-text="show ? '{{ __("Hide") }}' : '{{ __("Show") }}'"
                                            type="button"
                                            class="pl-2 text-sm text-leading-5 text-primary-600 hover:text-primary-500 focus:outline-none focus:text-primary-700 hover:underline">
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="github_secret" id="secret_key" :type="show ? 'text' : 'password'" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out" />
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <x-shopper::forms.group label="Webhook URL" for="webhook_url">
                                    <input wire:model="github_webhook_url" id="webhook_url" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper::forms.group>
                                <p class="mt-2 text-secondary-500 text-sm leading-5">
                                    {{ __("Learn more about webhooks") }} <a href="https://docs.github.com/webhooks" target="_blank" class="text-primary-600 hover:text-primary-500">https://docs.github.com/webhooks</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 pt-5">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __("Update API Keys") }}
            </x-shopper::buttons.primary>
        </div>
    </div>

</div>
