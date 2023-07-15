<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.users')" :current="__('shopper::pages/settings.roles_permissions.add_admin')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.users')" :title="__('Staff & permissions')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ __('shopper::pages/settings.roles_permissions.add_admin') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.roles_permissions.login_information') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.roles_permissions.login_information_summary') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-2xl">
            <div class="space-y-6">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.email')" for="email" isRequired :error="$errors->first('email')">
                    <x-shopper::forms.input wire:model.defer="email" id="email" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                </x-shopper::forms.group>
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between">
                        <x-shopper::label for="password" isRequired>
                            {{ __('shopper::layout.forms.label.password') }}
                        </x-shopper::label>
                        <div class="flex items-center divide-x divide-primary-500">
                            <button wire:click="generate" type="button" class="text-sm font-medium pr-2 leading-5 text-primary-500 hover:text-primary-600">
                                {{ __('shopper::words.generate') }}
                            </button>
                            <button
                                @click="show = !show"
                                x-text="show ? '{{ __('shopper::words.hide') }}' : '{{ __('shopper::words.show') }}'"
                                type="button"
                                class="text-sm text-leading-5 text-primary-500 hover:text-primary-600 pl-2 focus:outline-none">
                            </button>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="mt-2 relative">
                            <x-shopper::forms.input wire:model.defer="password" id="password" ::type="show ? 'text' : 'password'" type="password" autocomplete="off" class="dark:bg-secondary-800 dark:border-transparent @error('password') pr-10 @enderror" />
                            @error('password')
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <x-heroicon-s-exclamation-circle class="h-5 w-5 text-danger-500" />
                                </div>
                            @enderror
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <x-shopper::label for="send_mail">
                        {{ __('shopper::words.invitation') }}
                    </x-shopper::label>
                    <div class="mt-2">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                    <span wire:model.defer="send_mail" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-data="{ on: false }" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors  dark:bg-secondary-700">
                                        <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                        <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform"></span>
                                    </span>
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="send_mail" :value="__('shopper::pages/settings.roles_permissions.send_invite')" />
                                <p class="max-w-lg text-sm text-secondary-500 dark:text-secondary-400">
                                    {{ __('shopper::pages/settings.roles_permissions.send_invite_summary') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.roles_permissions.personal_information') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.roles_permissions.personal_information_summary') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2 max-w-2xl">
            <div class="space-y-6">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.first_name')" for="first_name" isRequired :error="$errors->first('first_name')">
                    <x-shopper::forms.input wire:model.defer="first_name" id="first_name" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                </x-shopper::forms.group>
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.last_name')" for="last_name" isRequired :error="$errors->first('last_name')">
                    <x-shopper::forms.input wire:model.defer="last_name" id="last_name" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                </x-shopper::forms.group>
                <div>
                    <x-shopper::label for="gender">
                        {{ __('shopper::layout.forms.label.gender') }}
                    </x-shopper::label>
                    <div class="max-w-lg rounded-md shadow-sm mt-1">
                        <x-shopper::forms.select wire:model.defer="gender" id="gender" class="dark:bg-secondary-800 dark:border-transparent">
                            <option value="male">{{ __('shopper::words.male') }}</option>
                            <option value="female">{{ __('shopper::words.female') }}</option>
                        </x-shopper::forms.select>
                    </div>
                </div>
                <div wire:ignore x-data="internationalNumber('#phone_number')">
                    <x-shopper::label for="phone_number" class="sm:mt-px sm:pt-2">
                        {{ __('shopper::layout.forms.label.phone_number') }}
                    </x-shopper::label>
                    <div class="max-w-lg rounded-md shadow-sm mt-1">
                        <x-shopper::forms.input wire:model.defer="phone_number" type="tel" id="phone_number" class="dark:bg-secondary-800 dark:border-transparent" />
                        @error('phone_number')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-heroicon-s-exclamation-circle class="h-5 w-5 text-danger-500" />
                            </div>
                        @enderror
                    </div>
                    @error('phone_number')
                        <p class="mt-2 text-sm text-danger-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.roles_permissions.role_information') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.roles_permissions.personal_information_summary') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2">
            <div class="max-w-lg">
                <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.roles_permissions.choose_role') }}
                </p>
                <div class="mt-4 space-y-4">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <x-shopper::forms.radio wire:model.lazy="role_id" id="role_{{ $role->id }}" name="role_id" value="{{ $role->id }}" />
                            <label for="role_{{ $role->id }}" class="ml-3 cursor-pointer">
                                <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-400">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('role_id')
                    <p class="mt-2 text-sm text-danger-500">{{ $message }}</p>
                @enderror

                @if($is_admin)
                    <div class="rounded-md bg-yellow-50 p-4 mt-6">
                        <div class="flex">
                            <div class="shrink-0">
                                <x-heroicon-s-exclamation class="h-5 w-5 text-yellow-400" />
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                    {{ __('shopper::words.attention_needed') }}
                                </h3>
                                <p class="mt-2 text-sm leading-5 text-yellow-700">
                                    {{ __('shopper::words.attention_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 pt-5 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <span class="inline-flex rounded-md shadow-sm">
                <x-shopper::buttons.default :link="route('shopper.settings.users')">
                    {{ __('shopper::layout.forms.actions.cancel') }}
                </x-shopper::buttons.default>
            </span>
            <span class="ml-3 inline-flex rounded-md shadow-sm">
                <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.save') }}
                </x-shopper::buttons.primary>
            </span>
        </div>
    </div>
</x-shopper::container>
