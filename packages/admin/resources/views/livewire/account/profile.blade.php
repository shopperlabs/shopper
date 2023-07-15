<div class="mt-8">
    <form wire:submit.prevent="save">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/auth.account.profile_title') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/auth.account.profile_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div>
                    <x-shopper::forms.group
                        for="picture"
                        :label="__('shopper::layout.forms.label.photo')"
                        :error="$errors->first('picture')"
                        noShadow
                    >
                        <x-shopper::forms.avatar-upload wire:model.defer="picture" id="picture">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-secondary-100 dark:bg-secondary-700">
                                    @if($picture)
                                        <img class="h-full w-full bg-cover" src="{{ $picture->temporaryUrl() }}" alt="">
                                    @else
                                        <img class="h-full w-full bg-cover" src="{{ $this->getUser()->picture }}" alt="{{ $this->getUser()->email }}">
                                    @endif
                                </span>
                        </x-shopper::forms.avatar-upload>
                    </x-shopper::forms.group>
                    <div class="grid gap-4 grid-cols-6 sm:gap-5 mt-5">
                        <x-shopper::forms.group
                            for="first_name"
                            class="col-span-6 sm:col-span-3"
                            :label="__('shopper::layout.forms.label.first_name')"
                            :error="$errors->first('first_name')"
                        >
                            <x-shopper::forms.input type='text' wire:model.defer='first_name' class="dark:bg-secondary-800 dark:border-transparent" autocomplete='off' id='first_name' />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group
                            for="last_name"
                            class="col-span-6 sm:col-span-3"
                            :label="__('shopper::layout.forms.label.last_name')"
                            :error="$errors->first('last_name')"
                        >
                            <x-shopper::forms.input type='text' wire:model.defer='last_name' class="dark:bg-secondary-800 dark:border-transparent" autocomplete='off' id='last_name' />
                        </x-shopper::forms.group>

                        <x-shopper::forms.group
                            for="email"
                            class="col-span-6 sm:col-span-3"
                            :label="__('shopper::layout.forms.label.email')"
                            :error="$errors->first('email')"
                        >
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-heroicon-s-mail class="h-5 w-5 text-secondary-400" />
                                </div>
                                <x-shopper::forms.input
                                    wire:model.defer='email'
                                    id='email'
                                    type='email'
                                    class='block pl-10 w-full sm:text-sm sm:leading-5 dark:bg-secondary-800 dark:border-transparent'
                                    autocomplete='email-address'
                                />
                            </div>
                        </x-shopper::forms.group>

                        <div wire:ignore x-data="internationalNumber('#phone_number')" class="col-span-6 sm:col-span-3">
                            <div class="flex items-center justify-between">
                                <x-shopper::label for="phone_number" :value="__('shopper::layout.forms.label.phone_number')" />
                                <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                    {{ __('shopper::layout.forms.label.optional') }}
                                </span>
                            </div>
                            <div class="mt-1 relative">
                                <x-shopper::forms.input
                                    wire:model.defer="phone_number"
                                    id="phone_number"
                                    type="tel"
                                    class="pr-10 dark:bg-secondary-800 dark:border-transparent"
                                    autocomplete="off"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 text-right">
                    <span class="inline-flex rounded-md">
                        <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                            {{ __('shopper::layout.forms.actions.save') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>

