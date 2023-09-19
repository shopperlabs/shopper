<div>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.inventories.index')" :title="__('shopper::words.locations')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ $inventory->name }}
        </x-slot>

        <x-slot name="action">
            <div class="flex">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.location.detail') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.location.detail_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="p-4 sm:p-5 bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                    <div class="space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" class="sm:col-span-1" :error="$errors->first('name')">
                                <x-shopper::forms.input wire:model.debounce.550ms="name" id="name" type="text" autocomplete="off" placeholder="White House" />
                            </x-shopper::forms.group>
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.email')" for="email" class="sm:col-span-1" :error="$errors->first('email')">
                                <x-shopper::forms.input wire:model.debounce.550ms="email" id="email" type="email" autocomplete="off" />
                            </x-shopper::forms.group>
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <x-shopper::label :value="__('shopper::layout.forms.label.description')" for="description" />
                                    <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::layout.forms.label.optional') }}
                                    </span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper::forms.textarea wire:model.lazy="description" id="description" />
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
                        @if($inventory->is_default)
                            <div class="rounded-md bg-primary-50 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <x-heroicon-s-information-circle class="h-5 w-5 text-primary-400" />
                                    </div>
                                    <div class="ml-3 flex-1 md:flex md:justify-between">
                                        <p class="text-sm leading-5 text-primary-700">
                                            {{ __('shopper::pages/settings.location.is_default') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.location.address') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.location.address_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md dark:bg-secondary-800">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.street_address')" for="street_address" class="sm:col-span-6" :error="$errors->first('street_address')">
                                <x-shopper::forms.input wire:model="street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                            </x-shopper::forms.group>

                            <div class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper::label :value="__('shopper::layout.forms.label.street_address_plus')" for="street_address_plus" />
                                    <span class="ml-4 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                                        {{ __('shopper::layout.forms.label.optional') }}
                                    </span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper::forms.input wire:model="street_address_plus" id="street_address_plus" type="text" autocomplete="off" />
                                </div>
                            </div>
                            <x-shopper::forms.group for="country_id" :label="__('shopper::layout.forms.label.country')" class="sm:col-span-6" noShadow>
                                <select
                                    wire:model.defer="country_id"
                                    id="country_id"
                                    x-data="{}"
                                    x-init="function() { tomSelect($el, {}) }"
                                    placeholder="{{ __('shopper::layout.forms.placeholder.select_country') }}"
                                    autocomplete="off"
                                >
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @selected($country->id === $country_id)>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </x-shopper::forms.group>

                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.city')" for="city" class="sm:col-span-3" :error="$errors->first('city')">
                                <x-shopper::forms.input wire:model="city" id="city" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.postal_code')" for="zipcode" class="sm:col-span-3" :error="$errors->first('zipcode')">
                                <x-shopper::forms.input wire:model="zipcode" id="zipcode" type="text" autocomplete="off" />
                            </x-shopper::forms.group>

                            <div
                                wire:ignore
                                x-data="internationalNumber('#phone_number')"
                                class="sm:col-span-6"
                            >
                                <x-shopper::forms.group :label="__('shopper::layout.forms.label.phone_number')" for="phone_number" :error="$errors->first('phone_number')">
                                    <x-shopper::forms.input wire:model.defer="phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
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
        </div>
    </div>

    <div class="mt-6 pt-5 pb-10 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex items-center justify-between space-x-4">
            @can('delete_inventories')
                <span class="w-full sm:w-auto">
                    <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-inventory', {{ json_encode([$inventoryId, $name]) }})" type="button">
                        <x-heroicon-o-trash class="w-5 h-5 -ml-1 mr-2" />
                        {{ __('shopper::layout.status.delete') }}
                    </x-shopper::buttons.danger>
                </span>
            @endcan
            <span class="ml-auto flex justify-end">
                <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </span>
        </div>
    </div>
</div>
