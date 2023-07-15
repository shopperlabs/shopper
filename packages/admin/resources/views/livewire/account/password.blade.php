<div class="mt-10 sm:mt-0">
    <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/auth.account.password_title') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/auth.account.password_description') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2">
            <div class="space-y-4">
                @if (session()->has('error'))
                    <div class="rounded-md bg-danger-50 p-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <x-heroicon-s-x-circle class="h-5 w-5 text-danger-400" />
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium text-danger-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.current_password')" for="current_password" class="sm:col-span-6" :error="$errors->first('current_password')">
                        <x-shopper::forms.input wire:model.defer="current_password" id="current_password" type="password" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group
                        :label="__('shopper::layout.forms.label.new_password')"
                        for="password"
                        class="sm:col-span-6"
                        :error="$errors->first('password')"
                        :helpText="__('shopper::pages/auth.account.password_helper_validation')"
                    >
                        <x-shopper::forms.input wire:model.defer="password" id="password" type="password" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.confirm_password')" for="password_confirmation" class="sm:col-span-6" :error="$errors->first('password_confirmation')">
                        <x-shopper::forms.input wire:model.defer="password_confirmation" id="password_confirmation" type="password" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="mt-5 text-right">
                <span class="inline-flex rounded-md">
                    <x-shopper::buttons.primary wire:click="save" wire:loading.attr="disabled" type="button">
                        <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                        {{ __('shopper::layout.forms.actions.update') }}
                    </x-shopper::buttons.primary>
                </span>
            </div>
        </div>
    </div>
</div>
