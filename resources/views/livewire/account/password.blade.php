<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Update Password") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("Ensure your account is using a long, random password to stay secure.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                    @if (session()->has('error'))
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 font-medium text-red-800">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                        <div class="sm:col-span-6">
                            <x-shopper-input.group label="Current Password" for="current_password" :error="$errors->first('current_password')">
                                <x-shopper-input.text wire:model="current_password" id="current_password" type="password" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div class="sm:col-span-6">
                            <x-shopper-input.group label="New Password" for="password" :error="$errors->first('password')" helpText="Your password must be more than 8 characters long and must contain at least 1 uppercase, 1 lowercase and 1 digit.">
                                <x-shopper-input.text wire:model="password" id="password" type="password" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div class="sm:col-span-6">
                            <x-shopper-input.group label="Confirm Password" for="password_confirmation" :error="$errors->first('password_confirmation')">
                                <x-shopper-input.text wire:model="password_confirmation" id="password_confirmation" type="password" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper-button wire:click="save" wire:loading.attr="disabled" type="button">
                            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            {{ __("Update Password") }}
                        </x-shopper-button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
