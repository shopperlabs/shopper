<div class="flex flex-col justify-between space-y-10 lg:space-y-20">
    @include('shopper::livewire.components.initialization.steps')

    <form wire:submit="save" class="flex-1">
        <div>
            <div>
                <div class="flex items-center space-x-4">
                    <x-untitledui-share-07
                        class="h-6 w-6 text-gray-400 dark:text-gray-500"
                        aria-hidden="true"
                        stroke-width="1"
                    />
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_3') }}
                    </span>
                </div>
                <div class="mt-3">
                    <h2 class="font-heading text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.social_description') }}
                        <span class="font-normal text-gray-500 dark:text-gray-400">
                            ({{ __('shopper::layout.forms.label.optional') }})
                        </span>
                    </h2>
                    <p class="mt-4 text-sm leading-6 text-gray-500 dark:text-gray-300 lg:max-w-2xl">
                        {{ __('shopper::pages/settings.initialization.step_3_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-10 space-y-6">
                <x-shopper::forms.group
                    class="col-span-6 lg:col-span-2"
                    :label="__('shopper::words.socials.facebook')"
                    for="facebook"
                >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-shopper::icons.facebook
                            class="h-5 w-5 text-gray-400 dark:text-gray-500"
                            aria-hidden="true"
                        />
                    </div>
                    <x-shopper::forms.input
                        wire:model="facebook_link"
                        id="facebook"
                        type="text"
                        class="pl-10"
                        placeholder="https://facebook.com/laravelshopper"
                    />
                </x-shopper::forms.group>
                <x-shopper::forms.group
                    class="col-span-6 lg:col-span-2"
                    :label="__('shopper::words.socials.instagram')"
                    for="instagram"
                >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-shopper::icons.instagram
                            class="h-5 w-5 text-gray-400 dark:text-gray-500"
                            aria-hidden="true"
                        />
                    </div>
                    <x-shopper::forms.input
                        wire:model="instagram_link"
                        id="instagram"
                        type="text"
                        class="pl-10"
                        placeholder="https://instagram.com/laravelshopper"
                    />
                </x-shopper::forms.group>
                <x-shopper::forms.group
                    class="col-span-6 lg:col-span-2"
                    :label="__('shopper::words.socials.twitter')"
                    for="twitter"
                >
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-shopper::icons.twitter class="h-5 w-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                    </div>
                    <x-shopper::forms.input
                        wire:model="twitter_link"
                        id="twitter"
                        type="text"
                        class="pl-10"
                        placeholder="https://twitter.com/laravelshopper"
                    />
                </x-shopper::forms.group>
            </div>
        </div>
        <div class="mt-8 border-t border-dashed border-gray-200 pt-10 dark:border-gray-700">
            <div class="flex items-center justify-between space-x-4">
                <x-shopper::buttons.default type="button" wire:click="previousStep">
                    {{ __('shopper::layout.forms.actions.back') }}
                </x-shopper::buttons.default>
                <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="save" class="text-white" aria-hidden="true" />
                    {{ __('shopper::pages/settings.initialization.action') }}
                </x-shopper::buttons.primary>
            </div>
        </div>
    </form>
</div>
