<div class="flex flex-col justify-between space-y-10 lg:space-y-20">
    @include('shopper::livewire.components.initialization.steps')

    <form wire:submit="save" class="flex-1">
        <div>
            <div>
                <div class="flex items-center space-x-4">
                    <x-untitledui-heading-02
                        class="h-6 w-6 text-secondary-400 dark:text-secondary-500"
                        aria-hidden="true"
                        stroke-width="1"
                    />
                    <span class="text-xs font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_1') }}
                    </span>
                </div>
                <div class="mt-3">
                    <h2 class="text-2xl font-heading font-medium text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.tell_about') }}
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-secondary-500 lg:max-w-2xl dark:text-secondary-300">
                        {{ __('shopper::pages/settings.initialization.step_1_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-6">
                <x-shopper::forms.group
                    :label="__('shopper::layout.forms.label.store_name')"
                    for="name"
                    :error="$errors->first('name')"
                    isRequired
                >
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-untitledui-shop
                                class="w-5 h-5 text-secondary-400 dark:text-secondary-500"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                        </div>
                        <x-shopper::forms.input
                            id="name"
                            type="text"
                            wire:model="name"
                            class="pl-10"
                            autocomplete="name"
                            required="true"
                            autofocus
                        />
                    </div>
                </x-shopper::forms.group>
                <x-shopper::forms.group
                    :label="__('shopper::layout.forms.label.email')"
                    for="email"
                    :error="$errors->first('email')"
                    isRequired
                >
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-untitledui-mail
                                class="w-5 h-5 text-secondary-400 dark:text-secondary-500"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                        </div>
                        <x-shopper::forms.input
                            wire:model="email"
                            id="email"
                            type="email"
                            class="pl-10"
                            required="true"
                            placeholder="your@store.io"
                            autocomplete="e-mail"
                        />
                    </div>
                </x-shopper::forms.group>
                <div class="col-span-2">
                    <x-shopper::label for="country" is-required>
                        {{ __('shopper::layout.forms.label.country') }}
                    </x-shopper::label>
                    <div class="relative mt-1">
                        <div class="rounded-lg" wire:ignore>
                            <select
                                wire:model="country_id"
                                id="country_id"
                                x-data="{
                                    init() {
                                        tomSelect('#country_id', {})
                                    }
                                }"
                            >
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('country_id')
                        <p class="mt-1 text-sm text-danger-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="col-span-2">
                    <x-shopper::label for="currency" :value="__('shopper::layout.forms.label.currency')" />
                    <div class="mt-1">
                        <div class="rounded-lg" wire:ignore>
                            <select
                                wire:model="currency_id"
                                id="currency"
                                x-data="{
                                    init() {
                                        tomSelect('#currency', {})
                                    }
                                }"
                            >
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">
                                        {{ $currency->name . ' (' . $currency->code . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/settings.currency_description') }}
                        </p>
                    </div>
                </div>
                <div class="col-span-2">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.about')" for="about">
                        <x-shopper::forms.textarea id="about" wire:model="about" />
                    </x-shopper::forms.group>
                    <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.about_description') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-10 border-t border-dashed border-secondary-200 dark:border-secondary-700">
            <div class="flex justify-end">
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader
                        wire:loading
                        wire:target="save"
                        class="text-white"
                        aria-hidden="true"
                    />
                    {{ __('shopper::layout.forms.actions.next') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>
</div>
