<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories.index')" :current=" __('shopper::pages/settings.location.add')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.inventories.index')" :title="__('shopper::words.locations')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading>
        <x-slot name="title">
            {{ __('shopper::pages/settings.location.add') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.location.detail') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.location.detail_summary') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-1">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" :error="$errors->first('name')">
                            <x-shopper::forms.input wire:model.defer="name" id="name" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" placeholder="White House" />
                        </x-shopper::forms.group>
                    </div>
                    <div class="sm:col-span-1">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.email')" for="email" :error="$errors->first('email')">
                            <x-shopper::forms.input wire:model.defer="email" id="email" type="email" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between">
                            <x-shopper::label :value="__('shopper::layout.forms.label.description')" for="description" />
                            <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::layout.forms.label.optional') }}
                            </span>
                        </div>
                        <div class="mt-1 relative shadow-sm rounded-md">
                            <x-shopper::forms.textarea wire:model.defer="description" class="dark:bg-secondary-800 dark:border-transparent" id="description" />
                        </div>
                    </div>
                </div>
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <x-shopper::forms.checkbox wire:model.defer="isDefault" id="isDefault" />
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <label for="isDefault" class="font-medium text-secondary-700 cursor-pointer dark:text-secondary-200">
                            {{ __('shopper::pages/settings.location.set_default') }}
                        </label>
                        <p class="text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/settings.location.set_default_summary') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.location.address') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.location.address_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-3xl">
                <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                    <div class="sm:col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.street_address')" for="street_address" :error="$errors->first('street_address')">
                            <x-shopper::forms.input wire:model.defer="street_address" id="street_address" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" placeholder="Akwa Avenue 34..." />
                        </x-shopper::forms.group>
                    </div>

                    <div class="sm:col-span-6">
                        <div class="flex items-center justify-between">
                            <x-shopper::label :value="__('shopper::layout.forms.label.street_address_plus')" for="street_address_plus" />
                            <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::layout.forms.label.optional') }}
                            </span>
                        </div>
                        <div class="mt-1 relative shadow-sm rounded-md">
                            <x-shopper::forms.input wire:model.defer="street_address_plus" id="street_address_plus" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <x-shopper::forms.group for="country_id" :label="__('shopper::layout.forms.label.country')" noShadow wire:ignore>
                            <select
                                wire:model.defer="country_id"
                                id="country_id"
                                x-data="{}"
                                x-init="function() { tomSelect($el, {}) }"
                                placeholder="{{ __('shopper::layout.forms.placeholder.select_country') }}"
                                autocomplete="off"
                            >
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </x-shopper::forms.group>
                    </div>

                    <div class="sm:col-span-3">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.city')" for="city" :error="$errors->first('city')">
                            <x-shopper::forms.input wire:model.defer="city" id="city" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>

                    <div class="sm:col-span-3">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.postal_code')" for="zipcode" :error="$errors->first('zipcode')">
                            <x-shopper::forms.input wire:model.defer="zipcode" id="zipcode" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>

                    <div wire:ignore x-data="internationalNumber('#phone_number')" class="sm:col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.phone_number')" for="phone_number" :error="$errors->first('phone_number')">
                            <x-shopper::forms.input wire:model.defer="phone_number" id="phone_number" type="tel" class="pr-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                            @error('phone_number')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-danger-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @enderror
                        </x-shopper::forms.group>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 pt-5 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>