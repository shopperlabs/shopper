<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/auth.account.password_title') }}</h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/auth.account.password_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    @if (session()->has('error'))
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-heroicon-s-x-circle class="h-5 w-5 text-red-400" />
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
                        <x-shopper::forms.group label="shopper::layout.forms.label.current_password" for="current_password" class="sm:col-span-6" :error="$errors->first('current_password')">
                            <x-shopper::forms.input wire:model.defer="current_password" id="current_password" type="password" autocomplete="off" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group label="shopper::layout.forms.label.new_password" for="password" class="sm:col-span-6" :error="$errors->first('password')" helpText="shopper::pages/auth.account.password_helper_validation">
                            <x-shopper::forms.input wire:model.defer="password" id="password" type="password" autocomplete="off" />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group label="shopper::layout.forms.label.confirm_password" for="password_confirmation" class="sm:col-span-6" :error="$errors->first('password_confirmation')">
                            <x-shopper::forms.input wire:model.defer="password_confirmation" id="password_confirmation" type="password" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 text-right">
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper::buttons.primary wire:click="save" wire:loading.attr="disabled" type="button">
                            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                            {{ __('shopper::layout.forms.actions.update') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
