<div>
    <x-shopper::breadcrumb back="shopper.customers.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.customers.index')" :title="__('shopper::layout.sidebar.customers')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ __('shopper::messages.actions_label.add_new', ['name' => 'customer']) }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/customers.overview') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/customers.overview_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 sm:p-6 shadow bg-white rounded-md dark:bg-secondary-800">
                    <div class="grid gap-4 grid-cols-6 sm:gap-5">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.first_name')" for="first_name" class="col-span-6 sm:col-span-3" :error="$errors->first('first_name')">
                            <x-shopper::forms.input wire:model.defer="first_name" id="first_name" type="text" autocomplete="off" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.last_name')" for="last_name" class="col-span-6 sm:col-span-3" :error="$errors->first('last_name')">
                            <x-shopper::forms.input wire:model.defer="last_name" id="last_name" type="text" autocomplete="off" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.email')" for="email" class="col-span-6" :error="$errors->first('email')">
                            <x-shopper::forms.input wire:model.defer="email" id="email" type="email" autocomplete="off" />
                        </x-shopper::forms.group>

                        <div wire:ignore x-data="internationalNumber('#phone')" class="col-span-6">
                            <div class="flex items-center justify-between">
                                <x-shopper::label for="phone" :value="__('shopper::layout.forms.label.phone_number')" />
                                <span class="text-secondary-500 text-sm leading-5 dark:text-secondary-400">{{ __('shopper::layout.forms.label.optional') }}</span>
                            </div>
                            <div class="mt-1 relative">
                                <x-shopper::forms.input wire:model.defer="phone_number" id="phone" type="tel" class="pr-10" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/customers.security_title') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:txt-secondary-400">
                        {{ __('shopper::pages/customers.security_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div x-data="{ show: false }" class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper::label for="password" :value="__('shopper::layout.forms.label.password')" />
                                    <div class="flex items-center divide-x divide-secondary-200 dark:divide-secondary-600">
                                        <button wire:click="generate" type="button" class="pr-2 text-primary-600 text-sm font-medium leading-5 hover:text-primary-500 dark:text-primary-500/50">
                                            {{ __('shopper::messages.generate') }}
                                        </button>
                                        <button
                                            @click="show = !show"
                                            x-text="show ? '{{ __('shopper::messages.hide') }}' : '{{ __('shopper::messages.show') }}'"
                                            type="button"
                                            class="pl-2 text-sm text-leading-5 text-primary-600 hover:text-primary-700 focus:outline-none focus:text-primary-700 hover:underline dark:text-primary-500/50">
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <x-shopper::forms.input wire:model.defer="password" id="password" ::type="show ? 'text' : 'password'" type="password" autocomplete="off" class="@error('password') pr-10 @enderror" />
                                    @error('password')
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
                                        </div>
                                    @enderror
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-shopper::forms.group class="sm:col-span-6" :label="__('shopper::layout.forms.label.confirm_password')" for="password_confirmation" :error="$errors->first('password_confirmation')">
                                <x-shopper::forms.input wire:model.defer="password_confirmation" id="password_confirmation" type="password" autocomplete="off" />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/customers.address_title') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/customers.address_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md dark:bg-secondary-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <x-shopper::forms.group class="col-span-6 sm:col-span-3" :label="__('shopper::layout.forms.label.first_name')" for="address_first_name" :error="$errors->first('address_first_name')">
                                <x-shopper::forms.input wire:model.defer="address_first_name" id="address_first_name" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6 sm:col-span-3" :label="__('shopper::layout.forms.label.last_name')" for="address_last_name" :error="$errors->first('address_last_name')">
                                <x-shopper::forms.input wire:model="address_last_name" id="address_last_name" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6" :label="__('shopper::layout.forms.label.company_name')" for="company_name" optional>
                                <x-shopper::forms.input wire:model.defer="company_name" id="company_name" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6" :label="__('shopper::layout.forms.label.street_address')" for="street_address" :error="$errors->first('street_address')">
                                <x-shopper::forms.input wire:model.defer="street_address" id="street_address" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6" :label="__('shopper::layout.forms.label.street_address_plus')" for="street_address_plus" optional>
                                <x-shopper::forms.input wire:model.defer="street_address_plus" id="street_address_plus" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6 sm:col-span-4" for="country_id" :label="__('shopper::layout.forms.label.country')" :error="$errors->first('country_id')" wire:ignore>
                                <select
                                    wire:model.defer="country_id"
                                    id="country_id"
                                    x-data="{}"
                                    x-init="function() { tomSelect($el, {}) }"
                                    data-placeholder="{{ __('shopper::layout.forms.label.country') }}"
                                    autocomplete="off"
                                >
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </x-shopper::forms.group>

                            <x-shopper::forms.group class="col-span-6 sm:col-span-3" :label="__('shopper::layout.forms.label.city')" for="city" :error="$errors->first('city')">
                                <x-shopper::forms.input wire:model.defer="city" id="city" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.postal_code')" for="zipcode" class="col-span-6 sm:col-span-3" :error="$errors->first('zipcode')">
                                <x-shopper::forms.input wire:model.defer="zipcode" id="zipcode" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <div wire:ignore x-data="internationalNumber('#phone_number')" class="col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper::label for="phone_number" :value="__('shopper::layout.forms.label.phone_number')" />
                                </div>
                                <div class="mt-1 relative">
                                    <x-shopper::forms.input wire:model.defer="address_phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/customers.notification_title') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/customers.notification_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-shopper::forms.checkbox wire:model.defer="opt_in" id="opt_in" />
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <x-shopper::label for="opt_in" :value="__('shopper::pages/customers.marketing_email')" />
                                    <p class="text-sm mt-1 leading-5 text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/customers.marketing_description') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-shopper::forms.checkbox wire:model.defer="send_mail" id="send_mail" />
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <x-shopper::label for="send_mail" :value="__('shopper::pages/customers.send_credentials')" />
                                    <p class="text-sm mt-1 leading-5 text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/customers.credential_description') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-5 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>

</div>

