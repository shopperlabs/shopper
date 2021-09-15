@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.settings.users">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.users')" title="Users & roles" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Add Administrator') }}
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 pb-10">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Login information') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('This information will be useful for the administrator to connect to the administration of Shopper.') }}
            </p>
        </div>

        <div class="mt-5 px-4 py-5 sm:px-6 bg-white rounded-md shadow-md overflow-hidden dark:bg-gray-800">
            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                <x-shopper-label for="email" class="sm:mt-px sm:pt-2">
                    {{ __('Email address') }} <span class="text-red-500">*</span>
                </x-shopper-label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg relative rounded-md shadow-sm">
                        <x-shopper-input.text wire:model.lazy="email" id="email" type="email" autocomplete="off" />
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start mt-6 sm:mt-5 sm:pt-5 sm:border-t sm:border-gray-200 sm:dark:border-gray-700">
                <x-shopper-label for="password" class="sm:mt-px sm:pt-2">
                    {{ __("Password") }} <span class="text-red-500">*</span>
                </x-shopper-label>
                <div x-data="{ show: false }" class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="flex items-center justify-between max-w-lg">
                        <button wire:click="generate" type="button" class="text-sm font-medium leading-5 text-blue-500 hover:text-blue-400">
                            {{ __('Generate') }}
                        </button>
                        <button
                          @click="show = !show"
                          x-text="show ? '{{ __('Hide') }}' : '{{ __('Show') }}'"
                          type="button"
                          class="text-sm text-leading-5 text-blue-600 hover:text-blue-500 focus:outline-none focus:text-blue-700 hover:underline">
                        </button>
                    </div>
                    <div class="mt-2 max-w-lg relative rounded-md shadow-sm">
                        <input wire:model.lazy="password" id="password" :type="show ? 'text' : 'password'" autocomplete="off" class="block w-full dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 rounded-md shadow-sm border-gray-300 dark:border-gray-700 focus:border-blue-300 focus:ring focus:ring-blue-300 dark:focus:ring-offset-gray-900 focus:ring-opacity-50 sm:text-sm @error('password') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" />
                        @error('password')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
                            </div>
                        @enderror
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6 sm:mt-5 sm:pt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:dark:border-gray-700">
                <label for="about" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2 dark:text-gray-300">
                    {{ __('Invitation') }}
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="send_mail" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-data="{ on: false }" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors  dark:bg-gray-700">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="send_mail" :value="__('Send Invite')" />
                            <p class="max-w-lg text-sm text-gray-500 dark:text-gray-400">{{ __('Send an invitation to this administrator by email with his login information.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Personal Information') }}
            </h3>
            <p class="max-w-2xl mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('Information related to the admin profile.') }}
            </p>
        </div>

        <div class="mt-5 px-4 py-5 sm:px-6 bg-white rounded-md shadow-md overflow-hidden dark:bg-gray-800">
            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                <x-shopper-label for="first_name" class="sm:mt-px sm:pt-2">
                    {{ __('First name') }} <span class="text-red-500">*</span>
                </x-shopper-label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <x-shopper-input.text wire:model.lazy="first_name" type="text" id="first_name" autocomplete="off" />
                    </div>
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:pt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:dark:border-gray-700">
                <x-shopper-label for="last_name" class="sm:mt-px sm:pt-2">
                    {{ __('Last name') }} <span class="text-red-500">*</span>
                </x-shopper-label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <x-shopper-input.text wire:model="last_name" type="text" id="last_name" autocomplete="off" />
                    </div>
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 sm:mt-5 sm:pt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:dark:border-gray-700">
                <x-shopper-label for="gender" class="sm:mt-px sm:pt-2">
                    {{ __('Gender') }}
                </x-shopper-label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <x-shopper-input.select wire:model.lazy="gender" id="gender">
                            <option value="male">{{ __('Male') }}</option>
                            <option value="female">{{ __('Female') }}</option>
                        </x-shopper-input.select>
                    </div>
                </div>
            </div>

            <div
                x-data
                wire:ignore
                x-init="
                    phoneNumber = document.querySelector('#phone_number');
                    iti = intlTelInput(document.querySelector('#phone_number'), {
                        nationalMode: true,
                        initialCountry: 'auto',
                        geoIpLookup: function(success, failure) {
                            $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                                var countryCode = (resp && resp.country) ? resp.country : 'CM';
                                success(countryCode);
                            });
                        },
                        utilsScript: 'https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js'
                    });
                    var handleChange = () => {
                        if (iti.isValidNumber()) {
                            phoneNumber.value = iti.getNumber();
                        }
                      };
                    phoneNumber.addEventListener('change', handleChange);
                    phoneNumber.addEventListener('keyup', handleChange);
               "
                class="mt-6 sm:mt-5 sm:pt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:dark:border-gray-700"
            >
                <x-shopper-label for="phone_number" class="sm:mt-px sm:pt-2">
                    {{ __('Phone number') }}
                </x-shopper-label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                    <div class="max-w-lg rounded-md shadow-sm">
                        <x-shopper-input.text wire:model.lazy="phone_number" type="tel" id="phone_number" />
                        @error('phone_number')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
                            </div>
                        @enderror
                    </div>
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Role Information') }}
            </h3>
            <p class="max-w-2xl mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('Assign roles to this administrator who will limit the actions he can do.') }}
            </p>
        </div>

        <div class="mt-5 px-4 py-5 sm:px-6 bg-white rounded-md shadow-md overflow-hidden dark:bg-gray-800">
            <div>
                <div role="group" aria-labelledby="roles-lists">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                        <div>
                            <div class="text-base leading-6 font-medium text-gray-900 sm:text-sm sm:leading-5 dark:text-white" id="roles-lists">
                                {{ __('Roles') }}
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <div class="max-w-lg">
                                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('Choose a role for this admin') }}</p>
                                <div class="mt-4 space-y-4">
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <x-shopper-input.radio wire:model.lazy="role_id" id="role_{{ $role->id }}" name="role_id" value="{{ $role->id }}" />
                                            <label for="role_{{ $role->id }}" class="ml-3 cursor-pointer">
                                                <span class="block text-sm leading-5 font-medium text-gray-700 dark:text-gray-400">{{ $role->display_name ?? $role->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('role_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if($is_admin)
                                    <div class="rounded-md bg-yellow-50 p-4 mt-6">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <x-heroicon-s-exclamation class="h-5 w-5 text-yellow-400" />
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                                    {{ __('Attention needed') }}
                                                </h3>
                                                <div class="mt-2 text-sm leading-5 text-yellow-700">
                                                    <p>
                                                        {{ __('This role gives this administrator the same rights and permissions as you.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-end">
                <span class="inline-flex rounded-md shadow-sm">
                    <x-shopper-default-button :link="route('shopper.settings.users')">
                        {{ __('Cancel') }}
                    </x-shopper-default-button>
                </span>
                <span class="ml-3 inline-flex rounded-md shadow-sm">
                    <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                        <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                        {{ __('Save and Continue') }}
                    </x-shopper-button>
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
@endpush
