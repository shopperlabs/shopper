<div x-data="{ on: @entangle('is_enabled') }">
    <x:shopper-breadcrumb back="shopper.brands.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.brands.index')" title="Brands" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Create brand') }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper-input.group>
                </div>
                <div class="mt-4">
                    <x-shopper-input.group label="Website" for="website">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-secondary-500 dark:text-secondary-400 sm:text-sm sm:leading-5">https://</span>
                        </div>
                        <x-shopper-input.text wire:model.defer="website" id="website" type="text" class="pl-16" placeholder="www.example.com" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 border-t border-b border-secondary-200 dark:border-secondary-700 py-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-secondary-200': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="online" :value="__('Visibility')" />
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ __('Set brand visibility for the customers.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <x-shopper-input.group label="Description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md divide-y divide-secondary-200 dark:bg-secondary-800 dark:divide-secondary-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 transition duration-150 ease-in-out dark:text-primary-500/50">{{ __('Edit SEO preview') }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('Add a title and description to see how this collection might appear in a search engine listing.') }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-primary-800 font-medium leading-6 dark:text-primary-500/50">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5 dark:text-green-500/50">{{ env('APP_URL') }}/brands/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-secondary-500 text-sm leading-5 dark:text-secondary-400">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <x-shopper-input.group for="seo_title" label="Title">
                            <x-shopper-input.text wire:model.debounce.500ms="seoTitle" id="seo_title" type="text" autocomplete="off" />
                        </x-shopper-input.group>
                        <div>
                            <div class="flex items-center justify-between">
                                <x-shopper-label for="seo_description" :value="__('Description')" />
                                <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('160 characters') }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <x-shopper-input.textarea wire:model.debounce.500ms="seoDescription" id="seo_description" />
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white dark:bg-secondary-800 rounded-md shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label :value="__('Image preview')" />
                        <div class="mt-1">
                            <livewire:shopper-forms.uploads.single />
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
