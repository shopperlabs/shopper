<div class="mt-6">
    <form wire:submit.prevent="save">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Profile Information') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __("Update your account's profile information and email address.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-md">
                    <div class="px-4 py-5 sm:p-6">
                        <x-shopper-input.group label="Photo" for="picture" :error="$errors->first('picture')" noShadow>
                            <x-shopper-input.avatar-upload wire:model="picture" id="picture">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if($picture)
                                        <img class="h-full w-full bg-cover" src="{{ $picture->temporaryUrl() }}" alt="">
                                    @else
                                        <img class="h-full w-full bg-cover" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}">
                                    @endif
                                </span>
                            </x-shopper-input.avatar-upload>
                        </x-shopper-input.group>
                        <div class="grid gap-4 grid-cols-6 sm:gap-5 mt-5">
                            <x-shopper-input.group for="first_name" label="First name" class="col-span-6 sm:col-span-3" :error="$errors->first('first_name')">
                                <x-shopper-input.text type='text' wire:model.lazy='first_name' autocomplete='off' id='first_name' />
                            </x-shopper-input.group>

                            <x-shopper-input.group for="last_name" label="Last name" class="col-span-6 sm:col-span-3" :error="$errors->first('last_name')">
                                <x-shopper-input.text type='text' wire:model.lazy='last_name' autocomplete='off' id='last_name' />
                            </x-shopper-input.group>

                            <x-shopper-input.group label="Email address" for="email" class="col-span-6 sm:col-span-3" :error="$errors->first('email')">
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-heroicon-s-mail class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <x-shopper-input.text wire:model='email' id='email' type='email' class='form-input block pl-10 w-full sm:text-sm sm:leading-5' autocomplete='email-address' />
                                </div>
                            </x-shopper-input.group>

                            <div wire:ignore x-data="internationalNumber('#phone_number')" class="col-span-6 sm:col-span-3">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label for="phone_number" :value="__('Phone number')" />
                                    <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('Optional') }}</span>
                                </div>
                                <div class="mt-1 relative">
                                    <x-shopper-input.text wire:model="phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 sm:px-6 text-right">
                        <span class="inline-flex rounded-md shadow-sm">
                            <x-shopper-button type="submit" wire:loading.attr="disabled">
                                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                                {{ __('Save') }}
                            </x-shopper-button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

