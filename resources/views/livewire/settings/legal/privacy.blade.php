<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Privacy policy') }}</h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('Define the privacy policy to which all users and consumers of the products in your store will be subject.') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <div class="flex items-center justify-between">
                                <span class="grow flex flex-col" id="toggleLabel">
                                    <span class="text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                                        {{ __('Enabled') }}
                                    </span>
                                    <span class="text-sm leading-normal text-secondary-500 dark:text-secondary-400">
                                        {{ __('Setup page visibility for the customers.') }}
                                    </span>
                                </span>
                                <span role="checkbox" tabindex="0" @click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" aria-labelledby="toggleLabel" x-data="{ on: @entangle('isEnabled') }" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer">
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <x-shopper::forms.group :label="__('Content')" for="privacy-content">
                                <livewire:shopper-forms.trix :value="$content" />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 text-right sm:px-6">
                    <span class="inline-flex rounded-md shadow-sm">
                        <x-shopper::buttons.primary type="button" wire:click="store" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('layout.forms.actions.save') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
